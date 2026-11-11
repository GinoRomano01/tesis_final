<?php

class EstadisticasStockModel extends Model {

    public function snapshotActual(): array {
    
        $sql = "
            SELECT
                x.TipoMaterial,
                SUM(x.Cantidad * x.Precio) AS valor,
                COUNT(*) AS items,
                SUM(CASE WHEN x.Cantidad <= 0 THEN 1 ELSE 0 END) AS sin_stock,
                SUM(CASE WHEN x.Cantidad > 0 AND x.Cantidad <= 10 THEN 1 ELSE 0 END) AS bajo_stock
            FROM (
                SELECT
                    s.TipoMaterial,
                    s.IdMaterial,
                    SUM(s.Cantidad) AS Cantidad,
                    MAX(CASE
                        WHEN s.TipoMaterial = 1 THEN m.PrecioUnitario
                        WHEN s.TipoMaterial = 2 THEN i.PrecioUnitario
                    END) AS Precio
                FROM stock s
                LEFT JOIN maderas m ON s.TipoMaterial = 1 AND m.Id = s.IdMaterial AND m.FechaBorrado IS NULL
                LEFT JOIN insumosdecarpinteria i ON s.TipoMaterial = 2 AND i.Id = s.IdMaterial AND i.FechaBorrado IS NULL
                WHERE s.FechaBorrado IS NULL
                GROUP BY s.TipoMaterial, s.IdMaterial
            ) x
            GROUP BY x.TipoMaterial
        ";
        $rows = Db::query($sql)->fetchAll();

        $valorMaderas = 0; $valorInsumos = 0;
        $itemsMaderas = 0; $itemsInsumos = 0;
        $sinStock = 0; $bajoStock = 0;

        foreach ($rows as $r) {
            if ((int)$r['TipoMaterial'] === 1) {
                $valorMaderas = (float)$r['valor'];
                $itemsMaderas = (int)$r['items'];
            } else {
                $valorInsumos = (float)$r['valor'];
                $itemsInsumos = (int)$r['items'];
            }
            $sinStock  += (int)$r['sin_stock'];
            $bajoStock += (int)$r['bajo_stock'];
        }

        return [
            'valor_total'      => $valorMaderas + $valorInsumos,
            'valor_maderas'    => $valorMaderas,
            'valor_insumos'    => $valorInsumos,
            'items_total'      => $itemsMaderas + $itemsInsumos,
            'items_maderas'    => $itemsMaderas,
            'items_insumos'    => $itemsInsumos,
            'items_sin_stock'  => $sinStock,
            'items_bajo_stock' => $bajoStock,
        ];
    }

    public function comparativaUltimoDiagnostico(): array {
        $actual = $this->snapshotActual();

        $ultimo = Db::query(
            "SELECT ValorTotalStock, ItemsBajoStock, ItemsSinStock, 
                    TotalMaderas, TotalInsumos, VariacionPromedioPct, FechaGenerado
             FROM StockDiagnostico
             WHERE FechaBorrado IS NULL
             ORDER BY FechaGenerado DESC
             LIMIT 1"
        )->fetch();

        if (!$ultimo) {
            return [
                'actual'      => $actual,
                'ultimo'      => null,
                'variaciones' => null,
            ];
        }

        $variaciones = [
            'valor_total' => $ultimo['ValorTotalStock'] > 0
                ? round(($actual['valor_total'] - $ultimo['ValorTotalStock']) / $ultimo['ValorTotalStock'] * 100, 1)
                : null,
            'items_bajo'  => $actual['items_bajo_stock'] - (int)$ultimo['ItemsBajoStock'],
            'items_sin'   => $actual['items_sin_stock']  - (int)$ultimo['ItemsSinStock'],
        ];

        return [
            'actual'      => $actual,
            'ultimo'      => $ultimo,
            'variaciones' => $variaciones,
        ];
    }

    private function entre(string $columna): string {
        return "$columna >= ? AND $columna < DATE_ADD(?, INTERVAL 1 DAY)";
    }

    public function evolucionValor(string $desde, string $hasta, int $maximo = 60): array {
        $filas = Db::query(
            "SELECT
                DATE_FORMAT(FechaGenerado, '%Y-%m-%d') AS fecha,
                ValorTotalStock                        AS valor,
                ItemsBajoStock                         AS bajo,
                ItemsSinStock                          AS sin,
                VariacionPromedioPct                   AS var_pct
             FROM StockDiagnostico
             WHERE FechaBorrado IS NULL
               AND " . $this->entre('FechaGenerado') . "
             ORDER BY FechaGenerado DESC
             LIMIT ?",
            [$desde, $hasta, $maximo]
        )->fetchAll();
        return array_reverse($filas);
    }

