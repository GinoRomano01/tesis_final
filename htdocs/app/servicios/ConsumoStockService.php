<?php

class ConsumoStockService {

    private const MOTIVO = 'consumo';

    private const ESTADOS_TERMINADO = ['Listo', 'Entregado'];
    public static function alCambiarEstado(int $idPedido, string $anterior, string $nuevo): array {
        if ($anterior === $nuevo) return [];

        if ($nuevo === 'En producción' || $nuevo === 'Listo') {
            return self::descontar($idPedido);
        }
        if ($nuevo === 'Pendiente') {
            return self::devolver($idPedido);
        }
        if ($nuevo === 'Cancelado' && !in_array($anterior, self::ESTADOS_TERMINADO, true)) {
            return self::devolver($idPedido);
        }
        return [];
    }

    public static function alDarDeBaja(int $idPedido, string $estadoAnterior): array {
        if (in_array($estadoAnterior, self::ESTADOS_TERMINADO, true)) return [];
        return self::devolver($idPedido);
    }

    public static function materialesDelPedido(int $idPedido): array {
        $descontado = self::tieneConsumoActivo($idPedido);

        $materiales = [];
        foreach (self::necesidades($idPedido) as $n) {
            $necesario = (float)$n['Necesario'];
            if ($necesario <= 0) continue;
            $stock = self::disponible((int)$n['IdMaterial'], (int)$n['TipoMaterial']);
            $falta = $descontado
                ? min($necesario, max(0, -$stock))
                : max(0, $necesario - $stock);

            $materiales[] = [
                'material'  => self::nombreMaterial($n),
                'tipo'      => (int)$n['TipoMaterial'] === 1 ? 'Madera' : 'Insumo',
                'productos' => $n['Productos'],
                'necesario' => $necesario,
                'stock'     => $stock,
                'falta'     => $falta,
            ];
        }

        return [
            'descontado'    => $descontado,
            'materiales'    => $materiales,
            'faltantes'     => count(array_filter($materiales, fn($m) => $m['falta'] > 0)),
            'sinMateriales' => self::productosSinMateriales($idPedido),
        ];
    }

    private static function descontar(int $idPedido): array {
        if (self::tieneConsumoActivo($idPedido)) return [];

        $necesidades = self::necesidades($idPedido);
        $sinReceta   = self::productosSinMateriales($idPedido);

        $faltantes = [];
        foreach ($necesidades as $n) {
            $tipo       = (int)$n['TipoMaterial'];
            $idMaterial = (int)$n['IdMaterial'];
            $necesario  = (float)$n['Necesario'];
            if ($necesario <= 0) continue;

            $disponible = self::disponible($idMaterial, $tipo);

            Db::query("
                INSERT INTO stock (IdMaterial, TipoMaterial, Cantidad, FechaIngreso, IdPedido, Motivo)
                VALUES (?, ?, ?, NOW(), ?, ?)
            ", [$idMaterial, $tipo, -$necesario, $idPedido, self::MOTIVO]);

            if ($disponible < $necesario) {
                $faltantes[] = [
                    'material'   => self::nombreMaterial($n),
                    'productos'  => $n['Productos'],
                    'necesario'  => $necesario,
                    'disponible' => $disponible,
                    'falta'      => $necesario - $disponible,
                ];
            }
        }

        if ($faltantes || $sinReceta) {
            self::avisarFaltantes($idPedido, $faltantes, $sinReceta);
        }

        $mensajes = [];
        foreach ($faltantes as $f) {
            $mensajes[] = 'Pedido #' . $idPedido . ': faltan ' . self::num($f['falta']) . ' de '
                . htmlspecialchars($f['material']) . ' (' . htmlspecialchars($f['productos']) . ').';
        }
        if ($sinReceta) {
            $mensajes[] = 'Pedido #' . $idPedido . ': ' . htmlspecialchars(implode(', ', $sinReceta))
                . ' no tiene materiales cargados, no se descontó stock.';
        }
        if ($faltantes) {
            $mensajes[] = 'Se avisó a gerentes, carpinteros y administradores qué material hay que encargar.';
        }
        return $mensajes;
    }

    private static function necesidades(int $idPedido): array {
        return Db::query("
            SELECT 1 AS TipoMaterial,
                   pm.IdMadera AS IdMaterial,
                   SUM(pc.Cantidad * pm.CantidadNecesaria) AS Necesario,
                   GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.NombredelProducto SEPARATOR ', ') AS Productos,
                   tm.Nombre AS NombreMadera, ma.Largo, ma.Ancho, ma.Alto,
                   NULL AS NombreInsumo
            FROM Pedido pe
            JOIN Venta v            ON v.Id = pe.IdVenta
            JOIN ProductoCarrito pc ON pc.IdCarrito = v.IdCarrito
            JOIN Producto pr        ON pr.Id = pc.IdProducto
            JOIN ProductoMaderas pm ON pm.IdProducto = pc.IdProducto AND pm.FechaBorrado IS NULL
            LEFT JOIN maderas ma      ON ma.Id = pm.IdMadera
            LEFT JOIN tipodemadera tm ON tm.Id = ma.IdTipodeMadera
            WHERE pe.Id = ?
            GROUP BY pm.IdMadera, tm.Nombre, ma.Largo, ma.Ancho, ma.Alto

            UNION ALL

            SELECT 2 AS TipoMaterial,
                   pi.IdInsumoCarpinteria AS IdMaterial,
                   SUM(pc.Cantidad * pi.CantidadNecesaria) AS Necesario,
                   GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.NombredelProducto SEPARATOR ', ') AS Productos,
                   NULL, NULL, NULL, NULL,
                   ic.Descripcion AS NombreInsumo
            FROM Pedido pe
            JOIN Venta v             ON v.Id = pe.IdVenta
            JOIN ProductoCarrito pc  ON pc.IdCarrito = v.IdCarrito
            JOIN Producto pr         ON pr.Id = pc.IdProducto
            JOIN ProductoInsumos pi  ON pi.IdProducto = pc.IdProducto AND pi.FechaBorrado IS NULL
            LEFT JOIN insumosdecarpinteria ic ON ic.Id = pi.IdInsumoCarpinteria
            WHERE pe.Id = ?
            GROUP BY pi.IdInsumoCarpinteria, ic.Descripcion
        ", [$idPedido, $idPedido])->fetchAll();
    }

