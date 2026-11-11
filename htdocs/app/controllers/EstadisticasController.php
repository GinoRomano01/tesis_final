<?php

class EstadisticasController extends Controller {

    private EstadisticasModel $model;

    private const DIAS_POR_DEFECTO = 30;
    private const ATAJOS_DIAS      = [7, 30, 90, 365];

    public function __construct() {
        parent::__construct();
        $this->verificarAdmin();
        checkPermiso('puedeVerEstadisticas');
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

    private static function fechaValida(?string $f): ?string {
        if (!$f) return null;
        $d = DateTime::createFromFormat('!Y-m-d', $f);
        return ($d && $d->format('Y-m-d') === $f) ? $f : null;
    }

    private function rango(): array {
        $hoy = date('Y-m-d');
        $desde = $hasta = null;

        if (isset($_GET['limpiar'])) {
            unset($_SESSION['est_rango']);
        } elseif (isset($_GET['mes'])) {
            $desde = date('Y-m-01');
            $hasta = $hoy;
        } elseif (isset($_GET['dias']) && in_array((int)$_GET['dias'], self::ATAJOS_DIAS, true)) {
            $desde = date('Y-m-d', strtotime('-' . ((int)$_GET['dias'] - 1) . ' days'));
            $hasta = $hoy;
        } elseif (isset($_GET['desde']) || isset($_GET['hasta'])) {
            $desde = self::fechaValida($_GET['desde'] ?? null);
            $hasta = self::fechaValida($_GET['hasta'] ?? null) ?? $hoy;
            if (!$desde) {
                Toast::new('La fecha "desde" no es válida; se usaron los últimos 30 días.', 'warning');
                $hasta = null;
            }
        } elseif (!empty($_SESSION['est_rango'])) {
            [$desde, $hasta] = $_SESSION['est_rango'];
        }

        if (!$desde || !$hasta) {
            $desde = date('Y-m-d', strtotime('-' . (self::DIAS_POR_DEFECTO - 1) . ' days'));
            $hasta = $hoy;
        }
        [$desde, $hasta] = self::normalizar($desde, $hasta);

        $_SESSION['est_rango'] = [$desde, $hasta];
        if (isset($_GET['limpiar'])) unset($_SESSION['est_cmp']);

        $dias  = EstadisticasModel::diasDelRango($desde, $hasta);
        $atajo = null;
        if ($hasta === $hoy) {
            if ($desde === date('Y-m-01') && !in_array($dias, self::ATAJOS_DIAS, true)) $atajo = 'mes';
            elseif (in_array($dias, self::ATAJOS_DIAS, true)) $atajo = $dias;
        }

        $rango = [
            'desde' => $desde, 'hasta' => $hasta, 'dias' => $dias, 'atajo' => $atajo,
            'cmp'   => $this->comparacion('', $desde, $hasta, 'est_cmp', false),
        ];
        $this->data['rango'] = $rango;
        return $rango;
    }

    private static function normalizar(string $desde, string $hasta): array {
        $hoy = date('Y-m-d');
        if ($desde > $hasta) [$desde, $hasta] = [$hasta, $desde];
        return [min($desde, $hoy), min($hasta, $hoy)];
    }

    private function comparacion(string $p, string $desde, string $hasta,
                                 string $claveSesion, bool $activoPorDefecto): array {
        $cfg = $this->cfgComparacionGet($p);
        if ($cfg) {
            $_SESSION[$claveSesion] = $cfg;
        } else {
            $cfg = $_SESSION[$claveSesion] ?? self::cfgComparacionInicial($activoPorDefecto);
        }
        return self::periodoComparacion($cfg, $desde, $hasta);
    }

    private static function cfgComparacionInicial(bool $activo): array {
        return ['activo' => $activo, 'modo' => 'anterior', 'mdesde' => null, 'mhasta' => null];
    }

    private function cfgComparacionGet(string $p): ?array {
        if (!isset($_GET[$p . 'cmp_on'])) return null;

        $modos = ['anterior', 'anio', 'medida'];
        $cfg = [
            'activo' => $_GET[$p . 'cmp_on'] === '1',
            'modo'   => in_array($_GET[$p . 'cmp'] ?? '', $modos, true) ? $_GET[$p . 'cmp'] : 'anterior',
            'mdesde' => self::fechaValida($_GET[$p . 'cmp_desde'] ?? null),
            'mhasta' => self::fechaValida($_GET[$p . 'cmp_hasta'] ?? null),
        ];
        if ($cfg['modo'] === 'medida' && !$cfg['mdesde']) {
            if ($cfg['activo']) {
                Toast::new('La fecha "desde" de la comparación no es válida; se comparó con el período anterior.', 'warning');
            }
            $cfg['modo'] = 'anterior';
        }
        return $cfg;
    }

    private static function periodoComparacion(array $cfg, string $desde, string $hasta): array {
        [$cDesde, $cHasta] = match ($cfg['modo']) {
            'anio'   => EstadisticasModel::mismoPeriodoAnioPasado($desde, $hasta),
            'medida' => self::normalizar($cfg['mdesde'], $cfg['mhasta'] ?? date('Y-m-d')),
            default  => EstadisticasModel::periodoAnterior($desde, $hasta),
        };
        $dias = EstadisticasModel::diasDelRango($cDesde, $cHasta);

        return [
            'activo'   => (bool)$cfg['activo'],
            'modo'     => $cfg['modo'],
            'desde'    => $cDesde,
            'hasta'    => $cHasta,
            'dias'     => $dias,
            'distinta' => $dias !== EstadisticasModel::diasDelRango($desde, $hasta),
        ];
    }

    private function rangoKpi(array $general): array {
        if (isset($_GET['kpi_igualar'])) {
            unset($_SESSION['est_kpi'], $_SESSION['est_kpi_cmp']);
        } elseif (isset($_GET['kpi_desde'])) {
            $d = self::fechaValida($_GET['kpi_desde']);
            $h = self::fechaValida($_GET['kpi_hasta'] ?? null) ?? date('Y-m-d');
            if ($d) {
                $_SESSION['est_kpi'] = self::normalizar($d, $h);
            } else {
                Toast::new('La fecha "desde" de las tarjetas no es válida.', 'warning');
            }
        }

        [$desde, $hasta] = $_SESSION['est_kpi'] ?? [$general['desde'], $general['hasta']];

        return [
            'desde'  => $desde,
            'hasta'  => $hasta,
            'dias'   => EstadisticasModel::diasDelRango($desde, $hasta),
            'propio' => isset($_SESSION['est_kpi']),
            'cmp'    => $this->comparacion('kpi_', $desde, $hasta, 'est_kpi_cmp', true),
        ];
    }

    private function rangoGrafico(string $clave, array $general): array {
        $propios = $_SESSION['est_graf'] ?? [];
        $igualar = $_GET['g_igualar'] ?? null;

        if ($igualar === 'todos') {
            $propios = [];
        } elseif ($igualar === $clave) {
            unset($propios[$clave]);
        } elseif (($_GET['g'] ?? null) === $clave) {
            $d = self::fechaValida($_GET['g_desde'] ?? null);
            $h = self::fechaValida($_GET['g_hasta'] ?? null) ?? date('Y-m-d');
            if ($d) {
                $propios[$clave] = [
                    'rango' => self::normalizar($d, $h),
                    'cmp'   => $this->cfgComparacionGet('g_') ?? self::cfgComparacionInicial(false),
                ];
            } else {
                Toast::new('La fecha "desde" del gráfico no es válida.', 'warning');
            }
        }
        $_SESSION['est_graf'] = $propios;

        if (isset($propios[$clave])) {
            [$desde, $hasta] = $propios[$clave]['rango'];
            $cmp = self::periodoComparacion($propios[$clave]['cmp'], $desde, $hasta);
        } else {
            ['desde' => $desde, 'hasta' => $hasta, 'cmp' => $cmp] = $general;
        }

        $rg = [
            'clave'  => $clave,
            'desde'  => $desde,
            'hasta'  => $hasta,
            'dias'   => EstadisticasModel::diasDelRango($desde, $hasta),
            'propio' => isset($propios[$clave]),
            'cmp'    => $cmp,
        ];
        $this->data['graf'][$clave] = $rg;
        return $rg;
    }

    private function ranking(array $rg, string $metodo, int $limite, string $clave, string $campo): array {
        $filas = $this->model->$metodo($rg['desde'], $rg['hasta'], $limite);
        if ($rg['cmp']['activo']) {
            $previo = $this->model->$metodo($rg['cmp']['desde'], $rg['cmp']['hasta'], 1000);
            $filas  = EstadisticasModel::cruzarRanking($filas, $previo, $clave, $campo);
        }
        return $filas;
    }

    public function index(): void {
        ['desde' => $d, 'hasta' => $h] = $rango = $this->rango();

        $kpi = $this->rangoKpi($rango);
        $this->data['kpi_rango']       = $kpi;
        $this->data['kpis']            = $this->model->kpisPeriodo($kpi['desde'], $kpi['hasta'],
                                             $kpi['cmp']['desde'], $kpi['cmp']['hasta']);

        $g = $this->rangoGrafico('ini_ventas', $rango);
        $this->data['ventas_diarias'] = $this->model->ventasPorDia($g['desde'], $g['hasta']);
        if ($g['cmp']['activo']) {
            $this->data['ventas_diarias_cmp'] = $this->model->ventasPorDia($g['cmp']['desde'], $g['cmp']['hasta']);
        }

        $g = $this->rangoGrafico('ini_visitas', $rango);
        $this->data['visitas_diarias'] = $this->model->visitasPorDia($g['desde'], $g['hasta']);
        if ($g['cmp']['activo']) {
            $this->data['visitas_diarias_cmp'] = $this->model->visitasPorDia($g['cmp']['desde'], $g['cmp']['hasta']);
        }

        $g = $this->rangoGrafico('ini_embudo', $rango);
        $this->data['embudo'] = $this->model->embudoConversion($g['desde'], $g['hasta']);
        if ($g['cmp']['activo']) {
            $this->data['embudo_cmp'] = $this->model->embudoConversion($g['cmp']['desde'], $g['cmp']['hasta']);
        }

        $this->data['productos_top'] = $this->ranking($this->rangoGrafico('ini_productos', $rango),
                                           'productosMasVendidos', 5, 'Id', 'unidades_vendidas');
        $this->data['terminos_top']  = $this->ranking($this->rangoGrafico('ini_busquedas', $rango),
                                           'terminosMasBuscados', 10, 'termino', 'veces');
        $this->data['por_tipo_pago'] = $this->model->ventasPorTipoPago($d, $h);

        $this->render('view_estadisticas_index');
    }

    public function api(): void {
        header('Content-Type: application/json');

        ['desde' => $d, 'hasta' => $h] = $rango = $this->rango();
        $seccion = $_GET['seccion'] ?? 'all';

        $data = match ($seccion) {
            'kpis'                    => $this->model->kpisPeriodo($d, $h),
            'ventas_diarias'          => $this->model->ventasPorDia($d, $h),
            'visitas'                 => $this->model->visitasPorDia($d, $h),
            'embudo'                  => $this->model->embudoConversion($d, $h),
            'productos'               => $this->model->productosMasVendidos($d, $h),
            'busquedas'               => $this->model->terminosMasBuscados($d, $h),
            'busquedas_sin_resultado' => $this->model->busquedasSinResultados($d, $h),
            'clientes'                => $this->model->topClientes($d, $h),
            default                   => [
                'kpis'            => $this->model->kpisPeriodo($d, $h),
                'ventas_diarias'  => $this->model->ventasPorDia($d, $h),
                'visitas_diarias' => $this->model->visitasPorDia($d, $h),
                'embudo'          => $this->model->embudoConversion($d, $h),
                'por_dispositivo' => $this->model->visitasPorDispositivo($d, $h),
                'por_tipo_pago'   => $this->model->ventasPorTipoPago($d, $h),
            ],
        };

        echo json_encode(['ok' => true, 'rango' => $rango, 'data' => $data, 'generado' => date('Y-m-d H:i:s')]);
        exit;
    }

    public function ventas(): void {
        ['desde' => $d, 'hasta' => $h] = $rango = $this->rango();

        $g = $this->rangoGrafico('ven_evolucion', $rango);
        $this->data['evolucion'] = $this->model->ventasEvolucion($g['desde'], $g['hasta']);
        if ($g['cmp']['activo']) {
            $this->data['evolucion_cmp'] = $this->model->ventasEvolucion($g['cmp']['desde'], $g['cmp']['hasta'],
                                               $this->data['evolucion']['agrupado'] === 'mes');
        }
        $this->data['top_productos'] = $this->ranking($this->rangoGrafico('ven_productos', $rango),
                                           'productosMasVendidos', 20, 'Id', 'unidades_vendidas');
        $this->data['top_clientes']  = $this->ranking($this->rangoGrafico('ven_clientes', $rango),
                                           'topClientes', 10, 'id_cliente', 'total_gastado');
        $this->data['estado']        = $this->model->estadoVentasPeriodo($d, $h);
        $this->data['por_tipo_pago'] = $this->model->ventasPorTipoPago($d, $h);

        $this->render('view_estadisticas_ventas');
    }

    public function visitas(): void {
        ['desde' => $d, 'hasta' => $h] = $rango = $this->rango();

        $g = $this->rangoGrafico('vis_visitas', $rango);
        $this->data['visitas_diarias'] = $this->model->visitasPorDia($g['desde'], $g['hasta']);
        if ($g['cmp']['activo']) {
            $this->data['visitas_diarias_cmp'] = $this->model->visitasPorDia($g['cmp']['desde'], $g['cmp']['hasta']);
        }

        $g = $this->rangoGrafico('vis_eventos', $rango);
        $this->data['eventos'] = $this->model->eventosPorTipo($g['desde'], $g['hasta']);
        if ($g['cmp']['activo']) {
            $previo = $this->model->eventosPorTipo($g['cmp']['desde'], $g['cmp']['hasta']);
            $this->data['eventos'] = EstadisticasModel::cruzarRanking($this->data['eventos'], $previo, 'tipo', 'total');
        }

        $this->data['paginas_top']  = $this->ranking($this->rangoGrafico('vis_paginas', $rango),
                                          'paginasMasVisitadas', 20, 'url', 'total_visitas');
        $this->data['dispositivos'] = $this->model->visitasPorDispositivo($d, $h);

        $this->render('view_estadisticas_visitas');
    }

    public function busquedas(): void {
        ['desde' => $d, 'hasta' => $h] = $rango = $this->rango();

        $this->data['top10']          = $this->ranking($this->rangoGrafico('bus_top', $rango),
                                            'terminosMasBuscados', 10, 'termino', 'veces');
        $this->data['nube']           = $this->ranking($this->rangoGrafico('bus_nube', $rango),
                                            'terminosMasBuscados', 30, 'termino', 'veces');
        $this->data['terminos_top']   = $this->model->terminosMasBuscados($d, $h, 30);
        $this->data['sin_resultados'] = $this->model->busquedasSinResultados($d, $h, 20);

        $this->render('view_estadisticas_busquedas');
    }

    public function exportar(string $seccion = 'ventas'): void {
        ['desde' => $d, 'hasta' => $h] = $this->rango();
        $stock = fn() => new EstadisticasStockModel();

        $secciones = [
            'ventas'    => ['archivo' => 'ventas',          'filas' => fn() => $this->model->ventasEvolucion($d, $h)['filas']],
            'productos' => ['archivo' => 'productos_top',   'filas' => fn() => $this->model->productosMasVendidos($d, $h, 500)],
            'visitas'   => ['archivo' => 'paginas_top',     'filas' => fn() => $this->model->paginasMasVisitadas($d, $h, 500)],
            'busquedas' => ['archivo' => 'busquedas_top',   'filas' => fn() => $this->model->terminosMasBuscados($d, $h, 500)],
            'clientes'  => ['archivo' => 'clientes_top',    'filas' => fn() => $this->model->topClientes($d, $h, 500)],
            'stock'     => ['archivo' => 'historico_stock', 'filas' => fn() => $stock()->historicoDiagnosticos($d, $h, 500)],
        ];

        if (!isset($secciones[$seccion])) {
            Toast::new('Sección de exportación inválida', 'warning');
            Redirect::to('estadisticas');
            exit;
        }

        $cfg   = $secciones[$seccion];
        $filas = ($cfg['filas'])();

        if (empty($filas)) {
            Toast::new('No hay datos para exportar en el período elegido', 'info');
            Redirect::to('estadisticas');
            exit;
        }

        $nombre = $cfg['archivo'] . '_' . str_replace('-', '', $d) . '_' . str_replace('-', '', $h) . '.csv';
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
        ['desde' => $d, 'hasta' => $h] = $rango = $this->rango();
        $modelStock = new EstadisticasStockModel();

        $this->data['comparativa']     = $modelStock->comparativaUltimoDiagnostico();

        foreach (['sto_valor' => 'evolucion_valor', 'sto_items' => 'evolucion_items'] as $clave => $var) {
            $g = $this->rangoGrafico($clave, $rango);
            $this->data[$var] = $modelStock->evolucionValor($g['desde'], $g['hasta']);
            if ($g['cmp']['activo']) {
                $this->data[$var . '_cmp'] = $modelStock->evolucionValor($g['cmp']['desde'], $g['cmp']['hasta']);
            }
        }
        $this->data['evolucion_salud'] = $modelStock->evolucionSalud($d, $h);
        $this->data['inflacion']       = $modelStock->inflacionPeriodo($d, $h);
        $this->data['volatiles']       = $modelStock->materialesVolatiles($d, $h, 10);
        $this->data['historico']       = $modelStock->historicoDiagnosticos($d, $h, 10);
        $this->data['ultimo_ia']       = $modelStock->ultimoAnalisisIA();

        $this->render('view_estadisticas_stock');
    }

    public function resenas(): void {
        ['desde' => $d, 'hasta' => $h] = $rango = $this->rango();
        $analisis = new ResenaAnalisisModel();

        $this->data['metricas'] = $analisis->metricas($d, $h);
        $this->data['cola']     = (new ResenaModel())->listarPorEstado('en_revision');
        $g = $this->rangoGrafico('res_sentimiento', $rango);
        $this->data['sent_graf'] = $g['propio']
            ? $analisis->metricas($g['desde'], $g['hasta'])
            : $this->data['metricas'];
        if ($g['cmp']['activo']) {
            $this->data['sent_cmp'] = $analisis->metricas($g['cmp']['desde'], $g['cmp']['hasta']);
        }

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
