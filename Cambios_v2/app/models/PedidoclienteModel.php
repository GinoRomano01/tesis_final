<?php

class PedidoclienteModel extends Model {

    protected $table    = 'PedidosCliente';
    protected $fillable = ['IdClientes', 'IdTipoDePedido', 'IdVenta'];

    public function obtenerClientePorId($idCliente) {
        try {
            $result = Db::query(
                "SELECT c.Id, c.Nombre, c.Apellido, c.DNI, c.Telefono,
                        l.Nombre AS NombreLocalidad
                 FROM Clientes c
                 LEFT JOIN Localidad l ON l.Id = c.IdLocalidad
                 WHERE c.Id = ? AND c.FechaBorrado IS NULL",
                [$idCliente]
            );
            return $result->fetch();
        } catch (Exception $e) {
            error_log('PedidoclienteModel::obtenerClientePorId() - ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerPedidosPorCliente($idCliente) {
        try {
            $sql = "SELECT
                        v.Id                                AS IdVenta,
                        v.NumerodeVenta,
                        fc.FechaDeEmision                   AS Fecha,
                        fc.MontoTotal,
                        fc.Cuotas,
                        fc.MarcaTarjeta,
                        ep.Nombre                           AS EstadoPago,
                        tp.Nombre                           AS TipoPago,
                        pe.Id                               AS IdPedido,
                        pe.Estado                           AS EstadoPedido,
                        pe.Responsable,
                        e.Id                                AS IdEntrega,
                        e.CodigoEntrega,
                        e.FechaDeEntrega,
                        e.CostoEnvio,
                        ee.Nombre                           AS EstadoEntrega,
                        te.Nombre                           AS TipoEntrega,
                        GROUP_CONCAT(
                            p.NombredelProducto
                            ORDER BY p.NombredelProducto
                            SEPARATOR ', '
                        )                                   AS Productos,
                        SUM(pc2.Cantidad)                   AS CantidadTotal
                    FROM Venta v
                    JOIN FacturaCliente  fc  ON fc.Id  = v.IdFacturaCliente
                    JOIN EstadoDePago    ep  ON ep.Id  = fc.IdEstadoDePago
                    JOIN TipoDePago      tp  ON tp.Id  = fc.IdTipoDePago
                    JOIN Carrito         ca  ON ca.Id  = v.IdCarrito
                    JOIN ProductoCarrito pc2 ON pc2.IdCarrito = ca.Id
                    JOIN Producto        p   ON p.Id   = pc2.IdProducto
                    LEFT JOIN Pedido     pe  ON pe.IdVenta   = v.Id
                                            AND pe.FechaBorrado IS NULL
                    LEFT JOIN Entrega    e   ON e.IdVenta     = v.Id
                                            AND e.FechaBorrado  IS NULL
                    LEFT JOIN EstadosDeEntrega ee ON ee.Id = e.IdEstadosDeEntrega
                    LEFT JOIN TipoDeEntrega    te ON te.Id = e.IdTipoDeEntrega
                    WHERE ca.IdCliente = ?
                      AND v.FechaBorrado IS NULL
                    GROUP BY v.Id
                    ORDER BY fc.FechaDeEmision DESC";

            $result = Db::query($sql, [$idCliente]);
            return $result->fetchAll();

        } catch (Exception $e) {
            error_log('PedidoclienteModel::obtenerPedidosPorCliente() - ' . $e->getMessage());
            return [];
        }
    }
}