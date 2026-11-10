<?php
class EstadisticasController extends Controller {

    private EstadisticasModel $model;

    public function __construct() {
        parent::__construct();
        $this->verificarAdmin();
        $this->title  = 'Estadísticas — San Plácido Admin';
        $this->model  = new EstadisticasModel();
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
        $dias = (int)($_GET['dias'] ?? 30);
        if (!in_array($dias, [7, 30, 90, 365])) $dias = 30;

        $this->data['kpis']              = $this->model->kpisHoy();
        $this->data['ventas_diarias']    = $this->model->ventasUltimosDias($dias);
        $this->data['visitas_diarias']   = $this->model->visitasPorDia($dias);
        $this->data['productos_top']     = $this->model->productosMasVendidos(5);
        $this->data['embudo']            = $this->model->embudoConversion();
        $this->data['por_dispositivo']   = $this->model->visitasPorDispositivo();
        $this->data['por_tipo_pago']     = $this->model->ventasPorTipoPago();
        $this->data['terminos_top']      = $this->model->terminosMasBuscados(10);
        $this->data['dias']              = $dias;

        $this->render('view_estadisticas_index');
    }

    public function api(): void {
        header('Content-Type: application/json');

        $seccion = $_GET['seccion'] ?? 'all';
        $dias    = (int)($_GET['dias'] ?? 30);
        if (!in_array($dias, [7, 30, 90, 365])) $dias = 30;

        $data = [];

        switch ($seccion) {
            case 'kpis':
                $data = $this->model->kpisHoy();
                break;
            case 'ventas_diarias':
                $data = $this->model->ventasUltimosDias($dias);
                break;
            case 'visitas':
                $data = $this->model->visitasPorDia($dias);
                break;
            case 'embudo':
                $data = $this->model->embudoConversion();
                break;
            case 'productos':
                $data = $this->model->productosMasVendidos();
                break;
            case 'busquedas':
                $data = $this->model->terminosMasBuscados();
                break;
            case 'busquedas_sin_resultado':
                $data = $this->model->busquedasSinResultados();
                break;
            case 'clientes':
                $data = $this->model->topClientes();
                break;
            default:
                $data = [
                    'kpis'            => $this->model->kpisHoy(),
                    'ventas_diarias'  => $this->model->ventasUltimosDias($dias),
                    'visitas_diarias' => $this->model->visitasPorDia($dias),
                    'embudo'          => $this->model->embudoConversion(),
                    'por_dispositivo' => $this->model->visitasPorDispositivo(),
                    'por_tipo_pago'   => $this->model->ventasPorTipoPago(),
                ];
                break;
        }

        echo json_encode(['ok' => true, 'data' => $data, 'generado' => date('Y-m-d H:i:s')]);
        exit;
    }

    public function ventas(): void {
        $this->data['ventas_mensuales'] = $this->model->ventasMensuales();
        $this->data['estado_mes']       = $this->model->estadoVentasMes();
        $this->data['top_productos']    = $this->model->productosMasVendidos(20);
        $this->data['top_clientes']     = $this->model->topClientes(10);
        $this->data['por_tipo_pago']    = $this->model->ventasPorTipoPago();

        $this->render('view_estadisticas_ventas');
    }

    public function visitas(): void {
        $dias = (int)($_GET['dias'] ?? 30);
        if (!in_array($dias, [7, 30, 90])) $dias = 30;

        $this->data['visitas_diarias'] = $this->model->visitasPorDia($dias);
        $this->data['paginas_top']     = $this->model->paginasMasVisitadas(20);
        $this->data['dispositivos']    = $this->model->visitasPorDispositivo();
        $this->data['eventos']         = $this->model->eventosPorTipo();
        $this->data['dias']            = $dias;

        $this->render('view_estadisticas_visitas');
    }

