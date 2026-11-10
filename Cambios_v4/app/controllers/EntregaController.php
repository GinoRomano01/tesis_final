<?php

class EntregaController extends Controller {

    private EntregaModel $model;

    public function __construct() {
        parent::__construct();
        $this->verificarAdmin();
        $this->title = 'Entregas — San Plácido Admin';
        $this->model = new EntregaModel();

        if (!puedeVerEntregas()) {
            Toast::new('Sin permisos para acceder a Entregas.', 'danger');
            Redirect::to('admin/LobbyAdmin');
            exit;
        }
        $estados = $this->model->getEstados();
        if (empty($estados)) {
            $this->model->seedEstados();
        }
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
        $buscar        = trim($_GET['buscar'] ?? '');
        $idEstadoFiltro = (int)($_GET['estado'] ?? 0);
        $orden         = trim($_GET['orden'] ?? 'fecha_desc');

        $tipos           = $this->model->getTiposEntrega();
        $conteos         = $this->model->contarPorEstado();
        $estadosColumnas = EntregaModel::ESTADOS_COLUMNAS;                                                                                   
        $idEstadoAncho   = EntregaModel::ESTADO_ANCHO_ID;                   
        $estadosDrag     = EntregaModel::ESTADOS_DRAG;                                                           
        $estadosTodos    = $this->model->getEstados();                                           

        if ($idEstadoFiltro > 0) {
            $porPagina    = 15;
            $pagina       = max(1, (int)($_GET['pagina'] ?? 1));
            $items        = $this->model->listarPorEstadoPaginado($idEstadoFiltro, $pagina, $porPagina, $buscar, $orden);
            $total        = $this->model->contarConFiltro($idEstadoFiltro, $buscar);
            $totalPaginas = max(1, (int)ceil($total / $porPagina));

            $this->render('index', compact(
                'tipos', 'conteos', 'estadosColumnas', 'idEstadoAncho', 'estadosDrag', 'estadosTodos',
                'buscar', 'idEstadoFiltro', 'orden', 'items', 'pagina', 'totalPaginas', 'total'
            ));
            return;
        }

                                  
        $porPaginaCol = 6;
        $columnas     = [];
        foreach ($estadosColumnas as $idEst => $nombreEst) {
            $paginaCol = max(1, (int)($_GET['p_' . $idEst] ?? 1));
            $totalCol  = $this->model->contarConFiltro($idEst, $buscar);
            $columnas[$idEst] = [
                'items'        => $this->model->listarPorEstadoPaginado($idEst, $paginaCol, $porPaginaCol, $buscar, $orden),
                'pagina'       => $paginaCol,
                'total'        => $totalCol,
                'totalPaginas' => max(1, (int)ceil($totalCol / $porPaginaCol)),
            ];
        }

                                  
        $porPaginaAncho = 10;
        $paginaAncho    = max(1, (int)($_GET['p_5'] ?? 1));
        $totalAncho     = $this->model->contarConFiltro($idEstadoAncho, $buscar);
        $seccionAncha   = [
            'items'        => $this->model->listarPorEstadoPaginado($idEstadoAncho, $paginaAncho, $porPaginaAncho, $buscar, $orden),
            'pagina'       => $paginaAncho,
            'total'        => $totalAncho,
            'totalPaginas' => max(1, (int)ceil($totalAncho / $porPaginaAncho)),
        ];

        $this->render('index', compact(
            'tipos', 'conteos', 'estadosColumnas', 'idEstadoAncho', 'estadosDrag', 'estadosTodos',
            'buscar', 'idEstadoFiltro', 'orden', 'columnas', 'seccionAncha'
        ));
    }                                                        
                                                                               
