<?php

class VentaController extends Controller {

    private VentaModel $model;

    public function __construct() {
        parent::__construct();
        $this->verificarAdmin();

        if (!puedeVerVentas()) {
            Toast::new('Sin permisos para acceder a Ventas.', 'danger');
            Redirect::to('admin/LobbyAdmin');
            exit;
        }
        $this->title = 'Ventas — San Plácido Admin';
        $this->model = new VentaModel();
    }

    private function verificarAdmin(): void {
        if (!isset($_SESSION['usuario_id'])) {
            Toast::new('Debés iniciar sesión', 'warning');
            Redirect::to('login');
            exit;
        }
        if (($_SESSION['tipo_usuario'] ?? 2) == 2) {
            Toast::new('Sin permisos', 'danger');
            Redirect::to('');
            exit;
        }
    }

    public function index(): void {
        (new PagoModel())->sincronizarPendientesVencidos();

        $buscar   = trim($_GET['buscar']  ?? '');
        $idEstado = (int)($_GET['estado'] ?? 0);

        $ventas   = $this->model->listarVentas($buscar, $idEstado);
        $estados  = $this->model->getEstadosPago();
        $conteos  = $this->model->contarPorEstadoPago();

        $clientes       = $this->model->getClientes();
        $productos      = $this->model->getProductos();
        $tiposPago      = $this->model->getTiposPago();
        $tiposDni       = Db::query("SELECT Id, Nombre FROM TipodeDni    ORDER BY Id")->fetchAll();
        $localidades    = Db::query("SELECT Id, Nombre FROM Localidad     ORDER BY Nombre")->fetchAll();
        $tiposDomicilio = Db::query("SELECT Id, Nombre FROM TipoDomicilio ORDER BY Id")->fetchAll();

        $this->render('index', compact(
            'ventas', 'estados', 'conteos',
            'buscar', 'idEstado',
            'clientes', 'productos', 'tiposPago',
            'tiposDni', 'localidades', 'tiposDomicilio'
        ));
    }

    public function presencial(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('venta');
            exit;
        }

        $idCliente    = (int)($_POST['IdCliente']       ?? 0);
        $idTipoPago   = (int)($_POST['IdTipoPago']      ?? 1);
        $cuotas       = (int)($_POST['cuotas']          ?? 1);
        $marcaTarjeta = trim($_POST['marca_tarjeta']    ?? '');
        $itemsRaw     = $_POST['items']                 ?? '[]';
        $tipoEntrega  = (int)($_POST['tipo_entrega']    ?? 1);
        $usarNuevaDir = ($_POST['usar_nueva_dir']       ?? '0') === '1';
        $costoEnvio   = (float)($_POST['costo_envio']   ?? 0);

        $ndCalle      = trim($_POST['nd_calle']         ?? '');
        $ndNumero     = (int)($_POST['nd_numero']       ?? 0);
        $ndBarrio     = trim($_POST['nd_barrio']        ?? '');
        $ndCountry    = trim($_POST['nd_country']       ?? '');
        $ndPiso       = (int)($_POST['nd_piso']         ?? 0);
        $ndNumeroPiso = (int)($_POST['nd_numero_piso']  ?? 0);

        if ($idCliente <= 0) {
            Toast::new('Seleccioná un cliente', 'warning');
            Redirect::to('venta');
            exit;
        }

