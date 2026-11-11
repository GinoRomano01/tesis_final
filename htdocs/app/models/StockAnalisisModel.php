<?php

class StockAnalisisModel {

    private $db;

    public function __construct() {
        $this->db = Db::getInstance()->getConnection();
    }

    public function generarDiagnostico($idUsuario) {
        try {
            $metricas = $this->recolectarMetricas();

            $analisisIA = $this->llamarIA($metricas);

            if (!$analisisIA['success']) {
                return [
                    'success' => false,
                    'id'      => null,
                    'error'   => 'Error IA: ' . $analisisIA['error']
                ];
            }

            $idDiagnostico = $this->guardar($metricas, $analisisIA['data'], $idUsuario);

            return [
                'success' => true,
                'id'      => $idDiagnostico,
                'error'   => null
            ];

        } catch (Exception $e) {
            error_log('StockAnalisisModel::generarDiagnostico — ' . $e->getMessage());
            return [
                'success' => false,
                'id'      => null,
                'error'   => $e->getMessage()
            ];
        }
    }

    public function obtenerDiagnostico($id) {
        $stmt = $this->db->prepare("
            SELECT sd.*, u.NombredeUsuario AS NombreUsuario
            FROM StockDiagnostico sd
            LEFT JOIN Usuario u ON u.Id = sd.GeneradoPor
            WHERE sd.Id = ? AND sd.FechaBorrado IS NULL
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            $row['DiagnosticoData'] = json_decode($row['DiagnosticoJSON'], true);
        }
        return $row;
    }

