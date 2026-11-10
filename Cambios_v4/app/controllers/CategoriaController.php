<?php

class CategoriaController extends Controller {
    
    private $model;
    
    public function __construct() {
        parent::__construct();
        $this->model = new CategoriaModel();
        $this->title = 'Categorías - San Plácido';
    }

    private function verificarAdmin(): void {
        if (!isset($_SESSION['usuario_id'])) {
            Toast::new('Debés iniciar sesión', 'warning');
            Redirect::to('login'); exit;
        }
        if (($_SESSION['tipo_usuario'] ?? 2) == 2) {
            Toast::new('Sin permisos', 'danger');
            Redirect::to(''); exit;
        }
    }
    
    public function mostrarCategorias() {
        try {
            return $this->model->obtenerTodas();
        } catch (Exception $e) {
            error_log('Error al obtener categorías: ' . $e->getMessage());
            return [];
        }
    }
    
    public function index() {
        $this->verificarAdmin();
        checkPermiso('puedeVerProductos');

        $buscar     = trim($_GET['buscar'] ?? '');
        $categorias = $this->model->listarConConteo($buscar);
        
        $this->render('index', [
            'categorias' => $categorias,
            'buscar'     => $buscar,
        ]);
    }

    public function guardar(): void {
        $this->verificarAdmin();
        checkPermiso('puedeVerProductos');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('categoria'); exit;
        }

        $id     = (int)($_POST['Id'] ?? 0);
        $nombre = trim($_POST['Nombre'] ?? '');

        if ($nombre === '') {
            Toast::new('El nombre es obligatorio', 'warning');
            Redirect::to('categoria'); exit;
        }
        if (mb_strlen($nombre) > 30) {
            Toast::new('El nombre no puede superar los 30 caracteres', 'warning');
            Redirect::to('categoria'); exit;
        }
        if ($this->model->nombreExiste($nombre, $id)) {
            Toast::new('Ya existe una categoría con ese nombre', 'warning');
            Redirect::to('categoria'); exit;
        }

        if ($id > 0) {
            $this->model->editar($id, $nombre);
            Toast::new('Categoría actualizada correctamente', 'success');
        } else {
            $nuevo = $this->model->crear($nombre);
            Toast::new($nuevo ? 'Categoría creada correctamente' : 'Error al crear la categoría',
                       $nuevo ? 'success' : 'danger');
        }

        Redirect::to('categoria');
        exit;
    }

    public function baja($id = null): void {
        $this->verificarAdmin();
        checkPermiso('puedeEliminar');

        $id = (int)$id;
        if ($id <= 0) { Redirect::to('categoria'); exit; }

        $cantidad = $this->model->contarProductosActivos($id);
        if ($cantidad > 0) {
            Toast::new(
                "No se puede dar de baja: hay {$cantidad} producto(s) usando esta categoría. Reasignalos primero.",
                'danger'
            );
            Redirect::to('categoria'); exit;
        }

        $this->model->darDeBaja($id);
        Toast::new('Categoría dada de baja', 'success');
        Redirect::to('categoria');
        exit;
    }
}