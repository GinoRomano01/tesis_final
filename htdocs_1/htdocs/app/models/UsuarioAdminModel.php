<?php

class UsuarioAdminModel extends Model {

    public function getTiposUsuario(): array {
        return Db::query(
            "SELECT Id, Nombre FROM TipodeUsuario WHERE FechaBorrado IS NULL ORDER BY Id"
        )->fetchAll();
    }

    public function getTiposRol(): array {
        return Db::query(
            "SELECT Id, Nombre FROM TipodeRol WHERE FechaBorrado IS NULL ORDER BY Id"
        )->fetchAll();
    }

    public function getLocalidades(): array {
        return Db::query(
            "SELECT Id, Nombre FROM Localidad WHERE FechaBorrado IS NULL ORDER BY Nombre"
        )->fetchAll();
    }

    public function getTiposDni(): array {
        return Db::query(
            "SELECT Id, Nombre FROM TipodeDni WHERE FechaBorrado IS NULL ORDER BY Id"
        )->fetchAll();
    }

    public function listarUsuarios(string $buscar = '', int $idTipoUsuario = 0): array {
        $params = [];
        $where  = 'WHERE u.FechaBorrado IS NULL';

        if (!empty($buscar)) {
            $where   .= ' AND (
                c.Nombre               LIKE ?
                OR c.Apellido          LIKE ?
                OR u.CorreoElectronico LIKE ?
                OR u.NombredeUsuario   LIKE ?
            )';
            $like     = "%$buscar%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($idTipoUsuario > 0) {
            $where   .= ' AND u.IdTipodeUsuario = ?';
            $params[] = $idTipoUsuario;
        }

        return Db::query("
            SELECT
                u.Id,
                u.NombredeUsuario,
                u.CorreoElectronico,
                u.Confirmado,
                u.IdTipodeUsuario,
                u.IdTipodeRol,
                u.IdCliente,
                tu.Nombre AS TipoUsuarioNombre,
                tr.Nombre AS TipoRolNombre,
                c.Nombre      AS ClienteNombre,
                c.Apellido    AS ClienteApellido,
                c.DNI,
                c.Telefono,
                c.IdLocalidad,
                l.Nombre  AS LocalidadNombre,
                dom.CodigoPostal
            FROM Usuario u
            LEFT JOIN Clientes      c   ON c.Id   = u.IdCliente
            LEFT JOIN TipodeUsuario tu  ON tu.Id  = u.IdTipodeUsuario
            LEFT JOIN TipodeRol     tr  ON tr.Id  = u.IdTipodeRol
            LEFT JOIN Localidad     l   ON l.Id   = c.IdLocalidad
            LEFT JOIN Domicilio     dom ON dom.Id = c.IdDomicilio
            $where
            ORDER BY u.Id DESC
        ", $params)->fetchAll();
    }

    public function contarPorTipo(): array {
        $rows = Db::query("
            SELECT tu.Nombre, COUNT(u.Id) AS Total
            FROM Usuario u
            LEFT JOIN TipodeUsuario tu ON tu.Id = u.IdTipodeUsuario
            WHERE u.FechaBorrado IS NULL
            GROUP BY u.IdTipodeUsuario
        ")->fetchAll();

        $result = [];
        foreach ($rows as $r) {
            $result[$r['Nombre']] = (int) $r['Total'];
        }
        return $result;
    }

    public function correoExiste(string $correo, int $exceptoId = 0): bool {
        $sql    = "SELECT COUNT(*) AS total FROM Usuario WHERE CorreoElectronico = ? AND FechaBorrado IS NULL";
        $params = [$correo];
        if ($exceptoId > 0) {
            $sql    .= ' AND Id != ?';
            $params[] = $exceptoId;
        }
        return (int) Db::query($sql, $params)->fetch()['total'] > 0;
    }

    public function crearUsuario(array $u): int|false {
        $db = Db::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            Db::query(
                "INSERT INTO Domicilio (IdTipoDomicilio, CodigoPostal) VALUES (1, ?)",
                [$u['CodigoPostal'] ?? null]
            );
            $idDomicilio = $db->lastInsertId();

            Db::query("
                INSERT INTO Clientes
                    (DNI, Nombre, Apellido, Telefono, IdLocalidad, IdTipodeDni, IdDomicilio, IdTipodomicilio)
                VALUES (?, ?, ?, ?, ?, 1, ?, 1)
            ", [
                $u['DNI']         ?? null,
                trim($u['Nombre']),
                trim($u['Apellido']),
                $u['Telefono']    ?? null,
                $u['IdLocalidad'] ? (int)$u['IdLocalidad'] : null,
                $idDomicilio,
            ]);
            $idCliente = $db->lastInsertId();

            Db::query("
                INSERT INTO Usuario
                    (NombredeUsuario, Contraseña, CorreoElectronico,
                     IdTipodeUsuario, IdTipodeRol, IdCliente, Confirmado)
                VALUES (?, ?, ?, ?, ?, ?, 1)
            ", [
                trim($u['NombredeUsuario']),
                password_hash($u['Contrasena'], PASSWORD_DEFAULT),
                trim($u['CorreoElectronico']),
                (int)$u['IdTipodeUsuario'],
                (int)$u['IdTipodeRol'],
                $idCliente,
            ]);
            $idUsuario = $db->lastInsertId();

            $db->commit();
            return (int)$idUsuario;

        } catch (\Exception $e) {
            $db->rollBack();
            error_log('Error crearUsuario: ' . $e->getMessage());
            return false;
        }
    }

    public function editarUsuario(int $id, array $u): bool {
        Db::query("
            UPDATE Clientes c
            JOIN Usuario u ON u.IdCliente = c.Id
            SET c.Nombre      = ?,
                c.Apellido    = ?,
                c.Telefono    = ?,
                c.DNI         = ?,
                c.IdLocalidad = ?
            WHERE u.Id = ?
        ", [
            trim($u['Nombre']),
            trim($u['Apellido']),
            $u['Telefono']    ?? null,
            $u['DNI']         ?? null,
            $u['IdLocalidad'] ? (int)$u['IdLocalidad'] : null,
            $id,
        ]);

        Db::query("
            UPDATE Domicilio dom
            JOIN Clientes c ON c.IdDomicilio = dom.Id
            JOIN Usuario  u ON u.IdCliente   = c.Id
            SET dom.CodigoPostal = ?
            WHERE u.Id = ?
        ", [
            $u['CodigoPostal'] ?? null,
            $id,
        ]);

        $sql    = "UPDATE Usuario SET
                    NombredeUsuario   = ?,
                    CorreoElectronico = ?,
                    IdTipodeUsuario   = ?,
                    IdTipodeRol       = ?";
        $params = [
            trim($u['NombredeUsuario']),
            trim($u['CorreoElectronico']),
            (int)$u['IdTipodeUsuario'],
            (int)$u['IdTipodeRol'],
        ];

        if (!empty(trim($u['Contrasena'] ?? ''))) {
            $sql    .= ', Contraseña = ?';
            $params[] = password_hash(trim($u['Contrasena']), PASSWORD_DEFAULT);
        }

        $sql    .= ' WHERE Id = ?';
        $params[] = $id;

        Db::query($sql, $params);
        return true;
    }

    public function darDeBaja(int $id): bool {
        Db::query("UPDATE Usuario SET FechaBorrado = NOW() WHERE Id = ?", [$id]);
        return true;
    }

    public function restaurar(int $id): bool {
        Db::query("UPDATE Usuario SET FechaBorrado = NULL WHERE Id = ?", [$id]);
        return true;
    }
}