    public function busquedas(): void {
        $this->data['terminos_top']         = $this->model->terminosMasBuscados(30);
        $this->data['sin_resultados']       = $this->model->busquedasSinResultados(20);

        $this->render('view_estadisticas_busquedas');
    }

    public function exportar(string $seccion = 'ventas'): void {
        $secciones = [
            'ventas'    => ['modelo' => 'EstadisticasModel',       'metodo' => 'ventasMensuales',       'archivo' => 'ventas_mensuales'],
            'productos' => ['modelo' => 'EstadisticasModel',       'metodo' => 'productosMasVendidos',  'archivo' => 'productos_top'],
            'visitas'   => ['modelo' => 'EstadisticasModel',       'metodo' => 'paginasMasVisitadas',   'archivo' => 'paginas_top'],
            'busquedas' => ['modelo' => 'EstadisticasModel',       'metodo' => 'terminosMasBuscados',   'archivo' => 'busquedas_top'],
            'clientes'  => ['modelo' => 'EstadisticasModel',       'metodo' => 'topClientes',           'archivo' => 'clientes_top'],
            'stock'     => ['modelo' => 'EstadisticasStockModel',  'metodo' => 'historicoDiagnosticos', 'archivo' => 'historico_stock'],
        ];

        if (!isset($secciones[$seccion])) {
            Toast::new('Sección de exportación inválida', 'warning');
            Redirect::to('estadisticas');
            exit;
        }

        $cfg = $secciones[$seccion];
        $modelClass = $cfg['modelo'];
        $model = ($modelClass === 'EstadisticasStockModel') ? new EstadisticasStockModel() : $this->model;
        $metodo = $cfg['metodo'];
        $filas = $model->$metodo(500);

        if (empty($filas)) {
            Toast::new('No hay datos para exportar', 'info');
            Redirect::to('estadisticas');
            exit;
        }

        $nombre = $cfg['archivo'] . '_' . date('Ymd') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nombre . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, array_keys($filas[0]), ';');
        foreach ($filas as $fila) fputcsv($output, $fila, ';');
        fclose($output);
        exit;
    }

    public function stock(): void {
        $modelStock = new EstadisticasStockModel();

        $this->data['comparativa']         = $modelStock->comparativaUltimoDiagnostico();
        $this->data['evolucion_valor']     = $modelStock->evolucionValor(20);
        $this->data['evolucion_salud']     = $modelStock->evolucionSalud(20);
        $this->data['inflacion_categoria'] = $modelStock->inflacionPorCategoria();
        $this->data['volatiles']           = $modelStock->materialesVolatiles(10);
        $this->data['historico']           = $modelStock->historicoDiagnosticos(10);
        $this->data['ultimo_ia']           = $modelStock->ultimoAnalisisIA();

        $this->render('view_estadisticas_stock');
    }





    public function resenas(): void {
        $desde = $_GET['desde'] ?? null;
        $hasta = $_GET['hasta'] ?? null;

        $this->data['metricas'] = (new ResenaAnalisisModel())->metricas($desde, $hasta);
        $this->data['cola']     = (new ResenaModel())->listarPorEstado('en_revision');
        $this->data['desde']    = $desde;
        $this->data['hasta']    = $hasta;

        $this->render('view_estadisticas_resenas');
    }

    public function moderar_resena($id = null, $accion = null): void {
        $id     = (int) $id;
        $accion = in_array($accion, ['aprobada','rechazada','oculta'], true) ? $accion : null;

        if (!$id || !$accion) {
            Toast::new('Acción inválida.', 'danger');
            Redirect::to('estadisticas/resenas');
            exit;
        }

        (new ResenaModel())->cambiarEstado($id, $accion);
        (new ResenaHistorialModel())->registrar(
            $id, $accion, (int)($_SESSION['usuario_id'] ?? 0), ['via' => 'panel_estadisticas']
        );

        Toast::new("Reseña marcada como {$accion}.", 'success');
        Redirect::to('estadisticas/resenas');
        exit;
    }

}