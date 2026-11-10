<?php

class PedidoclienteModel extends Model {

    public function obtenerClientePorId(int $idCliente): ?array {
        $row = Db::query("
            SELECT Id, Nombre, Apellido, DNI, Telefono
            FROM Clientes
            WHERE Id = ? AND FechaBorrado IS NULL
        ", [$idCliente])->fetch();

        return $row ?: null;
    }

    public function obtenerPedidosPorCliente(int $idCliente): array {
        return Db::query("
            SELECT
                v.Id,
                v.NumerodeVenta,
                fc.FechadeEmision   AS Fecha,
                fc.MontoTotal,
                fc.Cuotas,
                fc.MarcaTarjeta,
                ep.Nombre           AS EstadoPago,
                tp.Nombre           AS TipoPago,
                p.Estado            AS EstadoPedido,
                te.Nombre           AS TipoEntrega,
                ee.Nombre           AS EstadoEntrega,
                e.FechadeEntrega    AS FechaDeEntrega,
                e.CodigoEntrega,
                e.CostoEnvio,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos
            FROM Venta v
            JOIN FacturaCliente       fc ON fc.Id  = v.IdFacturaCliente
            JOIN Carrito              ca ON ca.Id  = v.IdCarrito
            LEFT JOIN EstadodePago    ep ON ep.Id  = fc.IdEstadodePago
            LEFT JOIN TipodePago      tp ON tp.Id  = fc.IdTipodePago
            LEFT JOIN Pedido          p  ON p.IdVenta = v.Id AND p.FechaBorrado IS NULL
            LEFT JOIN Entrega         e  ON e.IdVenta = v.Id
            LEFT JOIN TipodeEntrega   te ON te.Id  = e.IdTipodeEntrega
            LEFT JOIN EstadosdeEntrega ee ON ee.Id = e.IdEstadosdeEntrega
            LEFT JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto        pr ON pr.Id  = pc.IdProducto
            WHERE ca.IdCliente = ? AND v.FechaBorrado IS NULL
            GROUP BY v.Id
            ORDER BY fc.FechadeEmision DESC, v.Id DESC
        ", [$idCliente])->fetchAll();
    }
}