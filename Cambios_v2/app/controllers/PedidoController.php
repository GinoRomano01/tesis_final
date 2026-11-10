<?php

class PedidoController extends Controller {

    private PedidoModel $model;

    public function __construct() {
        parent::__construct();
        $this->verificarAdmin();
        if (!puedeVerProduccion()) {
            Toast::new('Sin permisos para acceder a Pedidos.', 'danger');
            Redirect::to('admin/LobbyAdmin');
            exit;
        }

        parent::__construct();
        $this->verificarAdmin();
        $this->title = 'Pedidos — San Plácido Admin';
        $this->model = new PedidoModel();
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

        $this->model->sincronizar();

        $buscar  = trim($_GET['buscar'] ?? '');
        $estado  = trim($_GET['estado'] ?? '');

        $pedidos         = $this->model->listarPedidos($buscar, $estado);
        $conteos         = $this->model->contarPorEstado();
        $ventasElegibles = $this->model->getVentasElegibles();
        $estados         = PedidoModel::ESTADOS;

        $this->render('index', compact(
            'pedidos', 'conteos', 'ventasElegibles', 'estados', 'buscar', 'estado'
        ));
    }

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('pedido');
            exit;
        }

        $id          = (int)($_POST['Id']          ?? 0);
        $idVenta     = (int)($_POST['IdVenta']      ?? 0);
        $estado      = trim($_POST['Estado']        ?? 'Pendiente');
        $responsable = trim($_POST['Responsable']   ?? '');

        if (!in_array($estado, PedidoModel::ESTADOS)) {
            Toast::new('Estado no válido', 'warning');
            Redirect::to('pedido');
            exit;
        }

        if ($id > 0) {
            $this->model->editarPedido($id, $estado, $responsable);
            Toast::new('Pedido actualizado correctamente', 'success');
        } else {

            if ($idVenta <= 0) {
                Toast::new('Seleccioná una venta', 'warning');
                Redirect::to('pedido');
                exit;
            }
            $nuevo = $this->model->crearPedido($idVenta, $responsable, $estado);
            if (!$nuevo) {
                Toast::new('Esa venta ya tiene un pedido asignado', 'danger');
                Redirect::to('pedido');
                exit;
            }
            Toast::new('Pedido #' . $nuevo . ' creado correctamente', 'success');
        }

        Redirect::to('pedido');
        exit;
    }

    public function baja($id = null): void {


        if (!puedeEliminar()) {
            Toast::new('No tenés permisos para dar de baja pedidos.', 'danger');
            Redirect::to('pedido');
            exit;
        }

        $id = (int)$id;
        if ($id <= 0) { Redirect::to('pedido'); exit; }
        $this->model->darDeBaja($id);
        Toast::new('Pedido dado de baja', 'success');
        Redirect::to('pedido');
        exit;
    }
}