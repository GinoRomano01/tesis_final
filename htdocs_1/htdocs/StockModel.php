<?php

class StockModel extends Model {

    // ══ STOCK (tabla unificada — SOLO CANTIDAD, el precio vive en el catálogo) ═══

    public function listarStock(string $buscar = '', int $tipo = 0): array {
        $params = [];
        $where  = 'WHERE s.FechaBorrado IS NULL';

        if ($tipo === 1) {
            $where .= ' AND s.TipoMaterial = 1';
        } elseif ($tipo === 2) {
            $where .= ' AND s.TipoMaterial = 2';
        }

        if (!empty($buscar)) {
            $where   .= ' AND (
                (s.TipoMaterial = 1 AND (tm.Nombre LIKE ? OR CONCAT(ma.Largo,"x",ma.Ancho,"x",ma.Alto) LIKE ?))
                OR
                (s.TipoMaterial = 2 AND ic.Descripcion LIKE ?)
            )';
            $params[] = "%$buscar%";
            $params[] = "%$buscar%";
            $params[] = "%$buscar%";
        }

        return Db::query("
            SELECT
                s.Id,
                s.IdMaterial,
                s.TipoMaterial,
                s.Cantidad,
                COALESCE(ma.PrecioUnitario, ic.PrecioUnitario, 0) AS PrecioUnitario,
                s.Cantidad * COALESCE(ma.PrecioUnitario, ic.PrecioUnitario, 0) AS MontoTotal,
                s.FechaIngreso,
                -- Madera
                ma.Alto, ma.Largo, ma.Ancho, ma.Formato,
                ma.IdTipodeMadera,
                tm.Nombre  AS NombreMadera,
                -- Insumo
                ic.Descripcion AS NombreInsumo,
                ic.IdTipodeMaterial,
                ic.IdTipodeCorte,
                tmat.Nombre AS NombreTipoMaterial,
                tc.Nombre   AS NombreTipoCorte
            FROM stock s
            LEFT JOIN maderas              ma   ON s.TipoMaterial = 1 AND ma.Id   = s.IdMaterial
            LEFT JOIN tipodemadera         tm   ON tm.Id          = ma.IdTipodeMadera
            LEFT JOIN insumosdecarpinteria ic   ON s.TipoMaterial = 2 AND ic.Id   = s.IdMaterial
            LEFT JOIN tipodematerial       tmat ON tmat.Id        = ic.IdTipodeMaterial
            LEFT JOIN tipodecorte          tc   ON tc.Id          = ic.IdTipodeCorte
            $where
            ORDER BY s.FechaIngreso DESC, s.Id DESC
        ", $params)->fetchAll();
    }

