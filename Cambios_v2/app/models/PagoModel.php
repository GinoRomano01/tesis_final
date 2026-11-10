<?php

class PagoModel extends Model {

    private static array $interesesCuotas = [
        1  => 0.00,  
        3  => 0.12,   
        6  => 0.25,   
        12 => 0.55,   
        18 => 0.85,
        24 => 1.20,
    ];

    public static function calcularConInteres(float $monto, int $cuotas): array {
        $porcentaje  = self::$interesesCuotas[$cuotas] ?? 0.00;
        $interes     = round($monto * $porcentaje, 2);
        $montoFinal  = round($monto + $interes, 2);

        return [
            'monto_final' => $montoFinal,
            'interes'     => $interes,
            'porcentaje'  => $porcentaje,
        ];
    }

    public function guardarIntentoPago(array $payment, int $cuotas, string $marcaTarjeta = ''): int {
        Db::query("
            INSERT INTO AutorizaciondePago
                (TokendePago, PaymentIdMP, Status, StatusDetail, PaymentMethod, Cuotas)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $payment['card']['id']        ?? '',
            (string)($payment['id']       ?? ''),
            $payment['status']            ?? '',
            $payment['status_detail']     ?? '',
            $payment['payment_method_id'] ?? ($marcaTarjeta ?: ''),
            $cuotas,
        ]);

