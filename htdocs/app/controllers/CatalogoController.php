<?php

class CatalogoController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->title = 'Catálogo — San Plácido';
    }

    private function verificarLogin() {
        if (!isset($_SESSION['cliente_id']) && !isset($_SESSION['usuario_id'])) {
            Toast::new('Iniciá sesión para acceder al catálogo', 'warning');
            Redirect::to('login');
            exit;
        }
    }

    public function index() {
        $this->verificarLogin();

        $model = new CatalogoModel();

        $pagina    = max(1, (int)($_GET['pagina']   ?? 1));
        $buscar    = trim($_GET['buscar']            ?? '');
        $orden     = $_GET['orden']                  ?? 'nuevo';
        $categoria = (int)($_GET['categoria']        ?? 0);
        $madera    = (int)($_GET['madera']           ?? 0);
        $diseño    = (int)($_GET['diseño']           ?? 0);
        $acabado   = (int)($_GET['acabado']          ?? 0);
        $herraje   = (int)($_GET['herraje']          ?? 0);
        $almacen   = (int)($_GET['almacen']          ?? 0);
        $tipo      = (int)($_GET['tipo']             ?? 0);

        $filtros = compact(
            'buscar','orden','categoria','madera',
            'diseño','acabado','herraje','almacen','tipo'
        );

        $porPagina  = 20;
        $total      = $model->contarProductos($filtros);
        $totalPags  = max(1, (int)ceil($total / $porPagina));
        $pagina     = min($pagina, $totalPags);
        $offset     = ($pagina - 1) * $porPagina;

        $productos  = $model->obtenerProductos($filtros, $porPagina, $offset);

        if (!empty($buscar) && $pagina === 1) {
            try {
                Db::query(
                    "INSERT INTO Busquedas
                        (TerminoBuscado, CantidadResultados, IdUsuario, IdCliente, SesionId)
                     VALUES (?, ?, ?, ?, ?)",
                    [
                        substr($buscar, 0, 300),
                        $total,
                        $_SESSION['usuario_id'] ?? null,
                        $_SESSION['cliente_id']  ?? null,
                        session_id(),
                    ]
                );
            } catch (Exception $e) {
                error_log('CatalogoController::busqueda ERROR: ' . $e->getMessage());
            }
        }

        foreach ($productos as &$prod) {
            if (!empty($prod['URLImagen'])) {
                $prod['URLImagen'] = IMG_PRODUCTOS . basename($prod['URLImagen']);
            }
        }
        unset($prod);

        $categorias  = $model->getCategorias();
        $maderas     = $model->getMaderas();
        $diseños     = $model->getDiseños();
        $acabados    = $model->getAcabados();
        $herrajes    = $model->getHerrajes();
        $almacenes   = $model->getAlmacenamientos();
        $tipos       = $model->getTiposProducto();

        $this->render('catalogo', compact(
            'productos','filtros','pagina','totalPags','total',
            'categorias','maderas','diseños','acabados',
            'herrajes','almacenes','tipos','buscar','orden'
        ));
    }
}