    public function cambiarEstadoRapido(): void {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $id       = (int)($_POST['id'] ?? 0);
        $idEstado = (int)($_POST['estado'] ?? 0);

        if ($id <= 0 || !in_array($idEstado, EntregaModel::ESTADOS_DRAG, true)) {
            echo json_encode(['ok' => false, 'error' => 'Ese estado no se puede asignar arrastrando — usá "Iniciar entrega" o "Mi ruta".']);
            exit;
        }

        $entregaActual = $this->model->getPorId($id);
        $this->model->cambiarEstado($id, $idEstado);

        if ($entregaActual) {
            $this->_notificarCambioEstado($entregaActual, $idEstado);
        }

        echo json_encode(['ok' => true]);
        exit;
    }
    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('entrega');
            exit;
        }

        $id = (int)($_POST['Id'] ?? 0);
        if ($id <= 0) {
            Toast::new('Entrega no válida', 'danger');
            Redirect::to('entrega');
            exit;
        }

                                                                                      
        $entregaActual   = $this->model->getPorId($id);                                                     
        $estadoAnterior  = (int)($entregaActual['IdEstadosdeEntrega'] ?? 0);

        $d = [
            'IdEstadosdeEntrega' => $_POST['IdEstadosdeEntrega'] ?? 1,
            'IdTipodeEntrega'    => $_POST['IdTipodeEntrega']    ?? 1,
            'FechadeEntrega'     => $_POST['FechadeEntrega']     ?? null,
            'Direccion'          => $_POST['Direccion']          ?? '',
            'CostoEnvio'         => $_POST['CostoEnvio']        ?? 0,
            'CodigoEntrega'      => $_POST['CodigoEntrega']      ?? '',
        ];

        $this->model->editarEntrega($id, $d);

        $nuevoEstado = (int)$d['IdEstadosdeEntrega'];
        if ($nuevoEstado !== $estadoAnterior) {
            $this->_notificarCambioEstado($entregaActual, $nuevoEstado);
        }

        Toast::new('Entrega actualizada correctamente', 'success');
        Redirect::to('entrega');
        exit;
    }
    public function estado($id = null): void {
        $id       = (int)$id;
        $idEstado = (int)($_GET['s'] ?? 0);

        if ($id <= 0 || $idEstado <= 0) {
            Redirect::to('entrega');
            exit;
        }

        $entregaActual = $this->model->getPorId($id);

        $this->model->cambiarEstado($id, $idEstado);

        if ($entregaActual) {
            $this->_notificarCambioEstado($entregaActual, $idEstado);
        }

        Toast::new('Estado actualizado', 'success');
        Redirect::to('entrega');
        exit;
    }                                                                                       
    public function estadoMasivo(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('entrega');
            exit;
        }

        $ids      = json_decode($_POST['ids'] ?? '[]', true);
        $idEstado = (int)($_POST['estado'] ?? 0);

        if (!is_array($ids) || empty($ids) || $idEstado <= 0) {
            Toast::new('Seleccioná al menos una entrega y un estado de destino.', 'warning');
            Redirect::to('entrega');
            exit;
        }

        $actualizadas = 0;
        foreach ($ids as $id) {
            $id = (int)$id;
            if ($id <= 0) continue;

            $entregaActual = $this->model->getPorId($id);
            $this->model->cambiarEstado($id, $idEstado);

            if ($entregaActual) {
                $this->_notificarCambioEstado($entregaActual, $idEstado);
            }
            $actualizadas++;
        }

        Toast::new("{$actualizadas} entrega(s) actualizada(s) correctamente.", 'success');
        Redirect::to('entrega');
        exit;
    }  
    private function _getClienteIdDesdeVenta(int $idVenta): ?int {
        $row = Db::query("
            SELECT car.IdCliente
            FROM Venta v
            LEFT JOIN Carrito car ON car.Id = v.IdCarrito
            WHERE v.Id = ?
            LIMIT 1
        ", [$idVenta])->fetch();

        return $row['IdCliente'] ?? null;
    }   
    private function _mensajeEstadoEntrega(int $idEstado): array {
        return match ($idEstado) {
            2 => ['Tu pedido está en preparación',      'fa-hammer'],
            3 => ['Tu pedido está listo para retirar',  'fa-box-open'],
            4 => ['Tu pedido está en camino',           'fa-truck'],
            5 => ['Tu pedido fue entregado',            'fa-circle-check'],
            default => ['Actualización de tu pedido',   'fa-bell'],
        };
    }
    private function _notificarCambioEstado(array $entrega, int $nuevoEstado): void {
        $idVenta   = (int)($entrega['IdVenta'] ?? 0);
        if (!$idVenta) return;

        $idCliente = $this->_getClienteIdDesdeVenta($idVenta);
        if (!$idCliente) return;

        [$titulo, $icono] = $this->_mensajeEstadoEntrega($nuevoEstado);
        $codigo = $entrega['CodigoEntrega'] ?? '—';

        $contenidoHtml = "<p>{$titulo}.</p><p>Código de entrega: <strong>" . htmlspecialchars($codigo) . "</strong></p>";

        NotificacionModel::crearConEmail(
            [
                'IdCliente'  => $idCliente,
                'Tipo'       => 'entrega_actualizada',
                'Titulo'     => $titulo,
                'Contenido'  => 'Código de entrega: ' . $codigo,
                'UrlDestino' => 'pedidocliente',
                'Icono'      => $icono,
            ],
            $titulo . ' — San Plácido',
            $titulo,
            $contenidoHtml,
            'Ver mi pedido',
            'pedidocliente'
        );
    }
    public function iniciarentrega(): void {
        checkPermiso('puedeIniciarEntrega');

        $entregas     = $this->model->getListasParaRuta();
        $repartidores = esAdmin() ? $this->model->getRepartidores() : [];

        $this->render('iniciarentrega', compact('entregas', 'repartidores'));
    }  
    public function confirmarruta(): void {
        checkPermiso('puedeIniciarEntrega');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('entrega/iniciarentrega');
            exit;
        }

        $idRepartidor = esRepartidor()
            ? (int)($_SESSION['usuario_id'] ?? 0)
            : (int)($_POST['IdRepartidor'] ?? 0);

        $idsEnOrden = json_decode($_POST['Orden'] ?? '[]', true);

        if ($idRepartidor <= 0) {
            Toast::new('Elegí a qué repartidor asignarle la ruta.', 'warning');
            Redirect::to('entrega/iniciarentrega');
            exit;
        }
        if (!is_array($idsEnOrden) || empty($idsEnOrden)) {
            Toast::new('Seleccioná al menos una entrega para la ruta.', 'warning');
            Redirect::to('entrega/iniciarentrega');
            exit;
        }                                                                  
                                                                 
        $entregasPrevias = [];
        foreach ($idsEnOrden as $id) {
            $entregasPrevias[] = $this->model->getPorId((int)$id);
        }

        $idRuta = $this->model->crearRuta($idRepartidor, $idsEnOrden);
        if (!$idRuta) {
            Toast::new('No se pudo crear la ruta. Probá de nuevo.', 'danger');
            Redirect::to('entrega/iniciarentrega');
            exit;
        }

        foreach ($entregasPrevias as $entregaActual) {
            if ($entregaActual) {
                $this->_notificarCambioEstado($entregaActual, 4);
            }
        }

        Toast::new(count($idsEnOrden) . ' entrega(s) en camino.', 'success');
        Redirect::to('entrega/miruta');
        exit;
    }
    public function miruta(): void {
        if (!esRepartidor()) {
            Toast::new('Esta pantalla es solo para repartidores.', 'danger');
            Redirect::to('entrega');
            exit;
        }

        $idRepartidor = (int)($_SESSION['usuario_id'] ?? 0);
        $ruta         = $this->model->getRutaActiva($idRepartidor);
        $entregas     = $ruta ? $this->model->getEntregasDeRuta((int)$ruta['Id']) : [];

        $this->render('miruta', compact('ruta', 'entregas'));
    }
    public function marcarentregado($id = null): void {
        if (!esRepartidor()) {
            Toast::new('Esta acción es solo para repartidores.', 'danger');
            Redirect::to('entrega');
            exit;
        }

        $id = (int)$id;
        if ($id <= 0) { Redirect::to('entrega/miruta'); exit; }

        $entregaActual = $this->model->getPorId($id);
        $idRuta = (int)($entregaActual['IdRuta'] ?? 0);
        if (!$entregaActual || $idRuta <= 0) {
            Toast::new('Entrega no válida.', 'warning');
            Redirect::to('entrega/miruta');
            exit;
        }

        $this->model->marcarEntregado($id, $idRuta);
        $this->_notificarCambioEstado($entregaActual, 5);

        Toast::new('Marcada como entregada.', 'success');
        Redirect::to('entrega/miruta');
        exit;
    }
    public function actualizarubicacion(): void {
        header('Content-Type: application/json');

        if (!esRepartidor() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode(['ok' => false]);
            exit;
        }

        $idRepartidor = (int)($_SESSION['usuario_id'] ?? 0);
        $ruta = $this->model->getRutaActiva($idRepartidor);
        if (!$ruta) {
            echo json_encode(['ok' => false, 'error' => 'Sin ruta activa']);
            exit;
        }

        $lat = (float)($_POST['lat'] ?? 0);
        $lng = (float)($_POST['lng'] ?? 0);
        if ($lat === 0.0 && $lng === 0.0) {
            echo json_encode(['ok' => false, 'error' => 'Coordenadas inválidas']);
            exit;
        }

        $ok = $this->model->actualizarUbicacion($idRepartidor, $lat, $lng);
        if (!$ok) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'No se pudo guardar la ubicación (¿falta correr la migración de UbicacionRepartidor?)']);
            exit;
        }

        echo json_encode(['ok' => true]);
        exit;
    }
}