        return (int)Db::getInstance()->getConnection()->lastInsertId();
    }

    public function getVentaConTotal($idVenta) {
        return Db::query("
            SELECT v.Id, v.NumerodeVenta, fc.MontoTotal, fc.Cuotas,
                   CONCAT(c.Nombre, ' ', c.Apellido) AS NombreCliente,
                   u.CorreoElectronico
            FROM Venta v
            JOIN FacturaCliente fc ON fc.Id       = v.IdFacturaCliente
            JOIN Carrito ca        ON ca.Id       = v.IdCarrito
            JOIN Clientes c        ON c.Id        = ca.IdCliente
            JOIN Usuario u         ON u.IdCliente = c.Id
            WHERE v.Id = ? LIMIT 1
        ", [$idVenta])->fetch();
    }

    public function getPorPaymentIdMP(?string $paymentIdMP): array|false {
        if (!$paymentIdMP) return false;
        return Db::query("
            SELECT ap.*,
                fc.MontoTotal, fc.NumeroFactura, fc.Cuotas,
                fc.MarcaTarjeta, fc.FechadeEmision, fc.Interes,
                tp.Nombre  AS TipoPago,
                v.NumerodeVenta, v.Id AS IdVenta,
                CONCAT(c.Nombre, ' ', c.Apellido) AS NombreCliente,
                c.DNI, u.CorreoElectronico
            FROM AutorizaciondePago ap
            JOIN FacturaCliente fc ON fc.IdAutorizaciondePago = ap.Id
            JOIN Venta v           ON v.IdFacturaCliente      = fc.Id
            JOIN Clientes c        ON c.Id                    = fc.IdClientes
            JOIN TipodePago tp     ON tp.Id                   = fc.IdTipodePago
            LEFT JOIN Usuario u    ON u.IdCliente             = c.Id
            WHERE ap.PaymentIdMP = ? LIMIT 1
        ", [$paymentIdMP])->fetch();
    }

    public function guardarResultadoPagoRaw(int $idVenta, array $payment, int $cuotas): void {
        Db::query("
            INSERT INTO AutorizaciondePago
                (TokendePago, PaymentIdMP, Status, StatusDetail, PaymentMethod, Cuotas)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $payment['card']['id']          ?? '',
            (string)($payment['id']         ?? ''),
            $payment['status']              ?? '',
            $payment['status_detail']       ?? '',
            $payment['payment_method_id']   ?? '',
            $cuotas,
        ]);

        $idAutorizacion = Db::getInstance()->getConnection()->lastInsertId();
        $idEstado       = $this->getIdEstado(
            ['approved' => 'Aprobado', 'pending' => 'Pendiente', 'rejected' => 'Rechazado'][$payment['status']] ?? 'Pendiente'
        );

        Db::query("
            UPDATE FacturaCliente fc
            JOIN Venta v ON v.IdFacturaCliente = fc.Id
            SET fc.IdAutorizaciondePago = ?,
                fc.IdEstadodePago       = ?,
                fc.Cuotas               = ?
            WHERE v.Id = ?
        ", [$idAutorizacion, $idEstado, $cuotas, $idVenta]);
    }

    public function crearVentaDesdeCarritoConTipo(
        int $clienteId, array $items, float $monto, int $cuotas,
        int $idTipoPago, string $marcaTarjeta = '', float $interes = 0.0
    ): int|false {
        $db = Db::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $emisor   = Db::query("
                SELECT e.Id FROM Emisor e
                JOIN Usuario u ON u.Id = e.IdUsuario
                WHERE u.IdCliente = ? LIMIT 1
            ", [$clienteId])->fetch();
            $idEmisor = $emisor['Id'] ?? null;

            $cliente = Db::query(
                "SELECT Id FROM Clientes WHERE Id = ? LIMIT 1", [$clienteId]
            )->fetch();
            if (!$cliente) { $db->rollBack(); return false; }

            $subtotal          = $monto - $interes; 
            $nroFactura        = $this->generarNumeroFactura();
            $idEstadoPendiente = $this->getIdEstado('Pendiente');

            Db::query("
                INSERT INTO FacturaCliente
                    (NumeroFactura, FechadeEmision, SubTotal, Impuestos,
                    MontoTotal, Interes, Cuotas, MarcaTarjeta, IdEmisor,
                    IdTipodePago, IdEstadodePago, IdDatosEmpresa, IdClientes)
                VALUES (?, NOW(), ?, 0, ?, ?, ?, ?, ?, ?, ?, 2, ?)
            ", [
                $nroFactura,
                $subtotal,
                $monto,         
                $interes,      
                $cuotas,
                $marcaTarjeta,
                $idEmisor,
                $idTipoPago,
                $idEstadoPendiente,
                $clienteId,
            ]);
            $idFactura = $db->lastInsertId();

            $carrito = Db::query(
                "SELECT Id FROM Carrito WHERE IdCliente = ? AND Estado = 0 LIMIT 1",
                [$clienteId]
            )->fetch();
            $idCarrito = $carrito['Id'] ?? null;

            $nroVenta  = $this->generarNumeroVenta();
            $cantTotal = array_sum(array_column($items, 'Cantidad'));

            Db::query("
                INSERT INTO Venta (NumerodeVenta, CantidadTotal, IdCarrito, IdFacturaCliente)
                VALUES (?, ?, ?, ?)
            ", [$nroVenta, $cantTotal, $idCarrito, $idFactura]);
            $idVenta = $db->lastInsertId();

            foreach ($items as $item) {
                Db::query("
                    INSERT INTO DetallesVenta (IdVenta, Ancho, Alto, Largo)
                    VALUES (?, ?, ?, ?)
                ", [
                    $idVenta,
                    $item['Ancho']  ?? 0,
                    $item['Alto']   ?? 0,
                    $item['Largo']  ?? 0,
                ]);
            }

            $db->commit();
            return (int)$idVenta;

        } catch (\Exception $e) {
            $db->rollBack();
            error_log('Error crearVenta: ' . $e->getMessage());
            return false;
        }
    }

    public function crearVentaPresencial(
        int $clienteId, array $items, float $subtotal, float $interes,
        int $cuotas, int $idTipoPago, string $marcaTarjeta = ''
    ): int|false {
        $db = Db::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $montoTotal = $subtotal + $interes;

            $emisor   = Db::query("
                SELECT e.Id FROM Emisor e
                JOIN Usuario u ON u.Id = e.IdUsuario
                WHERE u.IdCliente = ? LIMIT 1
            ", [$clienteId])->fetch();
            $idEmisor = $emisor['Id'] ?? null;

            $nroFactura  = $this->generarNumeroFactura();
            $idAprobado  = $this->getIdEstado('Aprobado');  

            Db::query("
                INSERT INTO FacturaCliente
                    (NumeroFactura, FechadeEmision, SubTotal, Impuestos,
                    MontoTotal, Interes, Cuotas, MarcaTarjeta,
                    IdEmisor, IdTipodePago, IdEstadodePago, IdDatosEmpresa, IdClientes)
                VALUES (?, NOW(), ?, 0, ?, ?, ?, ?, ?, ?, ?, 2, ?)
            ", [
                $nroFactura, $subtotal, $montoTotal, $interes,
                $cuotas, $marcaTarjeta, $idEmisor,
                $idTipoPago, $idAprobado, $clienteId,
            ]);
            $idFactura = $db->lastInsertId();

            $carrito = Db::query(
                "SELECT Id FROM Carrito WHERE IdCliente = ? AND Estado = 0 LIMIT 1",
                [$clienteId]
            )->fetch();

            if (!$carrito) {
                Db::query(
                    "INSERT INTO Carrito (Cantidad, IdCliente, Estado) VALUES (?, ?, 0)",
                    [array_sum(array_column($items, 'cantidad')), $clienteId]
                );
                $idCarrito = $db->lastInsertId();
            } else {
                $idCarrito = $carrito['Id'];
            }

            foreach ($items as $item) {
                Db::query(
                    "INSERT INTO ProductoCarrito (IdProducto, IdCarrito, Cantidad) VALUES (?, ?, ?)",
                    [$item['id_producto'], $idCarrito, $item['cantidad']]
                );
            }

            $nroVenta  = $this->generarNumeroVenta();
            $cantTotal = array_sum(array_column($items, 'cantidad'));

            Db::query("
                INSERT INTO Venta (NumerodeVenta, CantidadTotal, IdCarrito, IdFacturaCliente)
                VALUES (?, ?, ?, ?)
            ", [$nroVenta, $cantTotal, $idCarrito, $idFactura]);
            $idVenta = $db->lastInsertId();

            Db::query("UPDATE Carrito SET Estado = 1 WHERE Id = ?", [$idCarrito]);

            Db::query("
                INSERT INTO Pedido (Estado, Responsable, IdVenta)
                VALUES ('Pendiente', '', ?)
            ", [$idVenta]);

            Db::query("
                INSERT INTO AutorizaciondePago
                    (TokendePago, PaymentIdMP, Status, StatusDetail, PaymentMethod, Cuotas)
                VALUES ('', 'PRESENCIAL-' + ?, 'approved', 'accredited', ?, ?)
            ", [$idFactura, $marcaTarjeta ?: $idTipoPago, $cuotas]);
            $idAut = $db->lastInsertId();

            Db::query("
                UPDATE FacturaCliente SET IdAutorizaciondePago = ? WHERE Id = ?
            ", [$idAut, $idFactura]);

            $db->commit();
            return (int)$idVenta;

        } catch (\Exception $e) {
            $db->rollBack();
            error_log('Error crearVentaPresencial: ' . $e->getMessage());
            return false;
        }
    }

    private function getIdEstado(string $nombre): ?int {
        $row = Db::query(
            "SELECT Id FROM EstadodePago WHERE Nombre = ? LIMIT 1", [$nombre]
        )->fetch();
        return $row ? (int)$row['Id'] : null;
    }

    private function generarNumeroFactura(): int {
        $row = Db::query(
            "SELECT COALESCE(MAX(NumeroFactura), 0) + 1 AS nro FROM FacturaCliente"
        )->fetch();
        return (int)($row['nro'] ?? 1);
    }

    private function generarNumeroVenta(): int {
        $row = Db::query(
            "SELECT COALESCE(MAX(NumerodeVenta), 0) + 1 AS nro FROM Venta"
        )->fetch();
        return (int)($row['nro'] ?? 1);
    }

    public function actualizarEstadoPago($paymentIdMP, $status, $statusDetail): void {
        Db::query(
            "UPDATE AutorizaciondePago SET Status = ?, StatusDetail = ? WHERE PaymentIdMP = ?",
            [$status, $statusDetail, $paymentIdMP]
        );
        $idEstado = $this->getIdEstado(
            ['approved' => 'Aprobado', 'pending' => 'Pendiente', 'rejected' => 'Rechazado'][$status] ?? 'Pendiente'
        );
        if ($idEstado) {
            Db::query("
                UPDATE FacturaCliente fc
                JOIN AutorizaciondePago ap ON ap.Id = fc.IdAutorizaciondePago
                SET fc.IdEstadodePago = ?
                WHERE ap.PaymentIdMP = ?
            ", [$idEstado, $paymentIdMP]);
        }
    }


    public function webhook(): void {
        $xSignature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
        $xRequestId = $_SERVER['HTTP_X_REQUEST_ID'] ?? '';
        $dataId     = $_GET['data.id'] ?? '';

        $parts = [];
        foreach (explode(',', $xSignature) as $part) {
            [$k, $v] = array_pad(explode('=', trim($part), 2), 2, '');
            $parts[$k] = $v;
        }
        $ts  = $parts['ts']  ?? '';
        $v1  = $parts['v1']  ?? '';

        $manifest = "id:{$dataId};request-id:{$xRequestId};ts:{$ts};";
        $hash     = hash_hmac('sha256', $manifest, MP_WEBHOOK_SECRET);

        if (!hash_equals($hash, $v1)) {
            http_response_code(401);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['type']) || $input['type'] !== 'payment') {
            http_response_code(200); exit;
        }

        $paymentId = $input['data']['id'] ?? null;
        if ($paymentId) {
            $ch = curl_init("https://api.mercadopago.com/v1/payments/{$paymentId}");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . MP_ACCESS_TOKEN],
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            $pago = json_decode($response, true);

            if ($pago) {
                $pagoModel = new PagoModel();
                $pagoModel->actualizarEstadoPago($paymentId, $pago['status'], $pago['status_detail'] ?? '');
            }
        }

        http_response_code(200);
        echo 'OK';
        exit;
    }
}