<?php


class StockDiagnosticoModel extends Model {

    protected $table = 'stock_diagnostico';

    /**
     * @param int|null $idUsuario  
     * @return array             
     */
    public function generarDiagnostico(?int $idUsuario = null): array {
        $contexto = $this->recopilarContexto();

        if (empty($contexto)) {
            return ['ok' => false, 'diagnostico' => null, 'error' => 'No hay datos de stock disponibles.'];
        }
        $respuestaIA = $this->llamarIA($contexto);

        if (!$respuestaIA['ok']) {
            return ['ok' => false, 'diagnostico' => null, 'error' => $respuestaIA['error']];
        }
        $diagnosticoJSON = $respuestaIA['json'];
        $metricas = $this->calcularMetricas($contexto);
        $resumenTexto = $diagnosticoJSON['resumen'] ?? 
                        ($diagnosticoJSON['alertas'][0]['descripcion'] ?? 'Diagnóstico generado.');
        $idGuardado = $this->guardarDiagnostico(
            $idUsuario,
            $metricas,
            json_encode($diagnosticoJSON, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            $resumenTexto
        );

        return [
            'ok'           => true,
            'id'           => $idGuardado,
            'diagnostico'  => $diagnosticoJSON,
            'metricas'     => $metricas,
            'error'        => null,
        ];
    }

    
    private function recopilarContexto(): array {
        $db = Db::getInstance()->getConnection();
        $maderas = $db->query("
            SELECT 
                s.Id            AS id_stock,
                s.Cantidad,
                s.PrecioUnitario,
                s.MontoTotal,
                s.FechaIngreso,
                tm.Nombre       AS tipo_madera,
                m.Alto, m.Largo, m.Ancho
            FROM stock s
            JOIN maderas m       ON m.Id = s.IdMaterial
            JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            WHERE s.TipoMaterial = 1
              AND s.FechaBorrado IS NULL
            ORDER BY s.Cantidad ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
        $insumos = $db->query("
            SELECT 
                s.Id            AS id_stock,
                s.Cantidad,
                s.PrecioUnitario,
                s.MontoTotal,
                s.FechaIngreso,
                ic.Descripcion  AS descripcion,
                tmm.Nombre      AS tipo_material
            FROM stock s
            JOIN insumosdecarpinteria ic ON ic.Id = s.IdMaterial
            JOIN tipodematerial tmm      ON tmm.Id = ic.IdTipodeMaterial
            WHERE s.TipoMaterial = 2
              AND s.FechaBorrado IS NULL
            ORDER BY s.Cantidad ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
        $consumoMaderas = $db->query("
            SELECT 
                tm.Nombre       AS tipo_madera,
                SUM(pm.CantidadNecesaria) AS cantidad_usada,
                SUM(pm.CostoTotal)        AS costo_total,
                COUNT(DISTINCT pm.IdProducto) AS productos_que_lo_usan
            FROM productomaderas pm
            JOIN maderas m       ON m.Id = pm.IdMadera
            JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            WHERE pm.FechaBorrado IS NULL
            GROUP BY tm.Nombre
            ORDER BY cantidad_usada DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        $consumoInsumos = $db->query("
            SELECT 
                ic.Descripcion  AS insumo,
                tmm.Nombre      AS tipo_material,
                SUM(pi.CantidadNecesaria) AS cantidad_usada,
                SUM(pi.CostoTotal)        AS costo_total,
                COUNT(DISTINCT pi.IdProducto) AS productos_que_lo_usan
            FROM productoinsumos pi
            JOIN insumosdecarpinteria ic ON ic.Id = pi.IdInsumoCarpinteria
            JOIN tipodematerial tmm      ON tmm.Id = ic.IdTipodeMaterial
            WHERE pi.FechaBorrado IS NULL
            GROUP BY ic.Id
            ORDER BY cantidad_usada DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        $historialPrecios = $db->query("
            SELECT 
                shp.TipoMaterial,
                shp.PrecioAnterior,
                shp.PrecioNuevo,
                shp.Variacion,
                shp.FechaRegistro,
                CASE shp.TipoMaterial
                    WHEN 1 THEN tm.Nombre
                    WHEN 2 THEN ic.Descripcion
                END AS nombre_material
            FROM stock_historial_precios shp
            LEFT JOIN maderas m            ON m.Id = shp.IdMaterial  AND shp.TipoMaterial = 1
            LEFT JOIN tipodemadera tm      ON tm.Id = m.IdTipodeMadera
            LEFT JOIN insumosdecarpinteria ic ON ic.Id = shp.IdMaterial AND shp.TipoMaterial = 2
            WHERE shp.PrecioAnterior IS NOT NULL
            ORDER BY shp.FechaRegistro DESC
            LIMIT 60
        ")->fetchAll(PDO::FETCH_ASSOC);
        $diagnosticoAnterior = $db->query("
            SELECT Id, FechaGenerado, ResumenTexto, VariacionPromedioPct
            FROM stock_diagnostico
            WHERE FechaBorrado IS NULL
            ORDER BY FechaGenerado DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);
        $ventasRecientes = $db->query("
            SELECT 
                p.NombredelProducto,
                COUNT(pc.Id) AS veces_vendido,
                SUM(pc.Cantidad) AS unidades_vendidas
            FROM productocarrito pc
            JOIN carrito c         ON c.Id = pc.IdCarrito AND c.Estado = 1
            JOIN producto p        ON p.Id = pc.IdProducto
            JOIN venta v           ON v.IdCarrito = c.Id
            JOIN facturacliente fc ON fc.Id = v.IdFacturaCliente
            JOIN estadodepago ep   ON ep.Id = fc.IdEstadodePago AND ep.Nombre = 'Aprobado'
            WHERE fc.FechaDeEmision >= DATE_SUB(NOW(), INTERVAL 30 DAY)
              AND p.FechaBorrado IS NULL
            GROUP BY p.Id
            ORDER BY unidades_vendidas DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        return [
            'maderas'             => $maderas,
            'insumos'             => $insumos,
            'consumo_maderas'     => $consumoMaderas,
            'consumo_insumos'     => $consumoInsumos,
            'historial_precios'   => $historialPrecios,
            'diagnostico_anterior'=> $diagnosticoAnterior,
            'ventas_recientes'    => $ventasRecientes,
            'fecha_consulta'      => date('Y-m-d H:i:s'),
        ];
    }

    private function llamarIA(array $contexto): array {
        $prompt = $this->construirPrompt($contexto);

        $payload = [
            'model'      => 'claude-sonnet-4-6',
            'max_tokens' => 4096,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ];

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'anthropic-version: 2023-06-01',
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 60,
        ]);

        $response = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ['ok' => false, 'error' => 'cURL error: ' . $curlError];
        }

        $data = json_decode($response, true);

        if ($httpCode !== 200 || empty($data['content'][0]['text'])) {
            $errMsg = $data['error']['message'] ?? 'Respuesta inesperada de la IA (HTTP ' . $httpCode . ')';
            return ['ok' => false, 'error' => $errMsg];
        }

        $textoIA = $data['content'][0]['text'];
        $textoIA = preg_replace('/^```json\s*/m', '', $textoIA);
        $textoIA = preg_replace('/```\s*$/m', '', $textoIA);
        $textoIA = trim($textoIA);

        $jsonDiagnostico = json_decode($textoIA, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $jsonDiagnostico = [
                'resumen'         => 'Diagnóstico generado.',
                'consumo'         => [],
                'precios'         => [],
                'alertas'         => [['nivel' => 'info', 'descripcion' => $textoIA]],
                'recomendaciones' => [],
            ];
        }

        return ['ok' => true, 'json' => $jsonDiagnostico];
    }

    private function construirPrompt(array $ctx): string {
        $fecha = $ctx['fecha_consulta'];
        $maDeraResumen = $this->resumirItems($ctx['maderas'], ['tipo_madera', 'Cantidad', 'PrecioUnitario', 'MontoTotal']);
        $insumoResumen = $this->resumirItems($ctx['insumos'], ['descripcion', 'tipo_material', 'Cantidad', 'PrecioUnitario', 'MontoTotal']);
        $consumoMResumen = $this->resumirItems($ctx['consumo_maderas'], ['tipo_madera', 'cantidad_usada', 'costo_total', 'productos_que_lo_usan']);
        $consumoIResumen = $this->resumirItems($ctx['consumo_insumos'], ['insumo', 'tipo_material', 'cantidad_usada', 'costo_total', 'productos_que_lo_usan']);
        $historialResumen = $this->resumirItems($ctx['historial_precios'], ['nombre_material', 'PrecioAnterior', 'PrecioNuevo', 'Variacion', 'FechaRegistro']);
        $ventasResumen = $this->resumirItems($ctx['ventas_recientes'], ['NombredelProducto', 'veces_vendido', 'unidades_vendidas']);

        $anteriorTxt = '';
        if (!empty($ctx['diagnostico_anterior'])) {
            $ant = $ctx['diagnostico_anterior'];
            $anteriorTxt = "DIAGNÓSTICO ANTERIOR (referencia):\n  Fecha: {$ant['FechaGenerado']}\n  Resumen: {$ant['ResumenTexto']}\n  Variación promedio de precios: {$ant['VariacionPromedioPct']}%\n\n";
        }
        $totalMaderas = count($ctx['maderas']);
        $totalInsumos = count($ctx['insumos']);
        $bajoStockM   = count(array_filter($ctx['maderas'],  fn($i) => (float)$i['Cantidad'] < 10));
        $bajoStockI   = count(array_filter($ctx['insumos'],  fn($i) => (float)$i['Cantidad'] < 10));
        $sinStockM    = count(array_filter($ctx['maderas'],  fn($i) => (float)$i['Cantidad'] <= 0));
        $sinStockI    = count(array_filter($ctx['insumos'],  fn($i) => (float)$i['Cantidad'] <= 0));

        return <<<PROMPT
Sos el analista de inventario y costos de una mueblería artesanal llamada San Plácido.
Fecha del análisis: {$fecha}.

Analizá el siguiente conjunto de datos de stock y generá un diagnóstico exhaustivo.
Respondé ÚNICAMENTE con un objeto JSON válido, sin texto adicional, con la estructura exacta indicada al final.

{$anteriorTxt}=== DATOS DE STOCK ===

RESUMEN GENERAL:
- Maderas en stock: {$totalMaderas} ítems | Con bajo stock (<10u): {$bajoStockM} | Sin stock: {$sinStockM}
- Insumos en stock: {$totalInsumos} ítems | Con bajo stock (<10u): {$bajoStockI} | Sin stock: {$sinStockI}

MADERAS ACTUALES:
{$maDeraResumen}

INSUMOS ACTUALES:
{$insumoResumen}

CONSUMO DE MADERAS POR PRODUCTOS (materiales usados en fabricación):
{$consumoMResumen}

CONSUMO DE INSUMOS POR PRODUCTOS:
{$consumoIResumen}

HISTORIAL DE CAMBIOS DE PRECIOS (vacío = primer diagnóstico, sin comparación):
{$historialResumen}

VENTAS ÚLTIMOS 30 DÍAS:
{$ventasResumen}

=== ESTRUCTURA JSON REQUERIDA ===
Devolvé exactamente este JSON (completá los arrays con los datos reales):

{
  "resumen": "Párrafo breve (2-3 oraciones) describiendo el estado general del stock.",
  "consumo": {
    "materiales_mas_usados": [
      {"nombre": "...", "tipo": "madera|insumo", "cantidad_usada": 0, "productos_dependientes": 0, "observacion": "..."}
    ],
    "materiales_sin_uso_en_productos": [
      {"nombre": "...", "tipo": "madera|insumo", "stock_actual": 0, "recomendacion": "..."}
    ]
  },
  "precios": {
    "sin_historial": true,
    "variaciones": [
      {"nombre": "...", "precio_anterior": 0, "precio_nuevo": 0, "variacion_pct": 0, "fecha": "...", "nivel": "bajo|moderado|alto"}
    ],
    "tendencia_general": "estable|inflacionaria|deflacionaria|sin datos",
    "variacion_promedio_pct": 0
  },
  "alertas": [
    {"nivel": "critico|advertencia|info", "tipo": "bajo_stock|sin_stock|aumento_precio|sin_rotacion", "material": "...", "detalle": "...", "stock_actual": 0, "stock_recomendado": 0}
  ],
  "recomendaciones": [
    {"prioridad": "alta|media|baja", "accion": "reponer|renegociar|liquidar|monitorear", "material": "...", "justificacion": "...", "cantidad_sugerida": 0}
  ],
  "indicadores": {
    "valor_total_stock": 0,
    "items_bajo_stock": 0,
    "items_sin_stock": 0,
    "items_sin_rotacion": 0,
    "alerta_general": "optimo|aceptable|critico"
  }
}
PROMPT;
    }

    private function resumirItems(array $items, array $campos): string {
        if (empty($items)) return "  (sin datos)\n";

        $lineas = [];
        foreach (array_slice($items, 0, 50) as $item) {
            $partes = [];
            foreach ($campos as $c) {
                if (isset($item[$c])) {
                    $partes[] = $c . ': ' . $item[$c];
                }
            }
            $lineas[] = '  - ' . implode(' | ', $partes);
        }

        return implode("\n", $lineas) . "\n";
    }

    private function calcularMetricas(array $ctx): array {
        $maderas = $ctx['maderas'];
        $insumos = $ctx['insumos'];
        $todos   = array_merge($maderas, $insumos);

        $valorTotal  = array_sum(array_column($todos, 'MontoTotal'));
        $bajoStock   = count(array_filter($todos, fn($i) => (float)$i['Cantidad'] > 0  && (float)$i['Cantidad'] < 10));
        $sinStock    = count(array_filter($todos, fn($i) => (float)$i['Cantidad'] <= 0));
        $variacionPct = null;
        if (!empty($ctx['historial_precios'])) {
            $vars = array_filter(
                array_column($ctx['historial_precios'], 'Variacion'),
                fn($v) => $v !== null
            );
            if (!empty($vars)) {
                $variacionPct = round(array_sum($vars) / count($vars), 2);
            }
        }

        return [
            'total_maderas'          => count($maderas),
            'total_insumos'          => count($insumos),
            'valor_total_stock'      => round($valorTotal, 2),
            'items_bajo_stock'       => $bajoStock,
            'items_sin_stock'        => $sinStock,
            'variacion_promedio_pct' => $variacionPct,
        ];
    }

    private function guardarDiagnostico(
        ?int $idUsuario,
        array $metricas,
        string $diagnosticoJSON,
        string $resumenTexto
    ): int {
        $db = Db::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO stock_diagnostico 
              (GeneradoPor, TotalMaderas, TotalInsumos, ValorTotalStock,
               ItemsBajoStock, ItemsSinStock, VariacionPromedioPct, DiagnosticoJSON, ResumenTexto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $idUsuario,
            $metricas['total_maderas'],
            $metricas['total_insumos'],
            $metricas['valor_total_stock'],
            $metricas['items_bajo_stock'],
            $metricas['items_sin_stock'],
            $metricas['variacion_promedio_pct'],
            $diagnosticoJSON,
            mb_substr($resumenTexto, 0, 500),
        ]);

        return (int) $db->lastInsertId();
    }

    
    public function listarDiagnosticos(int $limite = 20): array {
        $db = Db::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT 
                sd.Id,
                sd.FechaGenerado,
                sd.TotalMaderas,
                sd.TotalInsumos,
                sd.ValorTotalStock,
                sd.ItemsBajoStock,
                sd.ItemsSinStock,
                sd.VariacionPromedioPct,
                sd.ResumenTexto,
                u.NombredeUsuario AS generadoPorNombre
            FROM stock_diagnostico sd
            LEFT JOIN usuario u ON u.Id = sd.GeneradoPor
            WHERE sd.FechaBorrado IS NULL
            ORDER BY sd.FechaGenerado DESC
            LIMIT ?
        ");
        $stmt->execute([$limite]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function obtenerDiagnostico(int $id): ?array {
        $db = Db::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT * FROM stock_diagnostico
            WHERE Id = ? AND FechaBorrado IS NULL
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $row['DiagnosticoParseado'] = json_decode($row['DiagnosticoJSON'], true) ?? [];

        return $row;
    }

    
    public function obtenerUltimoDiagnostico(): ?array {
        $db = Db::getInstance()->getConnection();

        $row = $db->query("
            SELECT * FROM stock_diagnostico
            WHERE FechaBorrado IS NULL
            ORDER BY FechaGenerado DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $row['DiagnosticoParseado'] = json_decode($row['DiagnosticoJSON'], true) ?? [];

        return $row;
    }
}