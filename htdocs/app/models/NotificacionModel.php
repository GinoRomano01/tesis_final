<?php

class NotificacionModel {

    public static function actorActual($ctx = null) {
        if ($ctx === 'cliente') {
            return !empty($_SESSION['cliente_id'])
                ? ['campo' => 'IdCliente', 'id' => (int) $_SESSION['cliente_id']]
                : null;
        }

        if ($ctx === 'admin') {
            return isset($_SESSION['usuario_id']) && (int)($_SESSION['tipo_usuario'] ?? 2) !== 2
                ? ['campo' => 'IdUsuario', 'id' => (int) $_SESSION['usuario_id']]
                : null;
        }                                                                      
                                                                      
        if ((int)($_SESSION['tipo_usuario'] ?? 0) === 2 && !empty($_SESSION['cliente_id'])) {
            return ['campo' => 'IdCliente', 'id' => (int) $_SESSION['cliente_id']];
        }
        if (isset($_SESSION['usuario_id'])) {
            return ['campo' => 'IdUsuario', 'id' => (int) $_SESSION['usuario_id']];
        }
        if (isset($_SESSION['cliente_id'])) {
            return ['campo' => 'IdCliente', 'id' => (int) $_SESSION['cliente_id']];
        }
        return null;
    }

                                                                 

    public static function ultimasParaActual($limit = 8, $ctx = null) {
        $actor = self::actorActual($ctx);
        if (!$actor) return [];

        $sql = "SELECT * FROM notificaciones 
                WHERE {$actor['campo']} = ? AND FechaBorrado IS NULL 
                ORDER BY FechaCreacion DESC 
                LIMIT " . (int) $limit;

        $result = Db::query($sql, [$actor['id']]);
        return $result->fetchAll();
    }                                   

    public static function paginadoParaActual($pagina = 1, $porPagina = 20, $ctx = null) {
        $actor = self::actorActual($ctx);
        if (!$actor) return ['items' => [], 'total' => 0];

        $offset = ($pagina - 1) * $porPagina;

        $sql = "SELECT * FROM notificaciones 
                WHERE {$actor['campo']} = ? AND FechaBorrado IS NULL 
                ORDER BY FechaCreacion DESC 
                LIMIT {$porPagina} OFFSET {$offset}";
        $items = Db::query($sql, [$actor['id']])->fetchAll();

        $total = Db::query(
            "SELECT COUNT(*) AS c FROM notificaciones WHERE {$actor['campo']} = ? AND FechaBorrado IS NULL",
            [$actor['id']]
        )->fetch()['c'];

        return ['items' => $items, 'total' => (int) $total];
    }
                              
    public static function contarNoLeidas($ctx = null) {
        $actor = self::actorActual($ctx);
        if (!$actor) return 0;

        $sql = "SELECT COUNT(*) AS c FROM notificaciones 
                WHERE {$actor['campo']} = ? AND Leida = 0 AND FechaBorrado IS NULL";
        $result = Db::query($sql, [$actor['id']]);
        return (int) $result->fetch()['c'];
    }

    public static function marcarTodasComoLeidas($ctx = null) {
        $actor = self::actorActual($ctx);
        if (!$actor) return false;

        $sql = "UPDATE notificaciones 
                SET Leida = 1, FechaLeida = NOW() 
                WHERE {$actor['campo']} = ? AND Leida = 0 AND FechaBorrado IS NULL";
        Db::query($sql, [$actor['id']]);
        return true;
    }                                                       

    public static function crear($data) {
        $sql = "INSERT INTO notificaciones 
                (IdUsuario, IdCliente, Tipo, Titulo, Contenido, UrlDestino, Icono) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        Db::query($sql, [
            $data['IdUsuario']   ?? null,
            $data['IdCliente']   ?? null,
            $data['Tipo']        ?? 'sistema',
            $data['Titulo'],
            $data['Contenido']   ?? null,
            $data['UrlDestino']  ?? null,
            $data['Icono']       ?? 'fa-bell',
        ]);
        return true;
    }

