<?php

class ProductoModel extends Model {

    protected $table    = 'Producto';
    protected $fillable = [
        'NombredelProducto',
        'Descripcion',
        'URLImagen',
        'Ancho', 'Largo', 'Alto',
        'CostoTotalMateriales',
        'PorcentajeGanancia',
        'TiempoFabricacionHoras',
        'PrecioVenta',
        'IdCategoria',
        'IdTipodeProducto',
        'IdTipodeDiseño',
        'IdTipodeAcabado',
        'IdTipodeHerraje',
        'IdTipodeAlmacenamiento',
    ];

    public function obtenerTodos() {
        try {
            $sql = "SELECT p.Id, p.NombredelProducto, p.Descripcion, p.URLImagen,
                           p.Ancho, p.Largo, p.Alto,
                           p.CostoTotalMateriales, p.PorcentajeGanancia,
                           p.TiempoFabricacionHoras, p.PrecioVenta, p.FechaCreacion,
                           c.Nombre   AS NombreCategoria,
                           tp.Nombre  AS NombreTipoProducto,
                           td.Nombre  AS NombreTipoDiseño,
                           ta.Nombre  AS NombreTipoAcabado
                    FROM Producto p
                    LEFT JOIN Categoria        c   ON p.IdCategoria      = c.Id
                    LEFT JOIN TipodeProducto   tp  ON p.IdTipodeProducto = tp.Id
                    LEFT JOIN TipodeDiseño     td  ON p.`IdTipodeDiseño` = td.Id
                    LEFT JOIN TipodeAcabado    ta  ON p.IdTipodeAcabado  = ta.Id
                    WHERE p.FechaBorrado IS NULL
                    ORDER BY p.Id DESC";
            return Db::query($sql)->fetchAll();
        } catch (Exception $e) {
            error_log('ProductoModel::obtenerTodos() - ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT p.*,
                           c.Nombre    AS NombreCategoria,
                           tp.Nombre   AS NombreTipoProducto,
                           td.Nombre   AS NombreTipoDiseño,
                           ta.Nombre   AS NombreTipoAcabado,
                           th.Nombre   AS NombreTipoHerraje,
                           talm.Nombre AS NombreTipoAlmacenamiento
                    FROM Producto p
                    LEFT JOIN Categoria            c    ON p.IdCategoria            = c.Id
                    LEFT JOIN TipodeProducto       tp   ON p.IdTipodeProducto       = tp.Id
                    LEFT JOIN TipodeDiseño         td   ON p.`IdTipodeDiseño`       = td.Id
                    LEFT JOIN TipodeAcabado        ta   ON p.IdTipodeAcabado        = ta.Id
                    LEFT JOIN TipodeHerraje        th   ON p.IdTipodeHerraje        = th.Id
                    LEFT JOIN TipodeAlmacenamiento talm ON p.IdTipodeAlmacenamiento = talm.Id
                    WHERE p.Id = ? AND p.FechaBorrado IS NULL";
            $producto = Db::query($sql, [$id])->fetch();
            if ($producto) {
                $producto['imagenes_extra'] = $this->obtenerImagenesExtra($id);
                $producto['maderas']        = $this->obtenerMaderasProducto($id);
                $producto['insumos']        = $this->obtenerInsumosProducto($id);
            }
            return $producto;
        } catch (Exception $e) {
            error_log('ProductoModel::obtenerPorId() - ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerImagenesExtra($idProducto) {
        try {
            return Db::query(
                "SELECT * FROM ProductoImagenes WHERE IdProducto=? AND FechaBorrado IS NULL ORDER BY Orden",
                [$idProducto]
            )->fetchAll();
        } catch (Exception $e) { return []; }
    }

    public function agregarImagenExtra($idProducto, $url, $orden) {
        try {
            Db::query("INSERT INTO ProductoImagenes (IdProducto,URLImagen,Orden) VALUES (?,?,?)",
                [$idProducto, $url, $orden]);
            return true;
        } catch (Exception $e) { return false; }
    }

    public function eliminarImagenesExtra($idProducto) {
        try {
            Db::query("UPDATE ProductoImagenes SET FechaBorrado=NOW() WHERE IdProducto=?", [$idProducto]);
            return true;
        } catch (Exception $e) { return false; }
    }

    public function obtenerMaderasProducto($idProducto) {
        try {
            $sql = "SELECT pm.Id, pm.IdMadera, pm.CantidadNecesaria,
                           pm.CostoUnitario, pm.CostoTotal, pm.Observaciones,
                           tm.Nombre AS TipoMadera,
                           m.Alto, m.Largo, m.Ancho,
                           COALESCE(sk.CantidadTotal, 0) AS StockActual
                    FROM ProductoMaderas pm
                    JOIN Maderas m        ON pm.IdMadera       = m.Id
                    JOIN TipodeMadera tm  ON m.IdTipodeMadera  = tm.Id
                    LEFT JOIN (
                        SELECT IdMaterial, SUM(Cantidad) AS CantidadTotal
                        FROM stock WHERE TipoMaterial=1 AND FechaBorrado IS NULL
                        GROUP BY IdMaterial
                    ) sk ON sk.IdMaterial = m.Id
                    WHERE pm.IdProducto = ? AND pm.FechaBorrado IS NULL";
            return Db::query($sql, [$idProducto])->fetchAll();
        } catch (Exception $e) { return []; }
    }

    public function guardarMaderaProducto($idProducto, $datos) {
        try {
            $costoTotal = (float)($datos['CantidadNecesaria'] ?? 0) * (float)($datos['CostoUnitario'] ?? 0);
            Db::query(
                "INSERT INTO ProductoMaderas (IdProducto,IdMadera,CantidadNecesaria,CostoUnitario,CostoTotal,Observaciones)
                 VALUES (?,?,?,?,?,?)",
                [$idProducto, $datos['IdMadera'], $datos['CantidadNecesaria'],
                 $datos['CostoUnitario'], $costoTotal, $datos['Observaciones'] ?? null]
            );
            return true;
        } catch (Exception $e) { return false; }
    }

    public function eliminarMaderasProducto($idProducto) {
        try {
            Db::query("UPDATE ProductoMaderas SET FechaBorrado=NOW() WHERE IdProducto=?", [$idProducto]);
            return true;
        } catch (Exception $e) { return false; }
    }

    public function obtenerInsumosProducto($idProducto) {
        try {
            $sql = "SELECT pi.Id, pi.IdInsumoCarpinteria, pi.CantidadNecesaria,
                           pi.CostoUnitario, pi.CostoTotal, pi.Observaciones,
                           ic.Descripcion AS NombreInsumo,
                           tmat.Nombre    AS TipoMaterial,
                           COALESCE(sk.CantidadTotal, 0) AS StockActual
                    FROM ProductoInsumos pi
                    JOIN InsumosdeCarpinteria ic ON pi.IdInsumoCarpinteria = ic.Id
                    LEFT JOIN TipodeMaterial tmat ON ic.IdTipodeMaterial   = tmat.Id
                    LEFT JOIN (
                        SELECT IdMaterial, SUM(Cantidad) AS CantidadTotal
                        FROM stock WHERE TipoMaterial=2 AND FechaBorrado IS NULL
                        GROUP BY IdMaterial
                    ) sk ON sk.IdMaterial = ic.Id
                    WHERE pi.IdProducto = ? AND pi.FechaBorrado IS NULL";
            return Db::query($sql, [$idProducto])->fetchAll();
        } catch (Exception $e) { return []; }
    }

    public function guardarInsumoProducto($idProducto, $datos) {
        try {
            $costoTotal = (float)($datos['CantidadNecesaria'] ?? 0) * (float)($datos['CostoUnitario'] ?? 0);
            Db::query(
                "INSERT INTO ProductoInsumos (IdProducto,IdInsumoCarpinteria,CantidadNecesaria,CostoUnitario,CostoTotal,Observaciones)
                 VALUES (?,?,?,?,?,?)",
                [$idProducto, $datos['IdInsumoCarpinteria'], $datos['CantidadNecesaria'],
                 $datos['CostoUnitario'], $costoTotal, $datos['Observaciones'] ?? null]
            );
            return true;
        } catch (Exception $e) { return false; }
    }

    public function eliminarInsumosProducto($idProducto) {
        try {
            Db::query("UPDATE ProductoInsumos SET FechaBorrado=NOW() WHERE IdProducto=?", [$idProducto]);
            return true;
        } catch (Exception $e) { return false; }
    }

    public function recalcularCostoMateriales($idProducto): bool {
        try {

            Db::query("
                UPDATE Producto SET CostoTotalMateriales = (
                    SELECT COALESCE(SUM(pm.CostoTotal), 0)
                    FROM ProductoMaderas pm
                    WHERE pm.IdProducto = ? AND pm.FechaBorrado IS NULL
                ) + (
                    SELECT COALESCE(SUM(pi.CostoTotal), 0)
                    FROM ProductoInsumos pi
                    WHERE pi.IdProducto = ? AND pi.FechaBorrado IS NULL
                )
                WHERE Id = ?
            ", [$idProducto, $idProducto, $idProducto]);

            Db::query("
                UPDATE Producto
                SET PrecioVenta = ROUND(
                    CostoTotalMateriales * (1 + PorcentajeGanancia / 100), 2
                )
                WHERE Id = ?
            ", [$idProducto]);

            return true;
        } catch (Exception $e) {
            error_log('ProductoModel::recalcularCostoMateriales() - ' . $e->getMessage());
            return false;
        }
    }

    public function actualizarPorcentaje($idProducto, float $porcentaje): bool {
        try {
            Db::query("
                UPDATE Producto
                SET PorcentajeGanancia = ?,
                    PrecioVenta = ROUND(CostoTotalMateriales * (1 + ? / 100), 2)
                WHERE Id = ?
            ", [$porcentaje, $porcentaje, $idProducto]);
            return true;
        } catch (Exception $e) { return false; }
    }

    public function actualizarCostosDesdeStock(): void {
        try {
           
            Db::query("
                UPDATE ProductoMaderas pm
                INNER JOIN Maderas ma ON ma.Id = pm.IdMadera AND ma.FechaBorrado IS NULL
                SET pm.CostoUnitario = ma.PrecioUnitario,
                    pm.CostoTotal    = pm.CantidadNecesaria * ma.PrecioUnitario
                WHERE pm.FechaBorrado IS NULL
            ");

            Db::query("
                UPDATE ProductoInsumos pi
                INNER JOIN InsumosDeCarpinteria ic ON ic.Id = pi.IdInsumoCarpinteria AND ic.FechaBorrado IS NULL
                SET pi.CostoUnitario = ic.PrecioUnitario,
                    pi.CostoTotal    = pi.CantidadNecesaria * ic.PrecioUnitario
                WHERE pi.FechaBorrado IS NULL
            ");
        } catch (Exception $e) {
            error_log('ProductoModel::actualizarCostosDesdeStock() - ' . $e->getMessage());
        }
    }

    public function recalcularTodos(): int {

        $this->actualizarCostosDesdeStock();

        $productos = Db::query("SELECT Id FROM Producto WHERE FechaBorrado IS NULL")->fetchAll();
        $actualizados = 0;
        foreach ($productos as $p) {
            if ($this->recalcularCostoMateriales($p['Id'])) $actualizados++;
        }
        return $actualizados;
    }


    public function crear($datos) {
        try {
            $filteredData = array_intersect_key($datos, array_flip($this->fillable));
            $fields       = implode(', ', array_keys($filteredData));
            $placeholders = implode(', ', array_fill(0, count($filteredData), '?'));
            Db::query(
                "INSERT INTO {$this->table} ({$fields}, FechaCreacion) VALUES ({$placeholders}, NOW())",
                array_values($filteredData)
            );
            return Db::getInstance()->getConnection()->lastInsertId();
        } catch (Exception $e) {
            error_log('ProductoModel::crear() - ' . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id, $datos) {
        try {
            $filteredData = array_intersect_key($datos, array_flip($this->fillable));
            if (empty($filteredData)) return false;
            $setClause = implode(' = ?, ', array_keys($filteredData)) . ' = ?';
            $values    = array_values($filteredData);
            $values[]  = $id;
            Db::query("UPDATE {$this->table} SET {$setClause} WHERE Id = ?", $values);
            return true;
        } catch (Exception $e) {
            error_log('ProductoModel::actualizar() - ' . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        try {
            Db::query("UPDATE {$this->table} SET FechaBorrado=NOW() WHERE Id=?", [$id]);
            return true;
        } catch (Exception $e) { return false; }
    }


    public function obtenerCategorias()          { return $this->_lista('Categoria'); }
    public function obtenerTiposProducto()       { return $this->_lista('TipodeProducto'); }
    public function obtenerTiposDiseño()         { return $this->_lista('TipodeDiseño'); }
    public function obtenerTiposAcabado()        { return $this->_lista('TipodeAcabado'); }
    public function obtenerTiposHerraje()        { return $this->_lista('TipodeHerraje'); }
    public function obtenerTiposAlmacenamiento() { return $this->_lista('TipodeAlmacenamiento'); }

    public function obtenerMaderas() {
        try {
            return Db::query("
                SELECT m.Id,
                       CONCAT(tm.Nombre, ' (', m.Largo,'×',m.Ancho,'×',m.Alto,' cm)') AS Nombre,
                       m.Alto, m.Largo, m.Ancho,
                       m.PrecioUnitario AS PrecioUnitario,
                       COALESCE(sk.CantidadTotal, 0) AS StockActual
                FROM Maderas m
                JOIN TipodeMadera tm ON m.IdTipodeMadera = tm.Id
                LEFT JOIN (
                    SELECT IdMaterial,
                           SUM(Cantidad) AS CantidadTotal
                    FROM stock WHERE TipoMaterial=1 AND FechaBorrado IS NULL
                    GROUP BY IdMaterial
                ) sk ON sk.IdMaterial = m.Id
                WHERE m.FechaBorrado IS NULL ORDER BY tm.Nombre ASC
            ")->fetchAll();
        } catch (Exception $e) { return []; }
    }

    public function obtenerInsumos() {
        try {
            return Db::query("
                SELECT ic.Id,
                       ic.Descripcion AS Nombre,
                       tmat.Nombre AS TipoMaterial,
                       ic.PrecioUnitario AS PrecioUnitario,
                       COALESCE(sk.CantidadTotal, 0) AS StockActual
                FROM InsumosdeCarpinteria ic
                LEFT JOIN TipodeMaterial tmat ON ic.IdTipodeMaterial = tmat.Id
                LEFT JOIN (
                    SELECT IdMaterial,
                           SUM(Cantidad) AS CantidadTotal
                    FROM stock WHERE TipoMaterial=2 AND FechaBorrado IS NULL
                    GROUP BY IdMaterial
                ) sk ON sk.IdMaterial = ic.Id
                WHERE ic.FechaBorrado IS NULL ORDER BY ic.Descripcion ASC
            ")->fetchAll();
        } catch (Exception $e) { return []; }
    }

    private function _lista($tabla) {
        try {
            return Db::query(
                "SELECT Id, Nombre FROM {$tabla} WHERE FechaBorrado IS NULL ORDER BY Nombre ASC"
            )->fetchAll();
        } catch (Exception $e) { return []; }
    }
}