    public function evolucionSalud(string $desde, string $hasta, int $maximo = 60): array {
        $rows = array_reverse(Db::query(
            "SELECT
                Id,
                DATE_FORMAT(FechaGenerado, '%Y-%m-%d') AS fecha,
                DiagnosticoJSON
             FROM StockDiagnostico
             WHERE FechaBorrado IS NULL
               AND " . $this->entre('FechaGenerado') . "
             ORDER BY FechaGenerado DESC
             LIMIT ?",
            [$desde, $hasta, $maximo]
        )->fetchAll());

        $out = [];
        foreach ($rows as $r) {
            $data = json_decode($r['DiagnosticoJSON'], true);
            $puntaje = $data['analisis']['puntaje_salud_stock'] ?? null;
            if ($puntaje !== null) {
                $out[] = [
                    'fecha'   => $r['fecha'],
                    'puntaje' => (int)$puntaje,
                    'id'      => (int)$r['Id'],
                ];
            }
        }
        return $out;
    }

    public function inflacionPeriodo(string $desde, string $hasta): array {
        $row = Db::query(
            "SELECT
                TipoMaterial,
                AVG(Variacion) AS promedio,
                COUNT(*)       AS cambios
             FROM StockHistorialPrecios
             WHERE " . $this->entre('FechaRegistro') . "
               AND PrecioAnterior IS NOT NULL
               AND PrecioAnterior > 0
             GROUP BY TipoMaterial",
            [$desde, $hasta]
        )->fetchAll();

        $maderas = ['promedio' => 0, 'cambios' => 0];
        $insumos = ['promedio' => 0, 'cambios' => 0];

        foreach ($row as $r) {
            if ((int)$r['TipoMaterial'] === 1) {
                $maderas = ['promedio' => round((float)$r['promedio'], 2), 'cambios' => (int)$r['cambios']];
            } else {
                $insumos = ['promedio' => round((float)$r['promedio'], 2), 'cambios' => (int)$r['cambios']];
            }
        }

        return ['maderas' => $maderas, 'insumos' => $insumos];
    }

    public function materialesVolatiles(string $desde, string $hasta, int $limite = 10): array {
        return Db::query(
            "SELECT
                shp.TipoMaterial AS tipo,
                shp.IdMaterial   AS id,
                CASE 
                    WHEN shp.TipoMaterial = 1 THEN CONCAT(tm.Nombre, ' #', m.Id)
                    WHEN shp.TipoMaterial = 2 THEN i.Descripcion
                END              AS nombre,
                COUNT(*)         AS cambios,
                AVG(shp.Variacion) AS variacion_promedio,
                MAX(shp.Variacion) AS variacion_max,
                MIN(shp.PrecioNuevo) AS precio_min,
                MAX(shp.PrecioNuevo) AS precio_max
             FROM StockHistorialPrecios shp
             LEFT JOIN maderas m  ON shp.TipoMaterial = 1 AND m.Id = shp.IdMaterial
             LEFT JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
             LEFT JOIN insumosdecarpinteria i ON shp.TipoMaterial = 2 AND i.Id = shp.IdMaterial
             WHERE " . $this->entre('shp.FechaRegistro') . "
               AND shp.PrecioAnterior IS NOT NULL
             GROUP BY shp.TipoMaterial, shp.IdMaterial
             HAVING cambios >= 1
             ORDER BY cambios DESC, variacion_promedio DESC
             LIMIT ?",
            [$desde, $hasta, $limite]
        )->fetchAll();
    }

    public function historicoDiagnosticos(string $desde, string $hasta, int $limite = 10): array {
        return Db::query(
            "SELECT
                sd.Id,
                sd.FechaGenerado,
                sd.ValorTotalStock,
                sd.ItemsBajoStock,
                sd.ItemsSinStock,
                sd.VariacionPromedioPct,
                sd.ResumenTexto,
                u.NombredeUsuario AS usuario
             FROM StockDiagnostico sd
             LEFT JOIN Usuario u ON u.Id = sd.GeneradoPor
             WHERE sd.FechaBorrado IS NULL
               AND " . $this->entre('sd.FechaGenerado') . "
             ORDER BY sd.FechaGenerado DESC
             LIMIT ?",
            [$desde, $hasta, $limite]
        )->fetchAll();
    }

    public function ultimoAnalisisIA(): ?array {
        $row = Db::query(
            "SELECT Id, FechaGenerado, DiagnosticoJSON, ResumenTexto
             FROM StockDiagnostico
             WHERE FechaBorrado IS NULL
             ORDER BY FechaGenerado DESC
             LIMIT 1"
        )->fetch();

        if (!$row) return null;

        $data = json_decode($row['DiagnosticoJSON'], true);
        return [
            'id'                => (int)$row['Id'],
            'fecha'             => $row['FechaGenerado'],
            'resumen'           => $row['ResumenTexto'],
            'puntaje'           => $data['analisis']['puntaje_salud_stock'] ?? null,
            'prioridad'         => $data['analisis']['prioridad_inmediata'] ?? null,
            'alertas'           => $data['analisis']['alertas_criticas'] ?? [],
            'recomendaciones'   => $data['analisis']['recomendaciones_reposicion'] ?? [],
        ];
    }
}