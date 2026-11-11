<?php

class ResumenModel extends Model {

    public function totalClientes(): int {
        try {
            return (int) Db::query(
                "SELECT COUNT(*) FROM Clientes WHERE FechaBorrado IS NULL"
            )->fetchColumn();
        } catch (Exception $e) {
            error_log('AdminModel::totalClientes() - ' . $e->getMessage());
            return 0;
        }
    }

    public function pedidosPendientes(): int {
        try {
            return (int) Db::query(
                "SELECT COUNT(*) FROM Pedido
                 WHERE Estado = 'Pendiente' AND FechaBorrado IS NULL"
            )->fetchColumn();
        } catch (Exception $e) {
            error_log('AdminModel::pedidosPendientes() - ' . $e->getMessage());
            return 0;
        }
    }

    public function totalProductos(): int {
        try {
            return (int) Db::query(
                "SELECT COUNT(*) FROM Producto WHERE FechaBorrado IS NULL"
            )->fetchColumn();
        } catch (Exception $e) {
            error_log('AdminModel::totalProductos() - ' . $e->getMessage());
            return 0;
        }
    }

    public function ventasMesActual(): float {
        try {
            $row = Db::query(
                "SELECT COALESCE(SUM(fc.MontoTotal), 0) AS Total
                 FROM Venta v
                 JOIN Comprobante fc ON fc.Id = v.IdComprobante
                 JOIN EstadodePago   ep ON ep.Id = fc.IdEstadodePago
                 WHERE ep.Nombre = 'Aprobado'
                   AND v.FechaBorrado IS NULL
                   AND MONTH(fc.FechadeEmision) = MONTH(NOW())
                   AND YEAR(fc.FechadeEmision)  = YEAR(NOW())"
            )->fetch();
            return (float)($row['Total'] ?? 0);
        } catch (Exception $e) {
            error_log('AdminModel::ventasMesActual() - ' . $e->getMessage());
            return 0.0;
        }
    }
    public function ultimasVentas(int $limite = 5): array {
        try {
            return Db::query("
                SELECT
                    v.NumerodeVenta,
                    fc.FechadeEmision,
                    fc.MontoTotal,
                    c.Nombre   AS ClienteNombre,
                    c.Apellido AS ClienteApellido,
                    ep.Nombre  AS EstadoPago,
                    GROUP_CONCAT(p.NombredelProducto ORDER BY p.Id SEPARATOR ', ') AS Productos
                FROM Venta v
                JOIN Comprobante  fc ON fc.Id = v.IdComprobante
                JOIN EstadodePago    ep ON ep.Id = fc.IdEstadodePago
                JOIN Carrito         ca ON ca.Id = v.IdCarrito
                JOIN Clientes        c  ON c.Id  = ca.IdCliente
                LEFT JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
                LEFT JOIN Producto        p  ON p.Id = pc.IdProducto
                WHERE v.FechaBorrado IS NULL
                GROUP BY v.Id
                ORDER BY fc.FechadeEmision DESC
                LIMIT ?
            ", [$limite])->fetchAll();
        } catch (Exception $e) {
            error_log('AdminModel::ultimasVentas() - ' . $e->getMessage());
            return [];
        }
    }

    public function ultimosPedidos(int $limite = 5): array {
        try {
            return Db::query("
                SELECT
                    p.Estado,
                    p.Responsable,
                    v.NumerodeVenta,
                    c.Nombre   AS ClienteNombre,
                    c.Apellido AS ClienteApellido,
                    GROUP_CONCAT(pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos
                FROM Pedido p
                JOIN Venta          v  ON v.Id  = p.IdVenta
                JOIN Carrito        ca ON ca.Id = v.IdCarrito
                JOIN Clientes       c  ON c.Id  = ca.IdCliente
                LEFT JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
                LEFT JOIN Producto        pr ON pr.Id = pc.IdProducto
                WHERE p.FechaBorrado IS NULL
                GROUP BY p.Id
                ORDER BY p.Id DESC
                LIMIT ?
            ", [$limite])->fetchAll();
        } catch (Exception $e) {
            error_log('AdminModel::ultimosPedidos() - ' . $e->getMessage());
            return [];
        }
    }
}