    public function resumenStock(): array {
        return Db::query("
            SELECT
                s.IdMaterial,
                s.TipoMaterial,
                SUM(s.Cantidad) AS CantidadTotal,
                COALESCE(ma.PrecioUnitario, ic.PrecioUnitario, 0) AS UltimoPrecio,
                SUM(s.Cantidad) * COALESCE(ma.PrecioUnitario, ic.PrecioUnitario, 0) AS ValorTotal,
                MAX(s.FechaIngreso) AS UltimoIngreso,
                -- Madera
                tm.Nombre  AS NombreMadera,
                ma.Alto, ma.Largo, ma.Ancho, ma.Formato,
                -- Insumo
                ic.Descripcion AS NombreInsumo,
                tmat.Nombre AS NombreTipoMaterial
            FROM stock s
            LEFT JOIN maderas              ma   ON s.TipoMaterial = 1 AND ma.Id   = s.IdMaterial
            LEFT JOIN tipodemadera         tm   ON tm.Id          = ma.IdTipodeMadera
            LEFT JOIN insumosdecarpinteria ic   ON s.TipoMaterial = 2 AND ic.Id   = s.IdMaterial
            LEFT JOIN tipodematerial       tmat ON tmat.Id        = ic.IdTipodeMaterial
            WHERE s.FechaBorrado IS NULL
            GROUP BY s.IdMaterial, s.TipoMaterial
            ORDER BY ValorTotal DESC
        ")->fetchAll();
    }

    public function getStockById(int $id): array|false {
        return Db::query("
            SELECT s.*,
                   COALESCE(ma.PrecioUnitario, ic.PrecioUnitario, 0) AS PrecioUnitario,
                   s.Cantidad * COALESCE(ma.PrecioUnitario, ic.PrecioUnitario, 0) AS MontoTotal,
                   ma.Alto, ma.Largo, ma.Ancho, ma.Formato,
                   tm.Nombre  AS NombreMadera,
                   ic.Descripcion AS NombreInsumo,
                   tmat.Nombre AS NombreTipoMaterial
            FROM stock s
            LEFT JOIN maderas              ma   ON s.TipoMaterial = 1 AND ma.Id   = s.IdMaterial
            LEFT JOIN tipodemadera         tm   ON tm.Id          = ma.IdTipodeMadera
            LEFT JOIN insumosdecarpinteria ic   ON s.TipoMaterial = 2 AND ic.Id   = s.IdMaterial
            LEFT JOIN tipodematerial       tmat ON tmat.Id        = ic.IdTipodeMaterial
            WHERE s.Id = ? AND s.FechaBorrado IS NULL
        ", [$id])->fetch();
    }

    public function getCantidadMaterial(int $idMaterial, int $tipoMaterial): float {
        $row = Db::query("
            SELECT COALESCE(SUM(Cantidad), 0) AS Total
            FROM stock
            WHERE IdMaterial = ? AND TipoMaterial = ? AND FechaBorrado IS NULL
        ", [$idMaterial, $tipoMaterial])->fetch();
        return (float)($row['Total'] ?? 0);
    }

    public function getUltimoPrecioMaterial(int $idMaterial, int $tipoMaterial): float {
        $tabla = $tipoMaterial === 1 ? 'maderas' : 'insumosdecarpinteria';
        $row = Db::query("
            SELECT PrecioUnitario FROM {$tabla} WHERE Id = ? AND FechaBorrado IS NULL
        ", [$idMaterial])->fetch();
        return (float)($row['PrecioUnitario'] ?? 0);
    }

    public function crearStock(array $d): bool {
        Db::query("
            INSERT INTO stock (IdMaterial, TipoMaterial, Cantidad, FechaIngreso)
            VALUES (?, ?, ?, NOW())
        ", [
            (int)   $d['IdMaterial'],
            (int)   $d['TipoMaterial'],
            (float) $d['Cantidad'],
        ]);
        return true;
    }

    public function editarStock(int $id, array $d): bool {
        Db::query("
            UPDATE stock
            SET IdMaterial = ?, TipoMaterial = ?, Cantidad = ?, FechaIngreso = ?
            WHERE Id = ?
        ", [
            (int)   $d['IdMaterial'],
            (int)   $d['TipoMaterial'],
            (float) $d['Cantidad'],
            $d['FechaIngreso'] ?? date('Y-m-d H:i:s'),
            $id,
        ]);
        return true;
    }

    public function borrarStock(int $id): bool {
        Db::query("UPDATE stock SET FechaBorrado = NOW() WHERE Id = ?", [$id]);
        return true;
    }

    // ══ EXCEL: NOMBRE CANÓNICO DE MADERA ═══════════════════════════════════

    private function _nombreMaderaCanonico(string $tipoMadera, $alto, $largo, $ancho): string {
        return trim($tipoMadera) . ' '
            . number_format((float)$alto,  1, '.', '') . 'x'
            . number_format((float)$largo, 1, '.', '') . 'x'
            . number_format((float)$ancho, 1, '.', '') . 'cm';
    }

    // ══ EXPORTAR EXCEL ═══════════════════════════════════════════════════════

    public function exportarDatos(string $buscar = '', int $filtro = 0): array {
        $stocks = $this->listarStock($buscar, $filtro);

        $filas = [[
            'Tipo',
            'Nombre Material',
            'Cantidad',
            'Precio Unitario',
            'Monto Total',
            'Fecha Ingreso',
        ]];

        foreach ($stocks as $s) {
            $esMadera = (int)$s['TipoMaterial'] === 1;
            $nombre   = $esMadera
                ? $this->_nombreMaderaCanonico($s['NombreMadera'] ?? '—', $s['Alto'], $s['Largo'], $s['Ancho'])
                : trim($s['NombreInsumo'] ?? '—');

            $filas[] = [
                $esMadera ? 'madera' : 'insumo',
                $nombre,
                number_format($s['Cantidad'],      2, '.', ''),
                number_format($s['PrecioUnitario'],2, '.', ''),
                number_format($s['MontoTotal'],    2, '.', ''),
                date('d/m/Y H:i', strtotime($s['FechaIngreso'])),
            ];
        }

        return $filas;
    }

    private function _existeMaterial(int $id, int $tipo): bool {
        if ($tipo === 1) {
            $r = Db::query("SELECT Id FROM maderas WHERE Id=? AND FechaBorrado IS NULL", [$id])->fetch();
        } else {
            $r = Db::query("SELECT Id FROM insumosdecarpinteria WHERE Id=? AND FechaBorrado IS NULL", [$id])->fetch();
        }
        return !empty($r);
    }

    // ══ MADERAS (catálogo — incluye PrecioUnitario y Formato) ════════════════

    public function listarMaderas(string $buscar = ''): array {
        $params = [];
        $where  = 'WHERE m.FechaBorrado IS NULL';
        if (!empty($buscar)) {
            $where   .= ' AND tm.Nombre LIKE ?';
            $params[] = "%$buscar%";
        }
        return Db::query("
            SELECT m.*, tm.Nombre AS TipoMadera,
                   m.PrecioUnitario AS UltimoPrecio,
                   COALESCE(s.CantidadTotal, 0) AS CantidadStock
            FROM maderas m
            LEFT JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            LEFT JOIN (
                SELECT IdMaterial,
                       SUM(Cantidad) AS CantidadTotal
                FROM stock
                WHERE TipoMaterial = 1 AND FechaBorrado IS NULL
                GROUP BY IdMaterial
            ) s ON s.IdMaterial = m.Id
            $where
            ORDER BY tm.Nombre, m.Formato, m.Id DESC
        ", $params)->fetchAll();
    }

    public function getMaderaById(int $id): array|false {
        return Db::query("SELECT * FROM maderas WHERE Id = ? AND FechaBorrado IS NULL", [$id])->fetch();
    }

    public function crearMadera(array $d): int|false {
        Db::query("
            INSERT INTO maderas (Alto, Largo, Ancho, IdTipodeMadera, PrecioUnitario, Formato)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            (float) $d['Alto'],
            (float) $d['Largo'],
            (float) $d['Ancho'],
            (int)   $d['IdTipodeMadera'],
            (float) ($d['PrecioUnitario'] ?? 0),
            in_array($d['Formato'] ?? '', ['plancha', 'tablon']) ? $d['Formato'] : 'tablon',
        ]);
        return (int)Db::getInstance()->getConnection()->lastInsertId();
    }

    public function editarMadera(int $id, array $d): bool {
        Db::query("
            UPDATE maderas SET Alto=?, Largo=?, Ancho=?, IdTipodeMadera=?, PrecioUnitario=?, Formato=?
            WHERE Id=?
        ", [
            (float) $d['Alto'],
            (float) $d['Largo'],
            (float) $d['Ancho'],
            (int)   $d['IdTipodeMadera'],
            (float) ($d['PrecioUnitario'] ?? 0),
            in_array($d['Formato'] ?? '', ['plancha', 'tablon']) ? $d['Formato'] : 'tablon',
            $id,
        ]);
        return true;
    }

    public function borrarMadera(int $id): bool {
        Db::query("UPDATE maderas SET FechaBorrado = NOW() WHERE Id = ?", [$id]);
        return true;
    }

    // ══ INSUMOS (catálogo — incluye PrecioUnitario) ══════════════════════════

    public function listarInsumos(string $buscar = ''): array {
        $params = [];
        $where  = 'WHERE ic.FechaBorrado IS NULL';
        if (!empty($buscar)) {
            $where   .= ' AND (ic.Descripcion LIKE ? OR tmat.Nombre LIKE ?)';
            $params[] = "%$buscar%";
            $params[] = "%$buscar%";
        }
        return Db::query("
            SELECT ic.*, tmat.Nombre AS TipoMaterial, tc.Nombre AS TipoCorte,
                   ic.PrecioUnitario AS UltimoPrecio,
                   COALESCE(s.CantidadTotal, 0) AS CantidadStock
            FROM insumosdecarpinteria ic
            LEFT JOIN tipodematerial tmat ON tmat.Id = ic.IdTipodeMaterial
            LEFT JOIN tipodecorte   tc   ON tc.Id   = ic.IdTipodeCorte
            LEFT JOIN (
                SELECT IdMaterial,
                       SUM(Cantidad) AS CantidadTotal
                FROM stock
                WHERE TipoMaterial = 2 AND FechaBorrado IS NULL
                GROUP BY IdMaterial
            ) s ON s.IdMaterial = ic.Id
            $where
            ORDER BY ic.Descripcion
        ", $params)->fetchAll();
    }

    public function getInsumoById(int $id): array|false {
        return Db::query("SELECT * FROM insumosdecarpinteria WHERE Id = ? AND FechaBorrado IS NULL", [$id])->fetch();
    }

    public function crearInsumo(array $d): int|false {
        Db::query("
            INSERT INTO insumosdecarpinteria (Descripcion, IdTipodeMaterial, IdTipodeCorte, PrecioUnitario)
            VALUES (?, ?, ?, ?)
        ", [
            trim($d['Descripcion']),
            (int) $d['IdTipodeMaterial'],
            (int) $d['IdTipodeCorte'],
            (float) ($d['PrecioUnitario'] ?? 0),
        ]);
        return (int)Db::getInstance()->getConnection()->lastInsertId();
    }

    public function editarInsumo(int $id, array $d): bool {
        Db::query("
            UPDATE insumosdecarpinteria
            SET Descripcion=?, IdTipodeMaterial=?, IdTipodeCorte=?, PrecioUnitario=?
            WHERE Id=?
        ", [
            trim($d['Descripcion']),
            (int) $d['IdTipodeMaterial'],
            (int) $d['IdTipodeCorte'],
            (float) ($d['PrecioUnitario'] ?? 0),
            $id,
        ]);
        return true;
    }

