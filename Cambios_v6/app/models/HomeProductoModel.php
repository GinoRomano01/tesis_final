<?php

class HomeProductoModel extends Model {

    protected $table    = 'Producto';
    protected $fillable = [];

    public function obtenerMasNuevos(int $limite = 5): array {
        try {
            return Db::query("
                SELECT
                    p.Id,
                    p.NombredelProducto,
                    p.Descripcion,
                    p.URLImagen,
                    p.PrecioVenta,
                    p.FechaCreacion,
                    c.Nombre AS NombreCategoria
                FROM Producto p
                LEFT JOIN Categoria c ON c.Id = p.IdCategoria
                WHERE p.FechaBorrado IS NULL
                  AND p.EstadoAprobacion = 'Aprobado'
                  AND p.PrecioVenta > 0
                ORDER BY p.FechaCreacion DESC
                LIMIT ?
            ", [$limite])->fetchAll();
        } catch (Exception $e) {
            error_log('HomeProductoModel::obtenerMasNuevos() - ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerMasVendidos(int $limite = 5): array {
        try {
            return Db::query("
                SELECT
                    p.Id,
                    p.NombredelProducto,
                    p.Descripcion,
                    p.URLImagen,
                    p.PrecioVenta,
                    c.Nombre AS NombreCategoria,
                    SUM(pc.Cantidad) AS TotalVendido
                FROM ProductoCarrito pc
                JOIN Carrito          ca  ON ca.Id  = pc.IdCarrito
                JOIN Venta            v   ON v.IdCarrito = ca.Id
                JOIN Comprobante   fc  ON fc.Id  = v.IdComprobante
                JOIN EstadodePago     ep  ON ep.Id  = fc.IdEstadodePago
                JOIN Producto         p   ON p.Id   = pc.IdProducto
                LEFT JOIN Categoria   c   ON c.Id   = p.IdCategoria
                WHERE v.FechaBorrado  IS NULL
                  AND p.FechaBorrado  IS NULL
                  AND ep.Nombre = 'Aprobado'
                GROUP BY p.Id
                ORDER BY TotalVendido DESC
                LIMIT ?
            ", [$limite])->fetchAll();
        } catch (Exception $e) {
            error_log('HomeProductoModel::obtenerMasVendidos() - ' . $e->getMessage());
            return [];
        }
    }
}