        if ($tipoEntrega === 2) {
            $domicilioActual = Db::query("
                SELECT c.IdLocalidad, d.CodigoPostal
                FROM Clientes c
                LEFT JOIN Domicilio d ON d.Id = c.IdDomicilio
                WHERE c.Id = ? LIMIT 1
            ", [$idCliente])->fetch();

            $idLocalidad  = (int)($domicilioActual['IdLocalidad'] ?? 0);
            $codigoPostal = trim($domicilioActual['CodigoPostal'] ?? '');
            $cpNumero     = is_numeric($codigoPostal) ? (int)$codigoPostal : -1;

            $puedeEnvio = ($idLocalidad === 97) || ($cpNumero >= 5000 && $cpNumero <= 5009);

            if (!$puedeEnvio) {
                Toast::new(
                    'Este cliente está fuera de la zona de envío a domicilio (Córdoba Capital). Elegí "Retiro en sucursal".',
                    'danger'
                );
                Redirect::to('venta');
                exit;
            }
        }

        if (is_string($itemsRaw)) {
            $itemsRaw = json_decode($itemsRaw, true) ?? [];
        }

        $items = [];
        foreach ($itemsRaw as $it) {
            $idProd   = (int)($it['id_producto'] ?? 0);
            $cantidad = (int)($it['cantidad']    ?? 0);
            $precio   = (float)($it['precio']   ?? 0);
            if ($idProd > 0 && $cantidad > 0 && $precio > 0) {
                $items[] = [
                    'id_producto' => $idProd,
                    'cantidad'    => $cantidad,
                    'precio'      => $precio,
                ];
            }
        }

        if (empty($items)) {
            Toast::new('Agregá al menos un producto', 'warning');
            Redirect::to('venta');
            exit;
        }

        $subtotal = array_sum(array_map(
            fn($i) => $i['precio'] * $i['cantidad'], $items
        ));
        $base    = $subtotal + $costoEnvio;
        $calculo = VentaModel::calcularConInteres($base, $cuotas);

        $idVenta = $this->model->crearVentaPresencial(
            $idCliente,
            $items,
            $idTipoPago,
            $cuotas,
            $marcaTarjeta,
            $calculo['interes']
        );

        if (!$idVenta) {
            Toast::new('Error al registrar la venta. Revisá los datos.', 'danger');
            Redirect::to('venta');
            exit;
        }

        $montoTotal = $base + $calculo['interes'];
        $this->_notificarVentaNueva($idVenta, $montoTotal, count($items));

        try {
            (new PagoModel())->notificarCompraConfirmada($idVenta);
        } catch (\Throwable $e) {
            error_log('VentaController::presencial - notificarCompraConfirmada - ' . $e->getMessage());
        }

        $direccion = '';
        if ($tipoEntrega === 2) {
            if ($usarNuevaDir && $ndCalle) {
                $direccion = $ndCalle . ' ' . $ndNumero;
                if ($ndBarrio)  $direccion .= ' — ' . $ndBarrio;
                if ($ndCountry) $direccion .= ' (' . $ndCountry . ')';
            } else {
                $dom = Db::query("
                    SELECT d.Calle, d.Numero, d.Barrio
                    FROM Clientes c
                    LEFT JOIN Domicilio d ON d.Id = c.IdDomicilio
                    WHERE c.Id = ? LIMIT 1
                ", [$idCliente])->fetch();
                if ($dom) {
                    $direccion = ($dom['Calle'] ?? '') . ' ' . ($dom['Numero'] ?? '');
                    if ($dom['Barrio']) $direccion .= ' — ' . $dom['Barrio'];
                }
            }
        }

        $entregaModel = new EntregaModel();
        $entregaModel->guardarEntrega($idVenta, [
            'tipo'      => $tipoEntrega,
            'costo'     => $costoEnvio,
            'direccion' => $direccion,
            'idUsuario' => (int)($_SESSION['usuario_id'] ?? 0),
        ]);

        Toast::new('Venta presencial #' . $idVenta . ' registrada correctamente', 'success');
        Redirect::to('venta');
        exit;
    }

    private function _notificarVentaNueva(int $idVenta, float $montoTotal, int $cantidadItems): void {
        try {
            NotificacionModel::notificarARoles([1, 4], [
                'Tipo'       => 'venta_nueva',
                'Titulo'     => 'Venta presencial registrada',
                'Contenido'  => 'Venta #' . $idVenta . ' — $' . number_format($montoTotal, 0, ',', '.')
                                . ' (' . $cantidadItems . ' producto(s))',
                'UrlDestino' => 'venta',
                'Icono'      => 'fa-cart-shopping',
            ]);
        } catch (\Exception $e) {
            error_log('VentaController::_notificarVentaNueva - venta #' . $idVenta . ' - ' . $e->getMessage());
        }
    }

    public function buscarCliente(): void {
        header('Content-Type: application/json');

        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            echo json_encode([]);
            exit;
        }

        $like    = "%$q%";
        $results = Db::query("
            SELECT Id, Nombre, Apellido, DNI, Telefono
            FROM Clientes
            WHERE FechaBorrado IS NULL
              AND (
                DNI                             LIKE ?
                OR Nombre                       LIKE ?
                OR Apellido                     LIKE ?
                OR CONCAT(Nombre, ' ', Apellido) LIKE ?
                OR CONCAT(Apellido, ' ', Nombre) LIKE ?
              )
            ORDER BY Apellido, Nombre
            LIMIT 10
        ", [$like, $like, $like, $like, $like])->fetchAll();

        echo json_encode($results);
        exit;
    }

    public function registrarCliente(): void {
        header('Content-Type: application/json');

        $body         = json_decode(file_get_contents('php://input'), true) ?? [];
        $nombre       = trim($body['nombre']          ?? '');
        $apellido     = trim($body['apellido']        ?? '');
        $dni          = trim($body['dni']             ?? '');
        $idTipoDni    = (int)($body['idTipoDni']      ?? 0);
        $telefono     = trim($body['telefono']        ?? '');
        $idLocalidad  = (int)($body['idLocalidad']    ?? 0);
        $idTipoDom    = (int)($body['idTipoDomicilio'] ?? 0);

        $calle        = trim($body['calle']           ?? '');
        $numero       = (int)($body['numero']         ?? 0);
        $barrio       = trim($body['barrio']          ?? '');
        $country      = trim($body['country']         ?? '');
        $departamento = (int)($body['departamento']   ?? 0);
        $piso         = (int)($body['piso']           ?? 0);
        $numeroPiso   = (int)($body['numeroPiso']     ?? 0);

        if (!$nombre || !$apellido || !$dni || !$idTipoDni || !$idLocalidad || !$idTipoDom) {
            echo json_encode(['error' => 'Completá todos los campos obligatorios.']);
            exit;
        }

        $existe = Db::query(
            "SELECT Id FROM Clientes WHERE DNI = ? AND FechaBorrado IS NULL LIMIT 1",
            [$dni]
        )->fetch();

        if ($existe) {
            echo json_encode(['error' => 'Ya existe un cliente con ese DNI.']);
            exit;
        }

        $db = Db::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            Db::query("
                INSERT INTO Domicilio
                    (Calle, Numero, Country, Departamento, Barrio, IdTipoDomicilio, Piso, NumeroPiso)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $calle        ?: null,
                $numero       ?: null,
                $country      ?: null,
                $departamento ?: null,
                $barrio       ?: null,
                $idTipoDom,
                $piso         ?: null,
                $numeroPiso   ?: null,
            ]);
            $idDomicilio = $db->lastInsertId();

            Db::query("
                INSERT INTO Clientes
                    (DNI, Nombre, Apellido, Telefono, IdLocalidad, IdTipodeDni, IdDomicilio, IdTipodomicilio)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $dni,
                $nombre,
                $apellido,
                $telefono    ?: null,
                $idLocalidad,
                $idTipoDni,
                $idDomicilio,
                $idTipoDom,
            ]);
            $idCliente = $db->lastInsertId();

            $db->commit();

            echo json_encode([
                'id'       => (int)$idCliente,
                'Nombre'   => $nombre,
                'Apellido' => $apellido,
                'DNI'      => $dni,
                'Telefono' => $telefono,
            ]);

        } catch (\Exception $e) {
            $db->rollBack();
            error_log('Error registrarCliente presencial: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al guardar. Intentá de nuevo.']);
        }

        exit;
    }

    public function getDomicilioCliente(): void {
        header('Content-Type: application/json');

        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { echo json_encode(null); exit; }

        $row = Db::query("
            SELECT d.Calle, d.Numero, d.Barrio, d.Country, d.Piso, d.NumeroPiso,
                td.Nombre AS TipoDomicilio
            FROM Clientes c
            LEFT JOIN Domicilio    d  ON d.Id  = c.IdDomicilio
            LEFT JOIN TipoDomicilio td ON td.Id = d.IdTipoDomicilio
            WHERE c.Id = ? AND c.FechaBorrado IS NULL
            LIMIT 1
        ", [$id])->fetch();

        echo json_encode($row ?: null);
        exit;
    }
}