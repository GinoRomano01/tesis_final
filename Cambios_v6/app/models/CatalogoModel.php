<?php

class CatalogoModel extends Model {

    public function obtenerProductos(array $filtros, int $limit, int $offset): array {
        [$where, $params] = $this->buildWhere($filtros);
        $order = $this->buildOrder($filtros['orden'] ?? 'nuevo');

        $sql = "
            SELECT
                p.Id, p.NombredelProducto, p.Descripcion,
                p.PrecioVenta, p.Ancho, p.Largo, p.Alto,
                p.URLImagen,
                c.Nombre   AS NombreCategoria,
                td.Nombre  AS NombreDiseño,
                ta.Nombre  AS NombreAcabado,
                tp.Nombre  AS NombreTipo
            FROM Producto p
            LEFT JOIN Categoria             c   ON c.Id   = p.IdCategoria
            LEFT JOIN TipodeDiseño          td  ON td.Id  = p.IdTipodeDiseño
            LEFT JOIN TipodeAcabado         ta  ON ta.Id  = p.IdTipodeAcabado
            LEFT JOIN TipodeProducto        tp  ON tp.Id  = p.IdTipodeProducto
            LEFT JOIN TipodeHerraje         th  ON th.Id  = p.IdTipodeHerraje
            LEFT JOIN TipodeAlmacenamiento  tal ON tal.Id = p.IdTipodeAlmacenamiento
            LEFT JOIN ProductoMaderas       pm  ON pm.IdProducto = p.Id
            LEFT JOIN maderas               mad ON mad.Id = pm.IdMadera
            $where
            GROUP BY p.Id
            $order
            LIMIT ? OFFSET ?
        ";

        $params[] = $limit;
        $params[] = $offset;

        return Db::query($sql, $params)->fetchAll();
    }

    public function contarProductos(array $filtros): int {
        [$where, $params] = $this->buildWhere($filtros);

        $sql = "
            SELECT COUNT(DISTINCT p.Id)
            FROM Producto p
            LEFT JOIN TipodeHerraje        th  ON th.Id  = p.IdTipodeHerraje
            LEFT JOIN TipodeAlmacenamiento tal ON tal.Id = p.IdTipodeAlmacenamiento
            LEFT JOIN ProductoMaderas      pm  ON pm.IdProducto = p.Id
            LEFT JOIN maderas              mad ON mad.Id = pm.IdMadera
            $where
        ";

        return (int) Db::query($sql, $params)->fetchColumn();
    }

