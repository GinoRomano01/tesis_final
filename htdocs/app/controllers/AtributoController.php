<?php

class AtributoController extends Controller {

    private AtributoModel $model;
    private const TABS_VALIDAS = ['categoria', 'tipoproducto', 'diseno', 'acabado', 'herraje', 'almacenamiento'];

    public function __construct() {
        parent::__construct();
        $this->model = new AtributoModel();
        $this->title = 'Atributos de producto — San Plácido Admin';
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

    private function tabValida(string $tab): string {
        return in_array($tab, self::TABS_VALIDAS, true) ? $tab : 'categoria';
    }

    public function index(): void {
        $this->verificarAdmin();
        checkPermiso('puedeVerProductos');

        $tab    = $this->tabValida($_GET['tab'] ?? 'categoria');
        $buscar = trim($_GET['buscar'] ?? '');

        $this->render('index', [
            'tab'              => $tab,
            'tabs'             => $this->model->getTabs(),
            'items'            => $this->model->listar($tab, $buscar),
            'buscar'           => $buscar,
            'tieneDescripcion' => $this->model->tieneDescripcion($tab),
            'tieneChecklist'   => $this->model->tieneChecklist($tab),
            'todosTipos'       => $this->model->obtenerTodosTiposProducto(),
        ]);
    }

    public function guardar(): void {
        $this->verificarAdmin();
        checkPermiso('puedeVerProductos');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { Redirect::to('atributo'); exit; }

        $tab        = $this->tabValida($_POST['Tab'] ?? '');
        $id         = (int)($_POST['Id'] ?? 0);
        $nombre     = trim($_POST['Nombre'] ?? '');
        $desc       = trim($_POST['Descripcion'] ?? '');
        $vinculados = $_POST['TiposVinculados'] ?? [];

        if ($nombre === '') {
            Toast::new('El nombre es obligatorio', 'warning');
            Redirect::to('atributo?tab=' . $tab); exit;
        }
        if (mb_strlen($nombre) > 30) {
            Toast::new('El nombre no puede superar los 30 caracteres', 'warning');
            Redirect::to('atributo?tab=' . $tab); exit;
        }
        if ($this->model->nombreExiste($tab, $nombre, $id)) {
            Toast::new('Ya existe un registro con ese nombre en esta sección', 'warning');
            Redirect::to('atributo?tab=' . $tab); exit;
        }

        if ($id > 0) {
            $this->model->editar($tab, $id, $nombre, $desc, $vinculados);
            Toast::new('Actualizado correctamente', 'success');
        } else {
            $nuevo = $this->model->crear($tab, $nombre, $desc, $vinculados);
            Toast::new($nuevo ? 'Creado correctamente' : 'Error al crear', $nuevo ? 'success' : 'danger');
        }

        Redirect::to('atributo?tab=' . $tab);
        exit;
    }

    public function baja($id = null): void {
        $this->verificarAdmin();
        checkPermiso('puedeEliminar');

        $tab = $this->tabValida($_GET['tab'] ?? '');
        $id  = (int)$id;
        if ($id <= 0) { Redirect::to('atributo?tab=' . $tab); exit; }

        $cantidad = $this->model->contarProductosActivos($tab, $id);
        if ($cantidad > 0) {
            Toast::new(
                "No se puede dar de baja: hay {$cantidad} producto(s) usando esto. Reasignalos primero.",
                'danger'
            );
            Redirect::to('atributo?tab=' . $tab); exit;
        }

        $this->model->darDeBaja($tab, $id);
        Toast::new('Dado de baja correctamente', 'success');
        Redirect::to('atributo?tab=' . $tab);
        exit;
    }
}
