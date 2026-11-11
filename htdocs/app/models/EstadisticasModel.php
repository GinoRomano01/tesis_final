<?php

class EstadisticasModel extends Model {

    private const SESIONES_PERSONAL =
        "SELECT DISTINCT vp.SesionId
           FROM VistasDePagina vp
           JOIN Usuario u ON u.Id = vp.IdUsuario
          WHERE u.IdTipodeUsuario <> 2
            AND vp.SesionId IS NOT NULL";

    private const USUARIOS_PERSONAL =
        "SELECT Id FROM Usuario WHERE IdTipodeUsuario <> 2";

    private const CLIENTES_PERSONAL =
        "SELECT IdCliente FROM Usuario WHERE IdTipodeUsuario <> 2 AND IdCliente IS NOT NULL";

    private function sinPersonal(string $alias = ''): string {
        $a = $alias !== '' ? $alias . '.' : '';
        return "({$a}IdUsuario IS NULL OR {$a}IdUsuario NOT IN (" . self::USUARIOS_PERSONAL . "))
            AND ({$a}SesionId IS NULL OR {$a}SesionId NOT IN (" . self::SESIONES_PERSONAL . "))";
    }

    private function entre(string $columna): string {
        return "$columna >= ? AND $columna < DATE_ADD(?, INTERVAL 1 DAY)";
    }

    public static function diasDelRango(string $desde, string $hasta): int {
        return (int)(new DateTime($desde))->diff(new DateTime($hasta))->days + 1;
    }

    public static function periodoAnterior(string $desde, string $hasta): array {
        $antHasta = (new DateTime($desde))->modify('-1 day')->format('Y-m-d');
        $antDesde = (new DateTime($antHasta))
            ->modify('-' . (self::diasDelRango($desde, $hasta) - 1) . ' day')->format('Y-m-d');
        return [$antDesde, $antHasta];
    }

    public static function mismoPeriodoAnioPasado(string $desde, string $hasta): array {
        $menosUnAnio = function (string $f): string {
            [$a, $m, $d] = explode('-', $f);
            $a = (int)$a - 1;
            $d = min((int)$d, (int)date('t', mktime(0, 0, 0, (int)$m, 1, $a)));
            return sprintf('%04d-%s-%02d', $a, $m, $d);
        };
        return [$menosUnAnio($desde), $menosUnAnio($hasta)];
    }

    public static function cruzarRanking(array $actual, array $comparado, string $clave, string $campo): array {
        $previo = array_column($comparado, $campo, $clave);
        foreach ($actual as &$fila) {
            $antes = (float)($previo[$fila[$clave]] ?? 0);
            $fila['cmp']       = $antes;
            $fila['cmp_var']   = $antes > 0 ? round(((float)$fila[$campo] - $antes) / $antes * 100, 1) : null;
            $fila['cmp_nuevo'] = $antes <= 0;
        }
        unset($fila);
        return $actual;
    }

    private function rellenarSerie(array $filas, string $desde, string $hasta,
                                   string $clave, array $ceros, bool $porMes = false): array {
        $porFecha = [];
        foreach ($filas as $f) $porFecha[$f[$clave]] = $f;

        $out    = [];
        $actual = new DateTime($porMes ? substr($desde, 0, 7) . '-01' : $desde);
        $fin    = new DateTime($porMes ? substr($hasta, 0, 7) . '-01' : $hasta);
        $paso   = $porMes ? '+1 month' : '+1 day';
        $fmt    = $porMes ? 'Y-m' : 'Y-m-d';

        while ($actual <= $fin) {
            $k = $actual->format($fmt);
            $out[] = $porFecha[$k] ?? array_merge([$clave => $k], $ceros);
            $actual->modify($paso);
        }
        return $out;
    }

