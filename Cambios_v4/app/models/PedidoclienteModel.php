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
                e.Id AS IdEntrega,
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

    public function obtenerUbicacionParaEntrega(int $idEntrega, int $idCliente): array {
        try {
            $row = Db::query("
                SELECT e.IdEstadosdeEntrega, e.IdRuta, ca.IdCliente
                FROM Entrega e
                JOIN Venta   v  ON v.Id  = e.IdVenta
                JOIN Carrito ca ON ca.Id = v.IdCarrito
                WHERE e.Id = ? AND e.FechaBorrado IS NULL
                LIMIT 1
            ", [$idEntrega])->fetch();

            if (!$row || (int)$row['IdCliente'] !== $idCliente) {
                return ['ok' => false, 'error' => 'No autorizado'];
            }
            if ((int)$row['IdEstadosdeEntrega'] !== 4 || empty($row['IdRuta'])) {
                return ['ok' => false, 'error' => 'Esta entrega no está en camino en este momento.'];
            }

            $ruta = Db::query("SELECT IdRepartidor FROM RutaEntrega WHERE Id = ?", [(int)$row['IdRuta']])->fetch();
            if (!$ruta) {
                return ['ok' => false, 'error' => 'Sin datos de ruta.'];
            }

            $ubic = Db::query("
                SELECT Lat, Lng, FechaActualizacion FROM UbicacionRepartidor WHERE IdRepartidor = ?
            ", [(int)$ruta['IdRepartidor']])->fetch();

            if (!$ubic) {
                return ['ok' => false, 'error' => 'Todavía no hay ubicación disponible. Probá de nuevo en un rato.'];
            }

            return [
                'ok'            => true,
                'lat'           => (float)$ubic['Lat'],
                'lng'           => (float)$ubic['Lng'],
                'hace_segundos' => time() - strtotime($ubic['FechaActualizacion']),
            ];
        } catch (Exception $e) {
            error_log('PedidoclienteModel::obtenerUbicacionParaEntrega() - ' . $e->getMessage());
            return ['ok' => false, 'error' => 'DEBUG: ' . $e->getMessage()];
        }
    }
}