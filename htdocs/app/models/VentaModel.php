<?php

class VentaModel extends Model {

    private static array $interesesCuotas = [
        1  => 0.00,
        3  => 0.12,
        6  => 0.25,
        12 => 0.55,
    ];

    public static function calcularConInteres(float $subtotal, int $cuotas): array {
        $porcentaje = self::$interesesCuotas[$cuotas] ?? 0.00;
        $interes    = round($subtotal * $porcentaje, 2);
        return [
            'subtotal'   => $subtotal,
            'interes'    => $interes,
            'total'      => round($subtotal + $interes, 2),
            'porcentaje' => $porcentaje,
        ];
    }


    public function getEstadosPago(): array {
        return Db::query(
            "SELECT Id, Nombre FROM EstadodePago WHERE FechaBorrado IS NULL ORDER BY Id"
        )->fetchAll();
    }

    public function getTiposPago(): array {
        return Db::query(
            "SELECT Id, Nombre FROM TipodePago WHERE FechaBorrado IS NULL ORDER BY Nombre"
        )->fetchAll();
    }

    public function getClientes(): array {
        return Db::query(
            "SELECT Id, Nombre, Apellido FROM Clientes WHERE FechaBorrado IS NULL ORDER BY Apellido, Nombre"
        )->fetchAll();
    }

    public function getProductos(): array {
        return Db::query(
            "SELECT Id, NombredelProducto AS Nombre, PrecioVenta
             FROM Producto
             WHERE FechaBorrado IS NULL AND EstadoAprobacion = 'Aprobado'
             ORDER BY NombredelProducto"
        )->fetchAll();
    }

    public function listarVentas(string $buscar = '', int $idEstado = 0): array {
        $params = [];
        $where  = 'WHERE v.FechaBorrado IS NULL';

        if (!empty($buscar)) {
            $where   .= ' AND (
                c.Nombre                LIKE ?
                OR c.Apellido           LIKE ?
                OR pr.NombredelProducto LIKE ?
                OR v.NumerodeVenta      LIKE ?
                OR fc.NumeroComprobante     LIKE ?
            )';
            $like     = "%$buscar%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($idEstado > 0) {
            $where   .= ' AND fc.IdEstadodePago = ?';
            $params[] = $idEstado;
        }

        return Db::query("
            SELECT
                v.Id,
                v.NumerodeVenta,
                v.CantidadTotal,
                -- Factura
                fc.Id             AS IdFactura,
                fc.NumeroComprobante,
                fc.FechadeEmision,
                fc.SubTotal,
                fc.MontoTotal,
                fc.Cuotas,
                fc.MarcaTarjeta,
                fc.IdEstadodePago,
                fc.IdTipodePago,
                -- Estados / tipos
                ep.Nombre         AS EstadoPagoNombre,
                tp.Nombre         AS TipoPagoNombre,
                -- Cliente
                c.Id              AS IdCliente,
                c.Nombre          AS ClienteNombre,
                c.Apellido        AS ClienteApellido,
                c.Telefono        AS ClienteTelefono,
                -- Productos
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos,
                -- Entrega
                e.CodigoEntrega,
                ee.Nombre         AS EstadoEntregaNombre
            FROM Venta v
            LEFT JOIN Comprobante    fc  ON fc.Id  = v.IdComprobante
            LEFT JOIN EstadodePago      ep  ON ep.Id  = fc.IdEstadodePago
            LEFT JOIN TipodePago        tp  ON tp.Id  = fc.IdTipodePago
            LEFT JOIN Carrito           ca  ON ca.Id  = v.IdCarrito
            LEFT JOIN Clientes          c   ON c.Id   = ca.IdCliente
            LEFT JOIN ProductoCarrito   pc  ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto          pr  ON pr.Id  = pc.IdProducto
            LEFT JOIN Entrega           e   ON e.IdVenta = v.Id
            LEFT JOIN EstadosdeEntrega  ee  ON ee.Id  = e.IdEstadosdeEntrega
            $where
            GROUP BY v.Id
            ORDER BY v.Id DESC
        ", $params)->fetchAll();
    }