    public static function registrarEvento(string $tipo, ?string $modulo = null, $elementoId = null,
                                           ?string $elementoTipo = null, ?array $extra = null): void {
        try {
            $stmt = Db::getInstance()->getConnection()->prepare(
                "INSERT INTO EventosDeUsuario
                    (TipoEvento, Modulo, ElementoId, ElementoTipo, ValorExtra,
                     IdUsuario, IdCliente, SesionId, IpHash)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $tipo,
                $modulo,
                $elementoId !== null ? (string)$elementoId : null,
                $elementoTipo,
                $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : null,
                $_SESSION['usuario_id'] ?? null,
                $_SESSION['cliente_id'] ?? null,
                session_id() ?: null,
                hash('sha256', $_SERVER['REMOTE_ADDR'] ?? ''),
            ]);
        } catch (Throwable $e) {
            error_log('registrarEvento(' . $tipo . '): ' . $e->getMessage());
        }
    }

    public function kpisPeriodo(string $desde, string $hasta,
                                ?string $cmpDesde = null, ?string $cmpHasta = null): array {
        $dias = self::diasDelRango($desde, $hasta);
        [$antDesde, $antHasta] = ($cmpDesde && $cmpHasta)
            ? [$cmpDesde, $cmpHasta]
            : self::periodoAnterior($desde, $hasta);

        return [
            'actual'         => $this->totalesPeriodo($desde, $hasta),
            'anterior'       => $this->totalesPeriodo($antDesde, $antHasta),
            'dias'           => $dias,
            'anterior_desde' => $antDesde,
            'anterior_hasta' => $antHasta,
        ];
    }

    private function totalesPeriodo(string $desde, string $hasta): array {
        $ventas = Db::query(
            "SELECT COALESCE(SUM(fc.MontoTotal), 0) AS monto,
                    COUNT(fc.Id)                    AS ordenes
             FROM Comprobante fc
             JOIN EstadodePago ep ON ep.Id = fc.IdEstadoDePago
             WHERE fc.FechaBorrado IS NULL
               AND ep.Nombre = 'Aprobado'
               AND " . $this->entre('fc.FechaDeEmision'),
            [$desde, $hasta]
        )->fetch() ?: ['monto' => 0, 'ordenes' => 0];

        $visitas = (int) Db::query(
            "SELECT COUNT(DISTINCT SesionId) FROM VistasDePagina
             WHERE " . $this->entre('FechaRegistro') . "
               AND " . $this->sinPersonal(),
            [$desde, $hasta]
        )->fetchColumn();

        return [
            'ventas'  => (float)$ventas['monto'],
            'ordenes' => (int)$ventas['ordenes'],
            'visitas' => $visitas,
        ];
    }

    public function ventasPorDia(string $desde, string $hasta): array {
        $filas = Db::query(
            "SELECT
                DATE_FORMAT(fc.FechaDeEmision, '%Y-%m-%d') AS fecha,
                COALESCE(SUM(fc.MontoTotal), 0)            AS monto_total,
                COUNT(fc.Id)                               AS cantidad_ordenes
             FROM Comprobante fc
             JOIN EstadodePago ep ON ep.Id = fc.IdEstadoDePago
             WHERE fc.FechaBorrado IS NULL
               AND ep.Nombre = 'Aprobado'
               AND " . $this->entre('fc.FechaDeEmision') . "
             GROUP BY fecha
             ORDER BY fecha ASC",
            [$desde, $hasta]
        )->fetchAll();

        return $this->rellenarSerie($filas, $desde, $hasta, 'fecha',
            ['monto_total' => 0, 'cantidad_ordenes' => 0]);
    }

    public function productosMasVendidos(string $desde, string $hasta, int $limite = 10): array {
        return Db::query(
            "SELECT
                p.Id,
                p.NombredelProducto AS nombre,
                p.URLImagen          AS imagen,
                COALESCE(cat.Nombre, '—') AS categoria,
                SUM(pc.Cantidad)     AS unidades_vendidas,
                SUM(pc.Cantidad * COALESCE(pc.PrecioUnitario, p.PrecioVenta)) AS ingreso_total
             FROM Venta v
             JOIN Comprobante     fc  ON fc.Id = v.IdComprobante AND fc.FechaBorrado IS NULL
             JOIN EstadodePago    ep  ON ep.Id = fc.IdEstadoDePago AND ep.Nombre = 'Aprobado'
             JOIN ProductoCarrito pc  ON pc.IdCarrito = v.IdCarrito
             JOIN Producto        p   ON p.Id  = pc.IdProducto
             LEFT JOIN Categoria  cat ON cat.Id = p.IdCategoria
             WHERE v.FechaBorrado IS NULL
               AND p.FechaBorrado IS NULL
               AND " . $this->entre('fc.FechaDeEmision') . "
             GROUP BY p.Id
             ORDER BY unidades_vendidas DESC
             LIMIT ?",
            [$desde, $hasta, $limite]
        )->fetchAll();
    }

    public function paginasMasVisitadas(string $desde, string $hasta, int $limite = 10): array {
        return Db::query(
            "SELECT
                UrlVisitada AS url,
                Titulo      AS titulo,
                COUNT(*)    AS total_visitas,
                COUNT(DISTINCT SesionId) AS sesiones_unicas,
                AVG(TiempoEnPagina) AS tiempo_promedio_seg
             FROM VistasDePagina
             WHERE " . $this->entre('FechaRegistro') . "
               AND " . $this->sinPersonal() . "
             GROUP BY UrlVisitada, Titulo
             ORDER BY total_visitas DESC
             LIMIT ?",
            [$desde, $hasta, $limite]
        )->fetchAll();
    }

    public function visitasPorDia(string $desde, string $hasta): array {
        $filas = Db::query(
            "SELECT
                DATE_FORMAT(FechaRegistro, '%Y-%m-%d') AS fecha,
                COUNT(*)                 AS total_visitas,
                COUNT(DISTINCT SesionId) AS sesiones_unicas
             FROM VistasDePagina
             WHERE " . $this->entre('FechaRegistro') . "
               AND " . $this->sinPersonal() . "
             GROUP BY fecha
             ORDER BY fecha ASC",
            [$desde, $hasta]
        )->fetchAll();

        return $this->rellenarSerie($filas, $desde, $hasta, 'fecha',
            ['total_visitas' => 0, 'sesiones_unicas' => 0]);
    }

    public function visitasPorDispositivo(string $desde, string $hasta): array {
        return Db::query(
            "SELECT
                COALESCE(DispositivoTipo, 'desconocido') AS dispositivo,
                COUNT(*) AS total
             FROM VistasDePagina
             WHERE " . $this->entre('FechaRegistro') . "
               AND " . $this->sinPersonal() . "
             GROUP BY DispositivoTipo
             ORDER BY total DESC",
            [$desde, $hasta]
        )->fetchAll();
    }

    public function eventosPorTipo(string $desde, string $hasta): array {
        return Db::query(
            "SELECT
                TipoEvento AS tipo,
                COUNT(*)   AS total
             FROM EventosDeUsuario
             WHERE " . $this->entre('FechaRegistro') . "
               AND " . $this->sinPersonal() . "
             GROUP BY TipoEvento
             ORDER BY total DESC",
            [$desde, $hasta]
        )->fetchAll();
    }

    public function embudoConversion(string $desde, string $hasta): array {
        $visitas = (int) Db::query(
            "SELECT COUNT(DISTINCT SesionId) FROM VistasDePagina
             WHERE " . $this->entre('FechaRegistro') . "
               AND " . $this->sinPersonal(),
            [$desde, $hasta]
        )->fetchColumn();

        $sesionesConEvento = function (string $tipo) use ($desde, $hasta): int {
            return (int) Db::query(
                "SELECT COUNT(DISTINCT SesionId) FROM EventosDeUsuario
                 WHERE TipoEvento = ?
                   AND " . $this->entre('FechaRegistro') . "
                   AND " . $this->sinPersonal(),
                [$tipo, $desde, $hasta]
            )->fetchColumn();
        };
        $carritos  = $sesionesConEvento('add_carrito');
        $checkouts = $sesionesConEvento('checkout_paso');

        $compras = (int) Db::query(
            "SELECT COUNT(*) FROM Comprobante fc
             JOIN EstadodePago ep ON ep.Id = fc.IdEstadoDePago
             WHERE ep.Nombre = 'Aprobado'
               AND " . $this->entre('fc.FechaDeEmision') . "
               AND fc.FechaBorrado IS NULL
               AND fc.IdAutorizaciondePago IS NOT NULL
               AND (fc.IdClientes IS NULL OR fc.IdClientes NOT IN (" . self::CLIENTES_PERSONAL . "))",
            [$desde, $hasta]
        )->fetchColumn();

        return [
            ['etapa' => 'Visitas',   'cantidad' => $visitas],
            ['etapa' => 'Carrito',   'cantidad' => $carritos],
            ['etapa' => 'Checkout',  'cantidad' => $checkouts],
            ['etapa' => 'Compras',   'cantidad' => $compras],
        ];
    }

    public function terminosMasBuscados(string $desde, string $hasta, int $limite = 20): array {
        return Db::query(
            "SELECT
                TerminoBuscado AS termino,
                COUNT(*)       AS veces,
                AVG(CantidadResultados) AS resultados_promedio
             FROM Busquedas
             WHERE " . $this->entre('FechaRegistro') . "
               AND " . $this->sinPersonal() . "
             GROUP BY TerminoBuscado
             ORDER BY veces DESC
             LIMIT ?",
            [$desde, $hasta, $limite]
        )->fetchAll();
    }

    public function busquedasSinResultados(string $desde, string $hasta, int $limite = 20): array {
        return Db::query(
            "SELECT
                TerminoBuscado AS termino,
                COUNT(*) AS veces
             FROM Busquedas
             WHERE CantidadResultados = 0
               AND " . $this->entre('FechaRegistro') . "
               AND " . $this->sinPersonal() . "
             GROUP BY TerminoBuscado
             ORDER BY veces DESC
             LIMIT ?",
            [$desde, $hasta, $limite]
        )->fetchAll();
    }

    public function ventasPorTipoPago(string $desde, string $hasta): array {
        return Db::query(
            "SELECT
                tp.Nombre AS tipo_pago,
                COUNT(fc.Id) AS cantidad,
                SUM(fc.MontoTotal) AS monto_total
             FROM Comprobante fc
             JOIN TipodePago   tp ON tp.Id = fc.IdTipoDePago
             JOIN EstadodePago ep ON ep.Id = fc.IdEstadoDePago
             WHERE fc.FechaBorrado IS NULL
               AND ep.Nombre = 'Aprobado'
               AND " . $this->entre('fc.FechaDeEmision') . "
             GROUP BY tp.Nombre
             ORDER BY monto_total DESC",
            [$desde, $hasta]
        )->fetchAll();
    }

    public function estadoVentasPeriodo(string $desde, string $hasta): array {
        return Db::query(
            "SELECT
                ep.Nombre AS estado,
                COUNT(fc.Id) AS cantidad,
                COALESCE(SUM(fc.MontoTotal), 0) AS monto_total
             FROM Comprobante fc
             JOIN EstadodePago ep ON ep.Id = fc.IdEstadoDePago
             WHERE fc.FechaBorrado IS NULL
               AND " . $this->entre('fc.FechaDeEmision') . "
             GROUP BY ep.Nombre
             ORDER BY cantidad DESC",
            [$desde, $hasta]
        )->fetchAll();
    }

    public function ventasEvolucion(string $desde, string $hasta, ?bool $porMes = null): array {
        $porMes ??= self::diasDelRango($desde, $hasta) > 92;
        $fmt    = $porMes ? '%Y-%m' : '%Y-%m-%d';
        $filas = Db::query(
            "SELECT
                DATE_FORMAT(fc.FechaDeEmision, '$fmt') AS periodo,
                COUNT(fc.Id)                           AS ordenes,
                COALESCE(SUM(fc.MontoTotal), 0)        AS monto_total
             FROM Comprobante fc
             JOIN EstadodePago ep ON ep.Id = fc.IdEstadoDePago
             WHERE fc.FechaBorrado IS NULL
               AND ep.Nombre = 'Aprobado'
               AND " . $this->entre('fc.FechaDeEmision') . "
             GROUP BY periodo
             ORDER BY periodo ASC",
            [$desde, $hasta]
        )->fetchAll();

        return [
            'agrupado' => $porMes ? 'mes' : 'dia',
            'filas'    => $this->rellenarSerie($filas, $desde, $hasta, 'periodo',
                              ['ordenes' => 0, 'monto_total' => 0], $porMes),
        ];
    }

    public function topClientes(string $desde, string $hasta, int $limite = 10): array {
        return Db::query(
            "SELECT
                CONCAT(c.Nombre, ' ', c.Apellido) AS cliente,
                c.Id AS id_cliente,
                COUNT(fc.Id)                       AS ordenes,
                SUM(fc.MontoTotal)                 AS total_gastado
             FROM Clientes c
             JOIN Comprobante fc ON fc.IdClientes = c.Id
             JOIN EstadodePago   ep ON ep.Id = fc.IdEstadoDePago
             WHERE fc.FechaBorrado IS NULL
               AND ep.Nombre = 'Aprobado'
               AND c.FechaBorrado IS NULL
               AND " . $this->entre('fc.FechaDeEmision') . "
             GROUP BY c.Id
             ORDER BY total_gastado DESC
             LIMIT ?",
            [$desde, $hasta, $limite]
        )->fetchAll();
    }
}
