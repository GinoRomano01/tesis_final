<?php

class ClienteController extends Controller {

    protected $title = 'Mi Cuenta — San Plácido';

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
    }

    private function requireAuth() {
        if (empty($_SESSION['cliente_id'])) {
            Redirect::to('login');
        }
    }

    public function perfil() {
        $clienteModel = new ClienteModel();
        $cliente      = $clienteModel->obtenerPorId($_SESSION['cliente_id']);

        if (!$cliente) {
            Toast::new('No se encontró tu perfil.', 'danger');
            Redirect::to('login/logout');
        }

        $this->render('perfil', [
            'title'   => 'Mi Perfil — San Plácido',
            'cliente' => $cliente,
        ]);
    }

    public function editar() {
        $clienteModel = new ClienteModel();
        $cliente      = $clienteModel->obtenerPorId($_SESSION['cliente_id']);

        if (!$cliente) {
            Toast::new('No se encontró tu perfil.', 'danger');
            Redirect::to('cliente/perfil');
        }

        $localidades    = $clienteModel->obtenerLocalidades();
        $tiposDomicilio = $clienteModel->obtenerTiposDomicilio();

        $this->render('editar', [
            'title'          => 'Editar Perfil — San Plácido',
            'cliente'        => $cliente,
            'localidades'    => $localidades,
            'tiposDomicilio' => $tiposDomicilio,
        ]);
    }

    public function guardar() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $clienteId = $_SESSION['cliente_id'] ?? null;
        if (!$clienteId) {
            echo json_encode(['success' => false, 'error' => 'Sesión expirada']);
            exit;
        }

        $nombre    = mb_strtoupper(trim($_POST['nombre']   ?? ''), 'UTF-8');
        $apellido  = mb_strtoupper(trim($_POST['apellido'] ?? ''), 'UTF-8');
        $telefono  = trim($_POST['telefono']  ?? '');
        $localidad = (int)($_POST['localidad']      ?? 0);
        $tipoDom   = (int)($_POST['tipo_domicilio'] ?? 0);

        if (empty($nombre) || empty($apellido) || $localidad === 0) {
            echo json_encode(['success' => false, 'error' => 'Nombre, apellido y localidad son obligatorios.']);
            exit;
        }

        $clienteModel = new ClienteModel();

        try {

            $clienteModel->actualizarDatos($clienteId, [
                'Nombre'          => $nombre,
                'Apellido'        => $apellido,
                'Telefono'        => $telefono ?: null,
                'IdLocalidad'     => $localidad,
                'IdTipodomicilio' => $tipoDom ?: null,
            ]);

            if ($tipoDom > 0) {
                $calle        = trim($_POST['nd_calle']         ?? '');
                $numero       = (int)($_POST['nd_numero']       ?? 0);
                $piso         = isset($_POST['nd_piso'])        && $_POST['nd_piso']        !== '' ? (int)$_POST['nd_piso']           : null;
                $numeroPiso   = isset($_POST['nd_numero_piso']) && $_POST['nd_numero_piso'] !== '' ? trim($_POST['nd_numero_piso'])    : null;
                $barrio       = isset($_POST['nd_barrio'])      && $_POST['nd_barrio']      !== '' ? trim($_POST['nd_barrio'])         : null;
                $country      = isset($_POST['nd_country'])     && $_POST['nd_country']     !== '' ? trim($_POST['nd_country'])        : null;
                $depto        = isset($_POST['nd_departamento'])&& $_POST['nd_departamento']!== '' ? trim($_POST['nd_departamento'])   : null;
                $codigoPostal = isset($_POST['nd_codigo_postal'])&& $_POST['nd_codigo_postal']!== '' ? trim($_POST['nd_codigo_postal']): null;

                if (!empty($calle) && $numero > 0) {
                    $clienteActual = $clienteModel->obtenerPorId($clienteId);
                    $idDomicilio   = $clienteActual['IdDomicilio'] ?? null;

                    $datosDom = [
                        'Calle'           => $calle,
                        'Numero'          => $numero,
                        'Piso'            => $piso,
                        'NumeroPiso'      => $numeroPiso,
                        'Barrio'          => $barrio,
                        'Country'         => $country,
                        'Departamento'    => $depto,
                        'CodigoPostal'    => $codigoPostal,
                        'IdTipoDomicilio' => $tipoDom,
                    ];

                    if ($idDomicilio) {
                        $clienteModel->actualizarDomicilio($idDomicilio, $datosDom);
                    } else {
                        $nuevoIdDom = $clienteModel->crearDomicilio($datosDom);
                        if ($nuevoIdDom) {
                            $clienteModel->actualizarDatos($clienteId, ['IdDomicilio' => $nuevoIdDom]);
                        }
                    }
                }
            }

            $_SESSION['nombre_completo'] = $nombre . ' ' . $apellido;
            echo json_encode(['success' => true, 'mensaje' => 'Perfil actualizado correctamente.']);
        } catch (Exception $e) {
            error_log('ClienteController::guardar() - ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Error interno al guardar los datos.']);
        }

        exit;
    }
    public function pedidos() {
        $clienteModel = new ClienteModel();
        $cliente      = $clienteModel->obtenerPorId($_SESSION['cliente_id']);
        $pedidos      = $clienteModel->obtenerPedidosCliente($_SESSION['cliente_id']);

        $this->render('pedidos', [
            'title'   => 'Mis Pedidos — San Plácido',
            'cliente' => $cliente,
            'pedidos' => $pedidos ?? [],
        ]);
    }
}