    public function obtenerUnoAleatorioPorTipo(): array {
        $todos = Db::query("
            SELECT
                p.Id, p.NombredelProducto, p.Descripcion,
                p.PrecioVenta, p.Ancho, p.Largo, p.Alto, p.URLImagen,
                p.IdTipodeProducto,
                c.Nombre  AS NombreCategoria,
                td.Nombre AS NombreDiseño,
                ta.Nombre AS NombreAcabado,
                tp.Nombre AS NombreTipo
            FROM Producto p
            LEFT JOIN Categoria      c  ON c.Id  = p.IdCategoria
            LEFT JOIN TipodeDiseño   td ON td.Id = p.IdTipodeDiseño
            LEFT JOIN TipodeAcabado  ta ON ta.Id = p.IdTipodeAcabado
            LEFT JOIN TipodeProducto tp ON tp.Id = p.IdTipodeProducto
            WHERE p.FechaBorrado IS NULL AND p.EstadoAprobacion = 'Aprobado'
            ORDER BY RAND()
        ")->fetchAll();
                                                            
        $unoPorTipo = [];
        foreach ($todos as $row) {
            $tipo = $row['IdTipodeProducto'] ?? 0;
            if (!isset($unoPorTipo[$tipo])) {
                $unoPorTipo[$tipo] = $row;
            }
        }
        return array_values($unoPorTipo);
    }

    private function buildWhere(array $f): array {
        $conds  = [];
        $params = [];                              
        $conds[] = 'p.FechaBorrado IS NULL';                                                            
        $conds[] = "p.EstadoAprobacion = 'Aprobado'";

        if (!empty($f['buscar'])) {
            $conds[]  = '(p.NombredelProducto LIKE ? OR p.Descripcion LIKE ?)';
            $params[] = '%' . $f['buscar'] . '%';
            $params[] = '%' . $f['buscar'] . '%';
        }
        if (!empty($f['categoria'])) {
            $conds[]  = 'p.IdCategoria = ?';
            $params[] = $f['categoria'];
        }
        if (!empty($f['madera'])) {                                                                                                                               
            $conds[]  = 'mad.IdTipodeMadera = ?';
            $params[] = $f['madera'];
        }
        if (!empty($f['diseño'])) {
            $conds[]  = 'p.IdTipodeDiseño = ?';
            $params[] = $f['diseño'];
        }
        if (!empty($f['acabado'])) {
            $conds[]  = 'p.IdTipodeAcabado = ?';
            $params[] = $f['acabado'];
        }
        if (!empty($f['color'])) {                                                                      
            $conds[]  = 'p.IdColorAcabado = ?';
            $params[] = $f['color'];
        }
        if (!empty($f['herraje'])) {
            $conds[]  = 'p.IdTipodeHerraje = ?';
            $params[] = $f['herraje'];
        }
        if (!empty($f['almacen'])) {
            $conds[]  = 'p.IdTipodeAlmacenamiento = ?';
            $params[] = $f['almacen'];
        }
        if (!empty($f['tipo'])) {
            $conds[]  = 'p.IdTipodeProducto = ?';
            $params[] = $f['tipo'];
        }

        $where = 'WHERE ' . implode(' AND ', $conds);
        return [$where, $params];
    }

    private function buildOrder(string $orden): string {
        return match($orden) {
            'precio_asc'  => 'ORDER BY p.PrecioVenta ASC',
            'precio_desc' => 'ORDER BY p.PrecioVenta DESC',
            default       => 'ORDER BY p.FechaCreacion DESC, p.Id DESC',
        };
    }
                                                                                                                                                            
    public function getCategorias(): array {
        return Db::query("SELECT Id, Nombre FROM Categoria WHERE FechaBorrado IS NULL ORDER BY Nombre")->fetchAll();
    }
    public function getMaderas(): array {
                                                                                
                                                                                     
        return Db::query("SELECT Id, Nombre FROM tipodemadera WHERE FechaBorrado IS NULL ORDER BY Nombre")->fetchAll();
    }
    public function getDiseños(): array {
        return Db::query("SELECT Id, Nombre FROM TipodeDiseño WHERE FechaBorrado IS NULL ORDER BY Nombre")->fetchAll();
    }
    public function getAcabados(): array {
        return Db::query("SELECT Id, Nombre FROM TipodeAcabado WHERE FechaBorrado IS NULL ORDER BY Nombre")->fetchAll();
    }
    public function getHerrajes(): array {
        return Db::query("SELECT Id, Nombre FROM TipodeHerraje WHERE FechaBorrado IS NULL ORDER BY Nombre")->fetchAll();
    }
    public function getAlmacenamientos(): array {
        return Db::query("SELECT Id, Nombre FROM TipodeAlmacenamiento WHERE FechaBorrado IS NULL ORDER BY Nombre")->fetchAll();
    }
    public function getTiposProducto(): array {
        return Db::query("SELECT Id, Nombre FROM TipodeProducto WHERE FechaBorrado IS NULL ORDER BY Nombre")->fetchAll();
    }

    public function getColoresAcabado(): array {
        return Db::query("SELECT Id, Nombre, ColorHex FROM ColorAcabado WHERE FechaBorrado IS NULL ORDER BY Nombre")->fetchAll();
    }
}