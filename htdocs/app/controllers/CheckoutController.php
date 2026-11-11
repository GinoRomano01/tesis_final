<?php

class CheckoutController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->title = 'Checkout — San Plácido';
    }

    private function verificarLogin(): void {
        if (!isset($_SESSION['cliente_id']) && !isset($_SESSION['usuario_id'])) {
            Toast::new('Iniciá sesión para continuar con el pago', 'warning');
            Redirect::to('login');
            exit;
        }
    }

    private function getClienteId(): int {
        return (int)($_SESSION['cliente_id'] ?? $_SESSION['usuario_id'] ?? 0);
    }

    private function getUsuarioId(): int {
        return (int)($_SESSION['usuario_id'] ?? 0);
    }

    public function entrega(): void {
        $this->verificarLogin();

        $clienteId    = $this->getClienteId();
        $carritoModel = new CarritoModel();
        $items        = $carritoModel->getItemsCarrito($clienteId);

        if (empty($items)) {
            Toast::new('Tu carrito está vacío.', 'warning');
            Redirect::to('carrito');
            exit;
        }

        EstadisticasModel::registrarEvento('checkout_paso', 'checkout', null, null, ['paso' => 'entrega']);

        $subtotal = array_sum(
            array_map(fn($i) => $i['PrecioVenta'] * $i['Cantidad'], $items)
        );

        $entregaModel   = new EntregaModel();
        $domicilio      = $entregaModel->getDomicilioCliente($clienteId);
        $localidades    = $entregaModel->getLocalidades();
        $tiposDomicilio = $entregaModel->getTiposDomicilio();
        $idLocalidad  = (int)($domicilio['IdLocalidad'] ?? 0);
        $codigoPostal = trim($domicilio['CodigoPostal'] ?? '');
        $cpNumero     = is_numeric($codigoPostal) ? (int)$codigoPostal : -1;
        $puedeEnvio = (
            $idLocalidad === 97
            || ($cpNumero >= 5000 && $cpNumero <= 5009)
        );

        $this->data['items']          = $items;
        $this->data['subtotal']       = $subtotal;
        $this->data['domicilio']      = $domicilio;
        $this->data['localidades']    = $localidades;
        $this->data['tiposDomicilio'] = $tiposDomicilio;
        $this->data['puedeEnvio']     = $puedeEnvio;

        $this->render('View_pago_entrega');
    }

    public function guardarEntrega(): void {
        $this->verificarLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('checkout/entrega');
            exit;
        }

        $tipo         = (int)$this->post('tipo_entrega');
        $usarNueva    = $this->post('usar_nueva_dir') === '1';
        $clienteId    = $this->getClienteId();
        $entregaModel = new EntregaModel();
        $domicilio    = $entregaModel->getDomicilioCliente($clienteId);
        $carritoModel = new CarritoModel();
        $items        = $carritoModel->getItemsCarrito($clienteId);
        $subtotal     = array_sum(
            array_map(fn($i) => $i['PrecioVenta'] * $i['Cantidad'], $items)
        );

        $costo       = ($tipo === 2) ? 20000 : 0;
        $totalFinal  = $subtotal + $costo;
        $direccion   = '';
        $idDomicilio = null;

        if ($tipo === 2) {
            if ($usarNueva) {
                $idDomicilio = $entregaModel->crearDomicilio([
                    'calle'          => $this->post('nd_calle'),
                    'numero'         => $this->post('nd_numero'),
                    'barrio'         => $this->post('nd_barrio'),
                    'country'        => $this->post('nd_country'),
                    'departamento'   => $this->post('nd_departamento'),
                    'piso'           => $this->post('nd_piso'),
                    'numero_piso'    => $this->post('nd_numero_piso'),
                    'tipo_domicilio' => $this->post('nd_tipo_domicilio'),
                ]);
                $direccion = $this->post('nd_calle') . ' ' . $this->post('nd_numero');
            } else {
                $idDomicilio = $domicilio['IdDomicilio'] ?? null;
                $direccion   = ($domicilio['Calle'] ?? '') . ' ' . ($domicilio['Numero'] ?? '');
            }
        }

        $_SESSION['checkout_entrega'] = [
            'tipo'        => $tipo,
            'costo'       => $costo,
            'total'       => $totalFinal,
            'subtotal'    => $subtotal,
            'direccion'   => $direccion,
            'idDomicilio' => $idDomicilio,
            'idUsuario'   => $this->getUsuarioId(),
        ];

        Redirect::to('checkout/metodo');
    }

    public function metodo(): void {
        $this->verificarLogin();

        if (empty($_SESSION['checkout_entrega'])) {
            Redirect::to('checkout/entrega');
            exit;
        }

        $entrega  = $_SESSION['checkout_entrega'];
        $subtotal = $entrega['subtotal'];
        $total    = $entrega['total'];

        $this->data['subtotal'] = $subtotal;
        $this->data['total']    = $total;
        $this->data['entrega']  = $entrega;

        $this->render('View_pago_metodo');
    }

    public function index(): void {
        $this->verificarLogin();

        if (empty($_SESSION['checkout_entrega'])) {
            Redirect::to('checkout/entrega');
            exit;
        }

        $clienteId    = $this->getClienteId();
        $carritoModel = new CarritoModel();
        $items        = $carritoModel->getItemsCarrito($clienteId);

        if (empty($items)) {
            Toast::new('Tu carrito está vacío.', 'warning');
            Redirect::to('carrito');
            exit;
        }

        $entrega  = $_SESSION['checkout_entrega'];
        $subtotal = $entrega['subtotal'];
        $total    = $entrega['total'];

        $this->data['items']      = $items;
        $this->data['subtotal']   = $subtotal;
        $this->data['total']      = $total;
        $this->data['entrega']    = $entrega;
        $this->data['public_key'] = MP_PUBLIC_KEY;
        $this->data['email']      = $_SESSION['correo'] ?? $_SESSION['email'] ?? '';

        $this->render('View_pago_checkout');
    }

    public function efectivo(): void {
        $this->verificarLogin();

        if (empty($_SESSION['checkout_entrega'])) {
            Redirect::to('checkout/entrega');
            exit;
        }

        $entrega = $_SESSION['checkout_entrega'];
        $total   = $entrega['total'];

        $preferenceData = [
            'items' => [[
                'title'       => 'Pedido San Plácido',
                'quantity'    => 1,
                'unit_price'  => (float)$total,
                'currency_id' => 'ARS',
            ]],
            'payment_methods' => [
                'excluded_payment_types' => [
                    ['id' => 'credit_card'],
                    ['id' => 'debit_card'],
                ],
            ],
            'back_urls' => [
                'success' => URL . 'checkout/aprobado/efectivo',
                'failure' => URL . 'checkout/rechazado',
                'pending' => URL . 'checkout/pendiente/efectivo',
            ],
            'auto_return' => 'approved',
        ];

        $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . MP_ACCESS_TOKEN,
            'Content-Type: application/json',
        ]);
        $response   = curl_exec($ch);
        curl_close($ch);

        $preference = json_decode($response, true);
        $initPoint  = $preference['sandbox_init_point'] ?? $preference['init_point'] ?? null;

        if (!$initPoint) {
            Toast::new('Error al generar el cupón de pago.', 'danger');
            Redirect::to('checkout/metodo');
            exit;
        }

        Redirect::to($initPoint);
    }

    public function mercadopago(): void {
        $this->verificarLogin();

        if (empty($_SESSION['checkout_entrega'])) {
            Redirect::to('checkout/entrega');
            exit;
        }

        $entrega = $_SESSION['checkout_entrega'];
        $total   = $entrega['total'];

        $preferenceData = [
            'items' => [[
                'title'       => 'Pedido San Plácido',
                'quantity'    => 1,
                'unit_price'  => (float)$total,
                'currency_id' => 'ARS',
            ]],
            'back_urls' => [
                'success' => URL . 'checkout/aprobado/mp',
                'failure' => URL . 'checkout/rechazado',
                'pending' => URL . 'checkout/pendiente/mp',
            ],
            'auto_return' => 'approved',
        ];

        $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . MP_ACCESS_TOKEN,
            'Content-Type: application/json',
        ]);
        $response   = curl_exec($ch);
        curl_close($ch);

        $preference = json_decode($response, true);
        $initPoint  = $preference['sandbox_init_point'] ?? $preference['init_point'] ?? null;

        if (!$initPoint) {
            Toast::new('Error al conectar con MercadoPago.', 'danger');
            Redirect::to('checkout/metodo');
            exit;
        }

        Redirect::to($initPoint);
    }

    public function procesar(): void {
        $this->verificarLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Toast::new('Tu sesión de pago venció o se interrumpió. Probá pagar de nuevo.', 'warning');
            Redirect::to('checkout/metodo');
            exit;
        }
        if (empty($_SESSION['checkout_entrega'])) { Redirect::to('checkout/entrega'); exit; }

        $entrega       = $_SESSION['checkout_entrega'];
        $clienteId     = $this->getClienteId();
        $token         = $this->post('token');
        $paymentMethod = $this->post('payment_method_id');
        $email         = $this->post('email');
        $cuotas        = (int)($this->post('installments') ?? 1);
        $docType       = $this->post('identificationType');
        $docNumber     = $this->post('identificationNumber');
        $metodo        = $this->post('metodo_pago')   ?? 'credito';
        $marca         = $this->post('marca_tarjeta') ?? '';

        $carritoModel = new CarritoModel();
        $items        = $carritoModel->getItemsCarrito($clienteId);

        if (empty($items)) {
            Toast::new('Tu carrito está vacío.', 'warning');
            Redirect::to('carrito');
            exit;
        }

        $subtotal    = $entrega['subtotal'];
        $costoEnvio  = $entrega['costo'];
        $baseConEnvio = $subtotal + $costoEnvio;

        $calculo     = PagoModel::calcularConInteres($baseConEnvio, $cuotas);
        $monto       = $calculo['monto_final'];
        $interes     = $calculo['interes'];

        $idTipoPago = match($metodo) {
            'debito'   => 2,
            'efectivo' => 3,
            'mp'       => 4,
            default    => 1,
        };

        if (!$token || !$monto) {
            Toast::new('Datos de pago incompletos.', 'danger');
            Redirect::to('checkout/index');
            exit;
        }

        if (empty($paymentMethod)) {
            Toast::new('No se pudo identificar el método de pago. Reingresá los datos de la tarjeta.', 'danger');
            Redirect::to('checkout/index');
            exit;
        }

        $deviceSessionId = trim($this->post('device_session_id') ?? '');
        $issuerId        = trim($this->post('issuer') ?? '');
        $idempotencyKey  = uniqid('', true);
        $esMetodoDebito = str_starts_with($paymentMethod, 'deb');
        if (($metodo === 'debito') !== $esMetodoDebito) {
            Toast::new('El tipo de tarjeta no coincide con el método de pago elegido. Volvé a intentar.', 'danger');
            Redirect::to('checkout/index');
            exit;
        }

        $paymentData = [
            'transaction_amount' => (float)$monto,
            'token'              => $token,
            'payment_method_id'  => $paymentMethod,
            'installments'       => $cuotas,
            'description'        => 'Pedido San Plácido',
            'payer'              => [
                'email'          => $email,
                'identification' => ['type' => $docType, 'number' => $docNumber],
            ],
        ];
        if ($issuerId !== '') {
            $paymentData['issuer_id'] = (int)$issuerId;
        }

        $headersMP = [
            'Authorization: Bearer ' . MP_ACCESS_TOKEN,
            'Content-Type: application/json',
            'X-Idempotency-Key: ' . $idempotencyKey,
        ];

        if ($deviceSessionId !== '') {
            $headersMP[] = 'X-meli-session-id: ' . $deviceSessionId;
        }

        $ch = curl_init('https://api.mercadopago.com/v1/payments');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($paymentData),
            CURLOPT_HTTPHEADER     => $headersMP,
        ]);
        $response      = curl_exec($ch);
        curl_close($ch);
        $paymentResult = json_decode($response, true);

        error_log('MP RESPONSE: ' . $response);
        error_log('MP Idempotency-Key usado: ' . $idempotencyKey . ' — device_session_id: ' . ($deviceSessionId ?: '(vacío)'));

        $status       = $paymentResult['status']        ?? '';
        $statusDetail = $paymentResult['status_detail'] ?? '';
        $paymentId    = (string)($paymentResult['id']   ?? '');

        if (!in_array($status, ['approved', 'pending'])) {
            $pagoModel = new PagoModel();
            $pagoModel->guardarIntentoPago($paymentResult, $cuotas, $marca);

            error_log("Pago rechazado — MP id: $paymentId — detail: $statusDetail — idempotency: $idempotencyKey");

            Toast::new($this->traducirRechazo($statusDetail), 'danger');
            Redirect::to('checkout/rechazado');
            exit;
        }

        $pagoModel = new PagoModel();
        $idVenta   = $pagoModel->crearVentaDesdeCarritoConTipo(
            $clienteId, $items, $monto, $cuotas, $idTipoPago, $marca, $interes
        );

        if (!$idVenta) {
            error_log("CRÍTICO: Pago aprobado (MP id=$paymentId) pero falló crearVenta para cliente $clienteId");
            Toast::new('Tu pago fue procesado pero hubo un error al registrar el pedido. Código: ' . $paymentId, 'danger');
            Redirect::to('checkout/rechazado');
            exit;
        }

        $pagoModel->guardarResultadoPagoRaw($idVenta, $paymentResult, $cuotas);

        $entregaModel  = new EntregaModel();
        $resultEntrega = $entregaModel->guardarEntrega($idVenta, [
            'tipo'      => $entrega['tipo'],
            'costo'     => $entrega['costo'],
            'direccion' => $entrega['direccion'],
            'idUsuario' => $entrega['idUsuario'],
        ]);

        $_SESSION['ultimo_codigo_entrega'] = $resultEntrega['codigo'];

        $carrito = $carritoModel->obtenerOCrearCarrito($clienteId);
        $carritoModel->concretarCarrito($carrito['Id']);
        $_SESSION['carrito_items'] = 0;
        unset($_SESSION['checkout_entrega']);

        switch ($status) {
            case 'approved':
                Toast::new('¡Pago aprobado! Tu pedido está confirmado.', 'success');
                Redirect::to('checkout/aprobado/' . $paymentId);
                break;
            case 'pending':
                Redirect::to('checkout/pendiente/' . $paymentId);
                break;
        }
    }

    public function aprobado($paymentId = null): void {
        $this->verificarLogin();
        $pagoModel    = new PagoModel();
        $entregaModel = new EntregaModel();
        $pago         = $pagoModel->getPorPaymentIdMP($paymentId);
        $entrega      = $pago ? $entregaModel->getPorVenta((int)($pago['IdVenta'] ?? 0)) : null;

        $codigoEntrega = $entrega['CodigoEntrega']
            ?? $_SESSION['ultimo_codigo_entrega']
            ?? '—';

        unset($_SESSION['ultimo_codigo_entrega']);

        $this->data['pago']          = $pago;
        $this->data['entrega']       = $entrega;
        $this->data['codigoEntrega'] = $codigoEntrega;
        $this->render('View_pago_aprobado');
    }

    public function pendiente($paymentId = null): void {
        $this->verificarLogin();
        $this->render('View_pago_pendiente');
    }

    public function rechazado(): void {
        $this->verificarLogin();
        $this->render('View_pago_rechazado');
    }

    public function webhook(): void {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['type']) || $input['type'] !== 'payment') {
            http_response_code(200); exit;
        }
        http_response_code(200);
        echo 'OK';
        exit;
    }

    private function traducirRechazo(string $codigo): string {
        $mensajes = [
            'cc_rejected_bad_filled_cvv'      => 'CVV incorrecto.',
            'cc_rejected_bad_filled_date'     => 'Fecha de vencimiento incorrecta.',
            'cc_rejected_bad_filled_other'    => 'Datos de la tarjeta incorrectos.',
            'cc_rejected_insufficient_amount' => 'Fondos insuficientes.',
            'cc_rejected_high_risk'           => 'Pago rechazado por seguridad.',
            'cc_rejected_call_for_authorize'  => 'Llamá a tu banco para autorizar el pago.',
            'pending_contingency'             => 'Estamos procesando tu pago.',
            'pending_review_manual'           => 'Tu pago está en revisión.',
        ];
        return $mensajes[$codigo] ?? 'Intentá con otro medio de pago.';
    }
}