    private static function productosSinMateriales(int $idPedido): array {
        $rows = Db::query("
            SELECT DISTINCT pr.NombredelProducto
            FROM Pedido pe
            JOIN Venta v            ON v.Id = pe.IdVenta
            JOIN ProductoCarrito pc ON pc.IdCarrito = v.IdCarrito
            JOIN Producto pr        ON pr.Id = pc.IdProducto
            WHERE pe.Id = ?
              AND NOT EXISTS (SELECT 1 FROM ProductoMaderas pm
                              WHERE pm.IdProducto = pr.Id AND pm.FechaBorrado IS NULL)
              AND NOT EXISTS (SELECT 1 FROM ProductoInsumos pi
                              WHERE pi.IdProducto = pr.Id AND pi.FechaBorrado IS NULL)
        ", [$idPedido])->fetchAll();
        return array_map(fn($r) => $r['NombredelProducto'] ?? 'Producto sin nombre', $rows);
    }

    private static function disponible(int $idMaterial, int $tipo): float {
        $row = Db::query("
            SELECT COALESCE(SUM(Cantidad), 0) AS Total
            FROM stock
            WHERE IdMaterial = ? AND TipoMaterial = ? AND FechaBorrado IS NULL
        ", [$idMaterial, $tipo])->fetch();
        return (float)($row['Total'] ?? 0);
    }

    private static function tieneConsumoActivo(int $idPedido): bool {
        return (int)Db::query("
            SELECT COUNT(*) FROM stock
            WHERE IdPedido = ? AND Motivo = ? AND FechaBorrado IS NULL
        ", [$idPedido, self::MOTIVO])->fetchColumn() > 0;
    }

    private static function devolver(int $idPedido): array {
        $filas = Db::query("
            UPDATE stock SET FechaBorrado = NOW()
            WHERE IdPedido = ? AND Motivo = ? AND FechaBorrado IS NULL
        ", [$idPedido, self::MOTIVO])->rowCount();

        return $filas > 0
            ? ['Pedido #' . $idPedido . ': se devolvió al stock el material que se había descontado.']
            : [];
    }

    private static function avisarFaltantes(int $idPedido, array $faltantes, array $sinReceta): void {
        $lineasTexto = [];
        $lineasHtml  = [];
        foreach ($faltantes as $f) {
            $lineasTexto[] = 'Faltan ' . self::num($f['falta']) . ' de ' . $f['material'] . ' para ' . $f['productos'];
            $lineasHtml[]  = '<li><strong>' . htmlspecialchars($f['material']) . '</strong>: faltan '
                . self::num($f['falta']) . ' (hay ' . self::num($f['disponible']) . ', se necesitan '
                . self::num($f['necesario']) . ') — para ' . htmlspecialchars($f['productos']) . '</li>';
        }
        foreach ($sinReceta as $p) {
            $lineasTexto[] = $p . ' no tiene materiales cargados';
            $lineasHtml[]  = '<li><strong>' . htmlspecialchars($p) . '</strong> no tiene materiales cargados: '
                . 'no se descontó stock para este producto.</li>';
        }

        $titulo = $faltantes
            ? 'Falta material para el pedido #' . $idPedido
            : 'Pedido #' . $idPedido . ' sin materiales cargados';

        $contenido = implode('. ', $lineasTexto) . '.';
        if (mb_strlen($contenido) > 500) {
            $contenido = mb_substr($contenido, 0, 497) . '...';
        }

        $html = '<p>El pedido <strong>#' . $idPedido . '</strong> pasó a producción.</p>'
            . '<ul>' . implode('', $lineasHtml) . '</ul>'
            . ($faltantes ? '<p>El stock de esos materiales quedó en negativo hasta que se cargue el ingreso.</p>' : '');

        NotificacionModel::notificarARolesConEmail(
            destinatariosAvisoFaltaMaterial(),
            [
                'Tipo'       => 'stock_bajo',
                'Titulo'     => $titulo,
                'Contenido'  => $contenido,
                'UrlDestino' => 'stock',
                'Icono'      => 'fa-triangle-exclamation',
            ],
            $titulo . ' — San Plácido',
            $titulo,
            $html,
            'Ver stock',
            'stock'
        );
    }

    private static function nombreMaterial(array $n): string {
        if ((int)$n['TipoMaterial'] === 1) {
            return trim(($n['NombreMadera'] ?? 'Madera') . ' '
                . number_format((float)$n['Largo'], 1) . '×'
                . number_format((float)$n['Ancho'], 1) . '×'
                . number_format((float)$n['Alto'],  1) . ' cm');
        }
        return $n['NombreInsumo'] ?? 'Insumo';
    }

    private static function num(float $n): string {
        return rtrim(rtrim(number_format($n, 2, ',', '.'), '0'), ',');
    }
}
