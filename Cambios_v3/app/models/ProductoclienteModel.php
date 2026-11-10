<?php

class ProductoclienteModel extends Model {

    public function getProducto(int $id): array|false {
        return Db::query("
            SELECT
                p.*,
                c.Nombre       AS NombreCategoria,
                tp.Nombre      AS NombreTipo,
                td.Nombre      AS NombreDiseño,
                ta.Nombre      AS NombreAcabado,
                th.Nombre      AS NombreHerraje,
                th.Descripcion AS DescHerraje,
                tal.Nombre     AS NombreAlmacenamiento,
                tal.Descripcion AS DescAlmacenamiento
            FROM Producto p
            LEFT JOIN Categoria             c   ON c.Id   = p.IdCategoria
            LEFT JOIN TipodeProducto        tp  ON tp.Id  = p.IdTipodeProducto
            LEFT JOIN TipodeDiseño          td  ON td.Id  = p.`IdTipodeDiseño`
            LEFT JOIN TipodeAcabado         ta  ON ta.Id  = p.IdTipodeAcabado
            LEFT JOIN TipodeHerraje         th  ON th.Id  = p.IdTipodeHerraje
            LEFT JOIN TipodeAlmacenamiento  tal ON tal.Id = p.IdTipodeAlmacenamiento
            WHERE p.Id = ? AND p.FechaBorrado IS NULL
            LIMIT 1
        ", [$id])->fetch();
    }

    public function getImagenes(int $idProducto): array {
        return Db::query("
            SELECT URLImagen, Orden
            FROM ProductoImagenes
            WHERE IdProducto = ? AND FechaBorrado IS NULL
            ORDER BY Orden ASC
        ", [$idProducto])->fetchAll();
    }

    public function getMaderasProducto(int $idProducto): array {
        return Db::query("
            SELECT
                pm.CantidadNecesaria,
                pm.Observaciones,
                m.Alto, m.Largo, m.Ancho,
                tm.Nombre AS NombreMadera
            FROM ProductoMaderas pm
            JOIN maderas      m  ON m.Id  = pm.IdMadera
            JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            WHERE pm.IdProducto = ? AND pm.FechaBorrado IS NULL
        ", [$idProducto])->fetchAll();
    }

    public function getCaracteristicas(int $idProducto): array {
        $p = $this->getProducto($idProducto);
        if (!$p) return [];

        $chars = [];

        if ($p['Ancho'] || $p['Alto'] || $p['Largo']) {
            $chars['Ancho'] = $p['Ancho'] ? number_format($p['Ancho'], 1) . ' cm' : '—';
            $chars['Alto']  = $p['Alto']  ? number_format($p['Alto'],  1) . ' cm' : '—';
            $chars['Largo'] = $p['Largo'] ? number_format($p['Largo'], 1) . ' cm' : '—';
        }

        if ($p['NombreCategoria']) $chars['Categoría']       = $p['NombreCategoria'];
        if ($p['NombreTipo'])      $chars['Tipo de producto'] = $p['NombreTipo'];
        if ($p['NombreDiseño'])    $chars['Diseño']           = $p['NombreDiseño'];
        if ($p['NombreAcabado'])   $chars['Acabado']          = $p['NombreAcabado'];

        if ($p['NombreHerraje']) {
            $chars['Herraje'] = $p['NombreHerraje'];
            if ($p['DescHerraje']) $chars['Detalle de herraje'] = $p['DescHerraje'];
        }

        if ($p['NombreAlmacenamiento']) {
            $chars['Almacenamiento'] = $p['NombreAlmacenamiento'];
            if ($p['DescAlmacenamiento']) $chars['Detalle de almacenamiento'] = $p['DescAlmacenamiento'];
        }

        return $chars;
    }

    public function getRelacionados(int $idProducto, int $idCategoria): array {
        if ($idCategoria <= 0) {
            return Db::query("
                SELECT Id, NombredelProducto, PrecioVenta, URLImagen
                FROM Producto
                WHERE Id != ? AND FechaBorrado IS NULL
                ORDER BY RAND() LIMIT 4
            ", [$idProducto])->fetchAll();
        }

        return Db::query("
            SELECT Id, NombredelProducto, PrecioVenta, URLImagen
            FROM Producto
            WHERE IdCategoria = ? AND Id != ? AND FechaBorrado IS NULL
            ORDER BY RAND() LIMIT 4
        ", [$idCategoria, $idProducto])->fetchAll();
    }
}