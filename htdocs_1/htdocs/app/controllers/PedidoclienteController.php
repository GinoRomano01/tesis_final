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
}