    public function listarHistorial($limit = 20) {
        $stmt = $this->db->prepare("
            SELECT sd.Id, sd.FechaGenerado, sd.ValorTotalStock,
                   sd.ItemsBajoStock, sd.ItemsSinStock, sd.VariacionPromedioPct,
                   sd.ResumenTexto, u.NombredeUsuario AS NombreUsuario
            FROM StockDiagnostico sd
            LEFT JOIN Usuario u ON u.Id = sd.GeneradoPor
            WHERE sd.FechaBorrado IS NULL
            ORDER BY sd.FechaGenerado DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function recolectarMetricas() {
        return [
            'generado_en'         => date('Y-m-d H:i:s'),
            'resumen_general'     => $this->metricaResumenGeneral(),
            'inflacion_precios'   => $this->metricaInflacionPrecios(),
            'top_consumidos'      => $this->metricaTopConsumidos(),
            'materiales_muertos'  => $this->metricaMaterialesMuertos(),
            'impacto_margen'      => $this->metricaImpactoMargen(),
            'valorizacion_stock'  => $this->metricaValorizacionStock(),
        ];
    }

    private function metricaResumenGeneral() {

        $sql = "
            SELECT
                s.TipoMaterial,
                s.IdMaterial,
                SUM(s.Cantidad) AS CantidadTotal,
                CASE 
                    WHEN s.TipoMaterial = 1 THEN m.PrecioUnitario
                    WHEN s.TipoMaterial = 2 THEN i.PrecioUnitario
                END AS PrecioUnitario,
                CASE 
                    WHEN s.TipoMaterial = 1 THEN CONCAT('Madera #', m.Id, ' (', tm.Nombre, ')')
                    WHEN s.TipoMaterial = 2 THEN i.Descripcion
                END AS Nombre,
                CASE
                    WHEN s.TipoMaterial = 1 THEN uf.StockMinimo
                    WHEN s.TipoMaterial = 2 THEN tc.StockMinimo
                END AS StockMinimo,
                CASE
                    WHEN s.TipoMaterial = 1 THEN uf.StockAceptable
                    WHEN s.TipoMaterial = 2 THEN tc.StockAceptable
                END AS StockAceptable,
                CASE
                    WHEN s.TipoMaterial = 1 THEN m.Formato
                    WHEN s.TipoMaterial = 2 THEN tc.Nombre
                END AS Categoria
            FROM stock s
            LEFT JOIN maderas m ON s.TipoMaterial = 1 AND m.Id = s.IdMaterial AND m.FechaBorrado IS NULL
            LEFT JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            LEFT JOIN UmbralFormatoMadera uf ON uf.Formato = m.Formato
            LEFT JOIN insumosdecarpinteria i ON s.TipoMaterial = 2 AND i.Id = s.IdMaterial AND i.FechaBorrado IS NULL
            LEFT JOIN tipodecorte tc ON tc.Id = i.IdTipodeCorte
            WHERE s.FechaBorrado IS NULL
            GROUP BY s.TipoMaterial, s.IdMaterial
        ";
        $rows = $this->db->query($sql)->fetchAll();

        $totalMaderas = 0; $totalInsumos = 0;
        $valorTotal = 0.0; $valorMaderas = 0.0; $valorInsumos = 0.0;
        $itemsBajo = 0; $itemsSin = 0;
        $detalleBajo = []; $detalleSin = [];
        $sinFallback = STOCK_UMBRAL_SIN;

        foreach ($rows as $r) {
            $cant   = (float)$r['CantidadTotal'];
            $precio = (float)$r['PrecioUnitario'];
            $valor  = $cant * $precio;

            $minFallback = $r['TipoMaterial'] == 1
                ? STOCK_UMBRAL_MADERAS_FALLBACK
                : STOCK_UMBRAL_INSUMOS_FALLBACK;

            $stockMinimo = $r['StockMinimo'] !== null ? (float)$r['StockMinimo'] : $minFallback;

            $valorTotal += $valor;

            if ($r['TipoMaterial'] == 1) {
                $totalMaderas++;
                $valorMaderas += $valor;
            } else {
                $totalInsumos++;
                $valorInsumos += $valor;
            }

            if ($cant <= $sinFallback) {
                $itemsSin++;
                $detalleSin[] = ['nombre' => $r['Nombre'], 'categoria' => $r['Categoria'], 'cantidad' => $cant];
            } elseif ($cant < $stockMinimo) {
                $itemsBajo++;
                $detalleBajo[] = [
                    'nombre'         => $r['Nombre'],
                    'categoria'      => $r['Categoria'],
                    'cantidad'       => $cant,
                    'stock_minimo'   => $stockMinimo,
                    'stock_aceptable'=> (float)($r['StockAceptable'] ?? 0),
                ];
            }
        }

        return [
            'total_maderas'   => $totalMaderas,
            'total_insumos'   => $totalInsumos,
            'valor_total'     => round($valorTotal, 2),
            'valor_maderas'   => round($valorMaderas, 2),
            'valor_insumos'   => round($valorInsumos, 2),
            'items_bajo'      => $itemsBajo,
            'items_sin'       => $itemsSin,
            'detalle_bajo'    => $detalleBajo,
            'detalle_sin'     => $detalleSin,
        ];
    }

    private function metricaInflacionPrecios() {
        $sql = "
            SELECT 
                shp.TipoMaterial,
                shp.IdMaterial,
                CASE 
                    WHEN shp.TipoMaterial = 1 THEN CONCAT('Madera ', tm.Nombre, ' #', m.Id)
                    WHEN shp.TipoMaterial = 2 THEN i.Descripcion
                END AS Nombre,
                shp.PrecioAnterior,
                shp.PrecioNuevo,
                shp.Variacion,
                shp.FechaRegistro
            FROM StockHistorialPrecios shp
            LEFT JOIN maderas m ON shp.TipoMaterial = 1 AND m.Id = shp.IdMaterial
            LEFT JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            LEFT JOIN insumosdecarpinteria i ON shp.TipoMaterial = 2 AND i.Id = shp.IdMaterial
            WHERE shp.FechaRegistro >= DATE_SUB(NOW(), INTERVAL 90 DAY)
              AND shp.PrecioAnterior IS NOT NULL
              AND shp.PrecioAnterior > 0
            ORDER BY shp.FechaRegistro DESC
        ";
        $rows = $this->db->query($sql)->fetchAll();

        $variaciones30 = []; $variaciones60 = []; $variaciones90 = [];
        $hace30 = strtotime('-30 days');
        $hace60 = strtotime('-60 days');

        foreach ($rows as $r) {
            $ts = strtotime($r['FechaRegistro']);
            $variaciones90[] = (float)$r['Variacion'];
            if ($ts >= $hace60) $variaciones60[] = (float)$r['Variacion'];
            if ($ts >= $hace30) $variaciones30[] = (float)$r['Variacion'];
        }

        $topSubas = array_slice(
            array_filter($rows, fn($r) => (float)$r['Variacion'] > 0),
            0, 5
        );
        usort($topSubas, fn($a, $b) => (float)$b['Variacion'] <=> (float)$a['Variacion']);
        $topSubas = array_slice($topSubas, 0, 5);

        return [
            'cambios_totales_90d' => count($variaciones90),
            'cambios_30d'         => count($variaciones30),
            'cambios_60d'         => count($variaciones60),
            'promedio_30d'        => count($variaciones30) ? round(array_sum($variaciones30) / count($variaciones30), 2) : 0,
            'promedio_60d'        => count($variaciones60) ? round(array_sum($variaciones60) / count($variaciones60), 2) : 0,
            'promedio_90d'        => count($variaciones90) ? round(array_sum($variaciones90) / count($variaciones90), 2) : 0,
            'top_subas'           => array_map(fn($r) => [
                'nombre'           => $r['Nombre'],
                'precio_anterior'  => (float)$r['PrecioAnterior'],
                'precio_nuevo'     => (float)$r['PrecioNuevo'],
                'variacion_pct'    => (float)$r['Variacion'],
                'fecha'            => $r['FechaRegistro'],
            ], $topSubas),
        ];
    }

    private function metricaTopConsumidos() {
        $sqlMaderas = "
            SELECT 
                tm.Nombre AS TipoMadera,
                m.Id AS IdMadera,
                CONCAT(m.Alto, 'x', m.Ancho, 'x', m.Largo) AS Dimensiones,
                SUM(pm.CantidadNecesaria * pc.Cantidad) AS CantidadConsumida,
                SUM(pm.CostoTotal * pc.Cantidad) AS CostoTotalConsumido,
                COUNT(DISTINCT v.Id) AS VecesVendido
            FROM Venta v
            JOIN Carrito c ON c.Id = v.IdCarrito AND c.Estado = 1
            JOIN ProductoCarrito pc ON pc.IdCarrito = c.Id
            JOIN ProductoMaderas pm ON pm.IdProducto = pc.IdProducto AND pm.FechaBorrado IS NULL
            JOIN maderas m ON m.Id = pm.IdMadera
            JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            WHERE v.FechaBorrado IS NULL
            GROUP BY m.Id
            ORDER BY CantidadConsumida DESC
            LIMIT 10
        ";

        $sqlInsumos = "
            SELECT 
                i.Id AS IdInsumo,
                i.Descripcion AS Nombre,
                tmat.Nombre AS TipoMaterial,
                SUM(pi.CantidadNecesaria * pc.Cantidad) AS CantidadConsumida,
                SUM(pi.CostoTotal * pc.Cantidad) AS CostoTotalConsumido,
                COUNT(DISTINCT v.Id) AS VecesVendido
            FROM Venta v
            JOIN Carrito c ON c.Id = v.IdCarrito AND c.Estado = 1
            JOIN ProductoCarrito pc ON pc.IdCarrito = c.Id
            JOIN ProductoInsumos pi ON pi.IdProducto = pc.IdProducto AND pi.FechaBorrado IS NULL
            JOIN insumosdecarpinteria i ON i.Id = pi.IdInsumoCarpinteria
            LEFT JOIN tipodematerial tmat ON tmat.Id = i.IdTipodeMaterial
            WHERE v.FechaBorrado IS NULL
            GROUP BY i.Id
            ORDER BY CantidadConsumida DESC
            LIMIT 10
        ";

        return [
            'top_maderas' => $this->db->query($sqlMaderas)->fetchAll(),
            'top_insumos' => $this->db->query($sqlInsumos)->fetchAll(),
        ];
    }

    private function metricaMaterialesMuertos() {
        $dias = DIAS_MATERIAL_MUERTO;

        $sqlMaderas = "
            SELECT 
                m.Id,
                CONCAT(tm.Nombre, ' ', m.Alto, 'x', m.Ancho, 'x', m.Largo) AS Nombre,
                m.PrecioUnitario,
                COALESCE(SUM(s.Cantidad), 0) AS StockActual,
                COALESCE(SUM(s.Cantidad), 0) * m.PrecioUnitario AS CapitalInmovilizado
            FROM maderas m
            JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            LEFT JOIN stock s ON s.TipoMaterial = 1 AND s.IdMaterial = m.Id AND s.FechaBorrado IS NULL
            WHERE m.FechaBorrado IS NULL
              AND m.Id NOT IN (
                  SELECT DISTINCT pm.IdMadera
                  FROM ProductoMaderas pm
                  JOIN ProductoCarrito pc ON pc.IdProducto = pm.IdProducto
                  JOIN Carrito c ON c.Id = pc.IdCarrito AND c.Estado = 1
                  JOIN Venta v ON v.IdCarrito = c.Id
                  JOIN Comprobante fc ON fc.Id = v.IdComprobante
                  WHERE fc.FechaDeEmision >= DATE_SUB(NOW(), INTERVAL ? DAY)
                    AND pm.FechaBorrado IS NULL
              )
            GROUP BY m.Id
            HAVING StockActual > 0
            ORDER BY CapitalInmovilizado DESC
            LIMIT 15
        ";
        $stmt = $this->db->prepare($sqlMaderas);
        $stmt->execute([$dias]);
        $maderasMuertas = $stmt->fetchAll();

        $sqlInsumos = "
            SELECT 
                i.Id,
                i.Descripcion AS Nombre,
                i.PrecioUnitario,
                COALESCE(SUM(s.Cantidad), 0) AS StockActual,
                COALESCE(SUM(s.Cantidad), 0) * i.PrecioUnitario AS CapitalInmovilizado
            FROM insumosdecarpinteria i
            LEFT JOIN stock s ON s.TipoMaterial = 2 AND s.IdMaterial = i.Id AND s.FechaBorrado IS NULL
            WHERE i.FechaBorrado IS NULL
              AND i.Id NOT IN (
                  SELECT DISTINCT pi.IdInsumoCarpinteria
                  FROM ProductoInsumos pi
                  JOIN ProductoCarrito pc ON pc.IdProducto = pi.IdProducto
                  JOIN Carrito c ON c.Id = pc.IdCarrito AND c.Estado = 1
                  JOIN Venta v ON v.IdCarrito = c.Id
                  JOIN Comprobante fc ON fc.Id = v.IdComprobante
                  WHERE fc.FechaDeEmision >= DATE_SUB(NOW(), INTERVAL ? DAY)
                    AND pi.FechaBorrado IS NULL
              )
            GROUP BY i.Id
            HAVING StockActual > 0
            ORDER BY CapitalInmovilizado DESC
            LIMIT 15
        ";
        $stmt = $this->db->prepare($sqlInsumos);
        $stmt->execute([$dias]);
        $insumosMuertos = $stmt->fetchAll();

        $capitalInmovilizado = 0;
        foreach ($maderasMuertas as $m) $capitalInmovilizado += (float)$m['CapitalInmovilizado'];
        foreach ($insumosMuertos as $i) $capitalInmovilizado += (float)$i['CapitalInmovilizado'];

        return [
            'dias_umbral'              => $dias,
            'maderas_muertas'          => $maderasMuertas,
            'insumos_muertos'          => $insumosMuertos,
            'capital_inmovilizado_total' => round($capitalInmovilizado, 2),
        ];
    }

    private function metricaImpactoMargen() {
        $sql = "
            SELECT 
                p.Id,
                p.NombredelProducto,
                p.CostoTotalMateriales AS CostoOriginal,
                p.PrecioVenta,
                p.PorcentajeGanancia,
                (
                    COALESCE((
                        SELECT SUM(pm.CantidadNecesaria * m.PrecioUnitario)
                        FROM ProductoMaderas pm
                        JOIN maderas m ON m.Id = pm.IdMadera
                        WHERE pm.IdProducto = p.Id AND pm.FechaBorrado IS NULL
                    ), 0)
                    +
                    COALESCE((
                        SELECT SUM(pi.CantidadNecesaria * i.PrecioUnitario)
                        FROM ProductoInsumos pi
                        JOIN insumosdecarpinteria i ON i.Id = pi.IdInsumoCarpinteria
                        WHERE pi.IdProducto = p.Id AND pi.FechaBorrado IS NULL
                    ), 0)
                ) AS CostoActualReal
            FROM Producto p
            WHERE p.FechaBorrado IS NULL
        ";
        $rows = $this->db->query($sql)->fetchAll();

        $afectados = [];
        foreach ($rows as $r) {
            $costoOriginal = (float)$r['CostoOriginal'];
            $costoActual   = (float)$r['CostoActualReal'];
            $precioVenta   = (float)$r['PrecioVenta'];

            if ($costoOriginal <= 0 || $precioVenta <= 0) continue;

            $varCosto = (($costoActual - $costoOriginal) / $costoOriginal) * 100;
            $margenOriginal = (($precioVenta - $costoOriginal) / $precioVenta) * 100;
            $margenActual   = (($precioVenta - $costoActual) / $precioVenta) * 100;
            $perdidaMargen  = $margenOriginal - $margenActual;

            if ($varCosto > 1) {
                $afectados[] = [
                    'id'              => (int)$r['Id'],
                    'nombre'          => $r['NombredelProducto'],
                    'costo_original'  => round($costoOriginal, 2),
                    'costo_actual'    => round($costoActual, 2),
                    'precio_venta'    => round($precioVenta, 2),
                    'variacion_costo' => round($varCosto, 2),
                    'margen_original' => round($margenOriginal, 2),
                    'margen_actual'   => round($margenActual, 2),
                    'perdida_margen'  => round($perdidaMargen, 2),
                ];
            }
        }

        usort($afectados, fn($a, $b) => $b['perdida_margen'] <=> $a['perdida_margen']);

        return [
            'productos_afectados' => array_slice($afectados, 0, 10),
            'total_afectados'     => count($afectados),
        ];
    }

    private function metricaValorizacionStock() {
        $sql = "
            SELECT 
                'madera' AS Tipo,
                m.Id AS IdMaterial,
                CONCAT(tm.Nombre, ' ', m.Alto, 'x', m.Ancho, 'x', m.Largo) AS Nombre,
                COALESCE(SUM(s.Cantidad), 0) AS Cantidad,
                m.PrecioUnitario,
                COALESCE(SUM(s.Cantidad), 0) * m.PrecioUnitario AS ValorTotal
            FROM maderas m
            JOIN tipodemadera tm ON tm.Id = m.IdTipodeMadera
            LEFT JOIN stock s ON s.TipoMaterial = 1 AND s.IdMaterial = m.Id AND s.FechaBorrado IS NULL
            WHERE m.FechaBorrado IS NULL
            GROUP BY m.Id
            
            UNION ALL
            
            SELECT 
                'insumo' AS Tipo,
                i.Id AS IdMaterial,
                i.Descripcion AS Nombre,
                COALESCE(SUM(s.Cantidad), 0) AS Cantidad,
                i.PrecioUnitario,
                COALESCE(SUM(s.Cantidad), 0) * i.PrecioUnitario AS ValorTotal
            FROM insumosdecarpinteria i
            LEFT JOIN stock s ON s.TipoMaterial = 2 AND s.IdMaterial = i.Id AND s.FechaBorrado IS NULL
            WHERE i.FechaBorrado IS NULL
            GROUP BY i.Id
            
            ORDER BY ValorTotal DESC
            LIMIT 10
        ";
        return [
            'top_valor' => $this->db->query($sql)->fetchAll(),
        ];
    }

    private function llamarIA($metricas) {
        $prompt = $this->construirPrompt($metricas);

        $payload = [
            'model'    => GROQ_MODEL,
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => 'Sos un analista de negocio especializado en carpintería a pedido. '
                               . 'Recibís métricas reales de stock y generás recomendaciones accionables en español rioplatense. '
                               . 'NUNCA inventes números, usá solo los que te paso. '
                               . 'Sé directo, sin relleno. Priorizá lo accionable sobre lo descriptivo. '
                               . 'Tu respuesta debe ser SOLO un JSON válido con la estructura solicitada, sin markdown ni explicaciones extra.'
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature'     => 0.3,
            'response_format' => ['type' => 'json_object'],
        ];

        $ch = curl_init(GROQ_API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . GROQ_API_KEY,
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 60,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return ['success' => false, 'data' => null, 'error' => 'cURL: ' . $err];
        }
        if ($httpCode !== 200) {
            return ['success' => false, 'data' => null, 'error' => 'HTTP ' . $httpCode . ': ' . $response];
        }

        $decoded = json_decode($response, true);
        $contenido = $decoded['choices'][0]['message']['content'] ?? null;

        if (!$contenido) {
            return ['success' => false, 'data' => null, 'error' => 'Respuesta IA vacía'];
        }

        $analisis = json_decode($contenido, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'data' => null, 'error' => 'JSON IA inválido: ' . json_last_error_msg()];
        }

        return ['success' => true, 'data' => $analisis, 'error' => null];
    }

    private function construirPrompt($metricas) {
        $json = json_encode($metricas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Analizá las siguientes métricas de stock de una carpintería que trabaja a pedido. Generá un diagnóstico ÚTIL y ACCIONABLE.

IMPORTANTE: cada material tiene su propio umbral de stock mínimo y aceptable
según su categoría (tipo de insumo o formato de madera) — no es un umbral
único genérico. Los arrays 'detalle_bajo' y 'detalle_sin' dentro de
'resumen_general' ya te dicen, para cada material puntual, su categoría
(Categoria), cuánto tiene (cantidad) y cuánto debería tener como mínimo
(stock_minimo). Usá esos datos exactos al armar las alertas y
recomendaciones — mencioná la categoría del material, no solo su nombre,
para que quede claro POR QUÉ ese nivel de stock es bajo para ese tipo
particular de insumo/madera.

MÉTRICAS REALES (NO INVENTES, usá exactamente estos números):
{$json}

Devolvé un JSON con esta estructura EXACTA:

{
  "resumen_ejecutivo": "Párrafo de 2-3 oraciones con el estado general del stock. Mencioná el valor total, items críticos y la situación inflacionaria.",
  "alertas_criticas": [
    "Cada string es una alerta concreta y accionable. Máximo 5. Si no hay alertas, array vacío."
  ],
  "analisis_inflacion": "Análisis de la variación de precios. Mencioná los materiales que más subieron y el impacto general.",
  "recomendaciones_reposicion": [
    "Lista priorizada de qué materiales reponer y por qué. Basate en top_consumidos. Máximo 5 items."
  ],
  "materiales_muertos_recomendacion": "Qué hacer con el capital inmovilizado en materiales sin rotación. Sugerí acciones concretas (liquidación, uso en productos nuevos, etc).",
  "impacto_pricing": "Productos que necesitan actualización de precio porque sus costos subieron. Sé específico con nombres.",
  "puntaje_salud_stock": 75,
  "prioridad_inmediata": "La acción más importante que el dueño debería hacer ESTA SEMANA."
}

El puntaje_salud_stock va de 0 a 100. Considerá: items sin stock (resta mucho), inflación reciente (resta), materiales muertos (resta), margen comprimido (resta).
PROMPT;
    }

    private function guardar($metricas, $analisisIA, $idUsuario) {
        $resumen = $metricas['resumen_general'];

        $payloadCompleto = [
            'metricas' => $metricas,
            'analisis' => $analisisIA,
        ];

        $stmt = $this->db->prepare("
            INSERT INTO StockDiagnostico (
                FechaGenerado, GeneradoPor,
                TotalMaderas, TotalInsumos, ValorTotalStock,
                ItemsBajoStock, ItemsSinStock, VariacionPromedioPct,
                DiagnosticoJSON, ResumenTexto
            ) VALUES (
                NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $idUsuario ?: null,
            $resumen['total_maderas'],
            $resumen['total_insumos'],
            $resumen['valor_total'],
            $resumen['items_bajo'],
            $resumen['items_sin'],
            $metricas['inflacion_precios']['promedio_30d'],
            json_encode($payloadCompleto, JSON_UNESCAPED_UNICODE),
            $analisisIA['resumen_ejecutivo'] ?? '',
        ]);

        return (int)$this->db->lastInsertId();
    }
}