    public function borrarInsumo(int $id): bool {
        Db::query("UPDATE insumosdecarpinteria SET FechaBorrado = NOW() WHERE Id = ?", [$id]);
        return true;
    }

    // ══ TIPOS / SELECTORES ════════════════════════════════════════════════════

    public function getTiposMadera(): array {
        return Db::query("SELECT Id, Nombre FROM tipodemadera ORDER BY Nombre")->fetchAll();
    }

    public function getTiposMaterial(): array {
        return Db::query("SELECT Id, Nombre FROM tipodematerial ORDER BY Nombre")->fetchAll();
    }

    public function getTiposCorte(): array {
        return Db::query("SELECT Id, Nombre FROM tipodecorte ORDER BY Nombre")->fetchAll();
    }

    public function getMaderasSelect(): array {
        return Db::query("
            SELECT ma.Id, tm.Nombre AS TipoMadera, ma.Formato,
                   ma.Alto, ma.Largo, ma.Ancho,
                   ma.PrecioUnitario AS UltimoPrecio,
                   COALESCE(s.CantidadTotal, 0) AS CantidadActual
            FROM maderas ma
            LEFT JOIN tipodemadera tm ON tm.Id = ma.IdTipodeMadera
            LEFT JOIN (
                SELECT IdMaterial,
                       SUM(Cantidad) AS CantidadTotal
                FROM stock WHERE TipoMaterial=1 AND FechaBorrado IS NULL
                GROUP BY IdMaterial
            ) s ON s.IdMaterial = ma.Id
            WHERE ma.FechaBorrado IS NULL
            ORDER BY tm.Nombre, ma.Formato
        ")->fetchAll();
    }

    public function getInsumosSelect(): array {
        return Db::query("
            SELECT ic.Id, ic.Descripcion,
                   ic.PrecioUnitario AS UltimoPrecio,
                   COALESCE(s.CantidadTotal, 0) AS CantidadActual
            FROM insumosdecarpinteria ic
            LEFT JOIN (
                SELECT IdMaterial,
                       SUM(Cantidad) AS CantidadTotal
                FROM stock WHERE TipoMaterial=2 AND FechaBorrado IS NULL
                GROUP BY IdMaterial
            ) s ON s.IdMaterial = ic.Id
            WHERE ic.FechaBorrado IS NULL
            ORDER BY ic.Descripcion
        ")->fetchAll();
    }

    public function exportarRefMaderas(): array {
        $maderas = $this->listarMaderas('');

        $filas = [[
            'Nombre Material',
            'Tipo de Madera',
            'Formato',
            'Alto (cm)',
            'Largo (cm)',
            'Ancho (cm)',
            'Precio Unitario',
            'Stock Actual',
        ]];

        foreach ($maderas as $m) {
            $nombreMat = $this->_nombreMaderaCanonico($m['TipoMadera'] ?? '—', $m['Alto'], $m['Largo'], $m['Ancho']);

            $filas[] = [
                $nombreMat,
                $m['TipoMadera'] ?? '',
                $m['Formato']    ?? '',
                number_format($m['Alto'],  1, '.', ''),
                number_format($m['Largo'], 1, '.', ''),
                number_format($m['Ancho'], 1, '.', ''),
                number_format($m['UltimoPrecio']  ?? 0, 2, '.', ''),
                number_format($m['CantidadStock'] ?? 0, 0, '.', ''),
            ];
        }

        return $filas;
    }

    public function exportarRefInsumos(): array {
        $insumos = $this->listarInsumos('');

        $filas = [[
            'Nombre Material',
            'Tipo de Material',
            'Tipo de Corte',
            'Precio Unitario',
            'Stock Actual',
        ]];

        foreach ($insumos as $ins) {
            $filas[] = [
                trim($ins['Descripcion'] ?? ''),
                $ins['TipoMaterial'] ?? '',
                $ins['TipoCorte']    ?? '',
                number_format($ins['UltimoPrecio']  ?? 0, 2, '.', ''),
                number_format($ins['CantidadStock'] ?? 0, 0, '.', ''),
            ];
        }

        return $filas;
    }
}