    public function getVentaById(int $id): array|false {
        return Db::query("
            SELECT
                v.*,
                fc.Id             AS IdFactura,
                fc.NumeroComprobante,
                fc.FechadeEmision,
                fc.SubTotal,
                fc.MontoTotal,
                fc.Cuotas,
                fc.MarcaTarjeta,
                fc.IdEstadodePago,
                fc.IdTipodePago,
                ep.Nombre         AS EstadoPagoNombre,
                tp.Nombre         AS TipoPagoNombre,
                c.Nombre          AS ClienteNombre,
                c.Apellido        AS ClienteApellido,
                c.Telefono        AS ClienteTelefono,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos,
                e.CodigoEntrega,
                e.Direccion       AS DireccionEntrega,
                ee.Nombre         AS EstadoEntregaNombre
            FROM Venta v
            LEFT JOIN Comprobante    fc  ON fc.Id  = v.IdComprobante
            LEFT JOIN EstadodePago      ep  ON ep.Id  = fc.IdEstadodePago
            LEFT JOIN TipodePago        tp  ON tp.Id  = fc.IdTipodePago
            LEFT JOIN Carrito           ca  ON ca.Id  = v.IdCarrito
            LEFT JOIN Clientes          c   ON c.Id   = ca.IdCliente
            LEFT JOIN ProductoCarrito   pc  ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto          pr  ON pr.Id  = pc.IdProducto
            LEFT JOIN Entrega           e   ON e.IdVenta = v.Id
            LEFT JOIN EstadosdeEntrega  ee  ON ee.Id  = e.IdEstadosdeEntrega
            WHERE v.Id = ?
            GROUP BY v.Id
        ", [$id])->fetch();
    }

    public function contarPorEstadoPago(): array {
        $rows = Db::query("
            SELECT ep.Nombre, COUNT(v.Id) AS Total
            FROM Venta v
            LEFT JOIN Comprobante fc ON fc.Id = v.IdComprobante
            LEFT JOIN EstadodePago   ep ON ep.Id = fc.IdEstadodePago
            WHERE v.FechaBorrado IS NULL
            GROUP BY fc.IdEstadodePago
        ")->fetchAll();

        $result = [];
        foreach ($rows as $r) {
            $result[$r['Nombre']] = (int) $r['Total'];
        }
        return $result;
    }

    public function crearVentaPresencial(
        int $idCliente, array $items, int $idTipoPago,
        int $cuotas = 1, string $marcaTarjeta = '', float $interes = 0.0
    ): int|false {
        $db = Db::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $cliente = Db::query(
                "SELECT Id FROM Clientes WHERE Id = ? LIMIT 1", [$idCliente]
            )->fetch();
            if (!$cliente) { $db->rollBack(); return false; }

            $emisor   = Db::query("
                SELECT e.Id FROM Emisor e
                JOIN Usuario u ON u.Id = e.IdUsuario
                WHERE u.IdCliente = ? LIMIT 1
            ", [$idCliente])->fetch();
            $idEmisor = $emisor['Id'] ?? null;
            $subtotal   = 0.0;
            $impuestos  = 0.0;
            $cantTotal  = 0;
            $ivaPorProducto = [];
            foreach ($items as $item) {
                $idProd = (int)$item['id_producto'];
                if (!isset($ivaPorProducto[$idProd])) {
                    $row = Db::query("
                        SELECT COALESCE(t.Porcentaje, 21.00) AS Porcentaje
                        FROM Producto p
                        LEFT JOIN TipoIVA t ON t.Id = p.IdTipoIVA
                        WHERE p.Id = ? LIMIT 1
                    ", [$idProd])->fetch();
                    $ivaPorProducto[$idProd] = (float)($row['Porcentaje'] ?? 21.00);
                }

                $porcentaje    = $ivaPorProducto[$idProd];
                $precioConIva  = (float)$item['precio'];
                $cantidad      = (int)$item['cantidad'];
                $totalItem     = $precioConIva * $cantidad;
                $subtotalItem  = $porcentaje > 0 ? $totalItem / (1 + $porcentaje / 100) : $totalItem;
                $subtotal  += $subtotalItem;
                $impuestos += $totalItem - $subtotalItem;
                $cantTotal += $cantidad;
            }
            $subtotal   = round($subtotal, 2);
            $impuestos  = round($impuestos, 2);
            $montoTotal = round($subtotal + $impuestos + $interes, 2);

            Db::query(
                "INSERT INTO Carrito (Cantidad, IdCliente, Estado) VALUES (?, ?, 1)",
                [$cantTotal, $idCliente]
            );
            $idCarrito = $db->lastInsertId();

            $idsProductoCarrito = [];
            foreach ($items as $item) {
                Db::query(
                    "INSERT INTO ProductoCarrito (IdProducto, IdCarrito, Cantidad, PrecioUnitario, PorcentajeIva) VALUES (?, ?, ?, ?, ?)",
                    [
                        (int)$item['id_producto'], $idCarrito, (int)$item['cantidad'],
                        (float)$item['precio'], $ivaPorProducto[(int)$item['id_producto']],
                    ]
                );
            }

            $nroFactura       = $this->generarNumeroComprobante();
            $idEstadoAprobado = $this->getIdEstadoPago('Aprobado');

            Db::query("
                INSERT INTO Comprobante
                    (NumeroComprobante, FechadeEmision, SubTotal, Impuestos,
                    MontoTotal, Interes, Cuotas, MarcaTarjeta, IdEmisor,
                    IdTipodePago, IdEstadodePago, IdDatosEmpresa, IdClientes)
                VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)
            ", [
                $nroFactura,
                $subtotal,
                $impuestos,
                $montoTotal,
                $interes,
                $cuotas,
                $marcaTarjeta,
                $idEmisor,
                $idTipoPago,
                $idEstadoAprobado,
                $idCliente,
            ]);
            $idFactura = $db->lastInsertId();

            $nroVenta = $this->generarNumeroVenta();
            Db::query("
                INSERT INTO Venta (NumerodeVenta, CantidadTotal, IdCarrito, IdComprobante)
                VALUES (?, ?, ?, ?)
            ", [$nroVenta, $cantTotal, $idCarrito, $idFactura]);
            $idVenta = $db->lastInsertId();

            foreach ($items as $item) {
                $prod = Db::query(
                    "SELECT Ancho, Alto, Largo FROM Producto WHERE Id = ? LIMIT 1",
                    [(int)$item['id_producto']]
                )->fetch();
                Db::query("
                    INSERT INTO DetallesVenta (IdVenta, Ancho, Alto, Largo)
                    VALUES (?, ?, ?, ?)
                ", [
                    $idVenta,
                    $prod['Ancho'] ?? 0,
                    $prod['Alto']  ?? 0,
                    $prod['Largo'] ?? 0,
                ]);
            }

            Db::query(
                "INSERT INTO Pedido (Estado, Responsable, IdVenta) VALUES ('Pendiente', '', ?)",
                [$idVenta]
            );

            $db->commit();
            return (int)$idVenta;

        } catch (\Exception $e) {
            $db->rollBack();
            error_log('Error crearVentaPresencial: ' . $e->getMessage());
            return false;
        }
    }

    private function getIdEstadoPago(string $nombre): ?int {
        $row = Db::query(
            "SELECT Id FROM EstadodePago WHERE Nombre = ? LIMIT 1", [$nombre]
        )->fetch();
        return $row ? (int)$row['Id'] : null;
    }

    private function generarNumeroComprobante(): int {
        $row = Db::query(
            "SELECT COALESCE(MAX(NumeroComprobante), 0) + 1 AS nro FROM Comprobante"
        )->fetch();
        return (int)($row['nro'] ?? 1);
    }

    private function generarNumeroVenta(): int {
        $row = Db::query(
            "SELECT COALESCE(MAX(NumerodeVenta), 0) + 1 AS nro FROM Venta"
        )->fetch();
        return (int)($row['nro'] ?? 1);
    }
}