    public static function notificarARoles($idsRoles, $data) {
        $placeholders = implode(',', array_fill(0, count($idsRoles), '?'));
        $usuarios = Db::query(
            "SELECT Id FROM Usuario WHERE IdTipodeRol IN ({$placeholders}) AND FechaBorrado IS NULL",
            $idsRoles
        )->fetchAll();

        foreach ($usuarios as $u) {
            $data['IdUsuario'] = $u['Id'];
            $data['IdCliente'] = null;
            self::crear($data);
        }
        return true;
    }

    public static function notificarARolesConEmail(array $destino, array $data, string $asuntoEmail, string $tituloEmail, string $contenidoHtml, ?string $ctaTexto = null, ?string $ctaUrl = null): bool {
        $idsRoles = array_map('intval', $destino['roles'] ?? []);
        $admins   = !empty($destino['admins']);

        $condiciones = [];
        if ($idsRoles) {
            $condiciones[] = 'IdTipodeRol IN (' . implode(',', array_fill(0, count($idsRoles), '?')) . ')';
        }
        if ($admins) {
            $condiciones[] = 'IdTipodeUsuario = 3';
        }
        if (!$condiciones) return true;

        $usuarios = Db::query(
            "SELECT Id, NombredeUsuario, CorreoElectronico FROM Usuario
             WHERE (" . implode(' OR ', $condiciones) . ") AND FechaBorrado IS NULL",
            $idsRoles
        )->fetchAll();

        if ($ctaUrl && !preg_match('#^https?://#', $ctaUrl)) {
            $ctaUrl = URL . ltrim($ctaUrl, '/');
        }

        $mailer = null;
        foreach ($usuarios as $u) {
            $data['IdUsuario'] = $u['Id'];
            $data['IdCliente'] = null;
            self::crear($data);

            if (empty($u['CorreoElectronico'])) continue;
            try {
                $mailer = $mailer ?? new Mailer();
                $mailer->enviarGenerico(
                    $u['CorreoElectronico'],
                    $u['NombredeUsuario'] ?? '',
                    $asuntoEmail,
                    $tituloEmail,
                    $contenidoHtml,
                    $ctaTexto,
                    $ctaUrl
                );
            } catch (\Throwable $e) {
                error_log('NotificacionModel::notificarARolesConEmail() - fallo al mandar mail - ' . $e->getMessage());
            }
        }
        return true;
    }

    public static function crearConEmail(array $data, string $asuntoEmail, string $tituloEmail, string $contenidoHtml, ?string $ctaTexto = null, ?string $ctaUrl = null): bool {
        self::crear($data);

        $idCliente = $data['IdCliente'] ?? null;
        if (!$idCliente) return true;

        try {
            $row = Db::query("
                SELECT u.CorreoElectronico, c.Nombre
                FROM Usuario u
                JOIN Clientes c ON c.Id = u.IdCliente
                WHERE u.IdCliente = ?
                  AND u.FechaBorrado IS NULL
                  AND u.CorreoElectronico IS NOT NULL
                  AND u.CorreoElectronico != ''
                LIMIT 1
            ", [$idCliente])->fetch();

            if (!$row) return true;                                                             

            if ($ctaUrl && !preg_match('#^https?://#', $ctaUrl)) {
                $ctaUrl = URL . ltrim($ctaUrl, '/');
            }

            $mailer = new Mailer();
            $mailer->enviarGenerico(
                $row['CorreoElectronico'],
                $row['Nombre'] ?? '',
                $asuntoEmail,
                $tituloEmail,
                $contenidoHtml,
                $ctaTexto,
                $ctaUrl
            );
        } catch (\Throwable $e) {
            error_log('NotificacionModel::crearConEmail() - fallo al mandar mail - ' . $e->getMessage());
        }

        return true;
    }
}