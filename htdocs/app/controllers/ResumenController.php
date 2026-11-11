<?php

class ResumenController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->verificarAdmin();
        $this->title = 'Panel Admin — San Plácido';
    }

    private function verificarAdmin(): void {
        if (!isset($_SESSION['usuario_id'])) {
            Toast::new('Debés iniciar sesión', 'warning');
            Redirect::to('login');
            exit;
        }
        if (($_SESSION['tipo_usuario'] ?? 2) == 2) {
            Toast::new('Sin permisos de administrador', 'danger');
            Redirect::to('');
            exit;
        }
    }

    public function index(): void {
        $model = new ResumenModel();

        var_dump($model->totalClientes());
        var_dump($model->totalProductos());
        var_dump($model->ultimasVentas(5));
        die();
    }
}