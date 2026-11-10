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
        (new PagoModel())->sincronizarPendientesVencidos();
        $this->model->sincronizar();

        $buscar       = trim($_GET['buscar'] ?? '');
        $estadoFiltro = trim($_GET['estado'] ?? '');
        $orden        = trim($_GET['orden'] ?? 'fecha_desc');

        $conteos         = $this->model->contarPorEstado();
        $ventasElegibles = $this->model->getVentasElegibles();
        $estadosKanban   = PedidoModel::ESTADOS_KANBAN;
        $estadosColumnas = PedidoModel::ESTADOS_COLUMNAS;
        $estadoListo     = PedidoModel::ESTADO_LISTO;
        $estados         = PedidoModel::ESTADOS;

        if ($estadoFiltro !== '') {
            $porPagina    = 15;
            $pagina       = max(1, (int)($_GET['pagina'] ?? 1));
            $items        = $this->model->listarPorEstadoPaginado($estadoFiltro, $pagina, $porPagina, $buscar, $orden);
            $total        = $this->model->contarConFiltro($estadoFiltro, $buscar);
            $totalPaginas = max(1, (int)ceil($total / $porPagina));

            $this->render('index', compact(
                'conteos', 'ventasElegibles', 'estados', 'estadosKanban', 'estadosColumnas', 'estadoListo',
                'buscar', 'estadoFiltro', 'orden', 'items', 'pagina', 'totalPaginas', 'total'
            ));
            return;
        }

        $porPaginaCol = 6;
        $columnas     = [];
        foreach ($estadosColumnas as $est) {
            $paginaCol = max(1, (int)($_GET['p_' . $this->_slugEstado($est)] ?? 1));
            $totalCol  = $this->model->contarConFiltro($est, $buscar);
            $columnas[$est] = [
                'items'        => $this->model->listarPorEstadoPaginado($est, $paginaCol, $porPaginaCol, $buscar, $orden),
                'pagina'       => $paginaCol,
                'total'        => $totalCol,
                'totalPaginas' => max(1, (int)ceil($totalCol / $porPaginaCol)),
            ];
        }

        $porPaginaListo = 10;
        $paginaListo    = max(1, (int)($_GET['p_listo'] ?? 1));
        $totalListo     = $this->model->contarConFiltro($estadoListo, $buscar);
        $seccionListo   = [
            'items'        => $this->model->listarPorEstadoPaginado($estadoListo, $paginaListo, $porPaginaListo, $buscar, $orden),
            'pagina'       => $paginaListo,
            'total'        => $totalListo,
            'totalPaginas' => max(1, (int)ceil($totalListo / $porPaginaListo)),
        ];

        $this->render('index', compact(
            'conteos', 'ventasElegibles', 'estados', 'estadosKanban', 'estadosColumnas', 'estadoListo',
            'buscar', 'estadoFiltro', 'orden', 'columnas', 'seccionListo'
        ));
    }

    private function _slugEstado(string $estado): string {
        $s = strtolower($estado);
        $s = str_replace(['ó', 'ú', 'á', 'é', 'í', ' '], ['o', 'u', 'a', 'e', 'i', '_'], $s);
        return $s;
    }

    public function cambiarEstadoRapido(): void {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $estado = trim($_POST['estado'] ?? '');

        if ($id <= 0 || !in_array($estado, PedidoModel::ESTADOS_KANBAN, true)) {
            echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
            exit;
        }
        if ($estado === 'Cancelado' && !puedeCancelarPedido()) {
            echo json_encode(['ok' => false, 'error' => 'No tenés permisos para cancelar pedidos.']);
            exit;
        }

        $this->model->cambiarEstadoMasivo($id, $estado);
        echo json_encode(['ok' => true]);
        exit;
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

        if (!in_array($estado, PedidoModel::ESTADOS_KANBAN)) {
            Toast::new('Estado no válido', 'warning');
            Redirect::to('pedido');
            exit;
        }
        if ($estado === 'Cancelado' && !puedeCancelarPedido()) {
            Toast::new('No tenés permisos para cancelar pedidos.', 'danger');
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

    public function estadoMasivo(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('pedido');
            exit;
        }

        $ids    = json_decode($_POST['ids'] ?? '[]', true);
        $estado = trim($_POST['Estado'] ?? '');

        if (!is_array($ids) || empty($ids)) {
            Toast::new('Seleccioná al menos un pedido.', 'warning');
            Redirect::to('pedido');
            exit;
        }
        if (!in_array($estado, PedidoModel::ESTADOS_KANBAN)) {
            Toast::new('Estado no válido', 'warning');
            Redirect::to('pedido');
            exit;
        }
        if ($estado === 'Cancelado' && !puedeCancelarPedido()) {
            Toast::new('No tenés permisos para cancelar pedidos.', 'danger');
            Redirect::to('pedido');
            exit;
        }

        $actualizados = 0;
        foreach ($ids as $id) {
            $id = (int)$id;
            if ($id <= 0) continue;
            $this->model->cambiarEstadoMasivo($id, $estado);
            $actualizados++;
        }

        Toast::new("{$actualizados} pedido(s) actualizado(s) a \"{$estado}\".", 'success');
        Redirect::to('pedido');
        exit;
    }

    public function bajaMasiva(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('pedido');
            exit;
        }
        if (!puedeEliminar()) {
            Toast::new('No tenés permisos para dar de baja pedidos.', 'danger');
            Redirect::to('pedido');
            exit;
        }

        $ids = json_decode($_POST['ids'] ?? '[]', true);
        if (!is_array($ids) || empty($ids)) {
            Toast::new('Seleccioná al menos un pedido.', 'warning');
            Redirect::to('pedido');
            exit;
        }

        $dados = 0;
        foreach ($ids as $id) {
            $id = (int)$id;
            if ($id <= 0) continue;
            $this->model->darDeBaja($id);
            $dados++;
        }

        Toast::new("{$dados} pedido(s) dado(s) de baja.", 'success');
        Redirect::to('pedido');
        exit;
    }
}