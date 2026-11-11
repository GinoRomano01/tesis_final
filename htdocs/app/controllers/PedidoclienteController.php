<?php

class PedidoclienteController extends Controller {

    protected $title = 'Mis Pedidos — San Plácido';

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
    }

    private function requireAuth() {
        if (empty($_SESSION['cliente_id'])) {
            Redirect::to('login');
        }
    }

    public function index() {
        $model   = new PedidoclienteModel();
        $cliente = $model->obtenerClientePorId($_SESSION['cliente_id']);
        $pedidos = $model->obtenerPedidosPorCliente($_SESSION['cliente_id']);

        $this->render('index', [
            'title'   => 'Mis Pedidos — San Plácido',
            'cliente' => $cliente,
            'pedidos' => $pedidos,
        ]);
    }
 
    public function ubicacion($idEntrega = null) {
        header('Content-Type: application/json');

        $idEntrega = (int)$idEntrega;
        $idCliente = (int)($_SESSION['cliente_id'] ?? 0);

        if ($idEntrega <= 0 || $idCliente <= 0) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'No autorizado']);
            exit;
        }

        $model = new PedidoclienteModel();
        echo json_encode($model->obtenerUbicacionParaEntrega($idEntrega, $idCliente));
        exit;
    }
}