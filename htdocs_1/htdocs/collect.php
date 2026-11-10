<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');          
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST')   { http_response_code(405); exit; }


require_once __DIR__ . '/app/config/core_config.php';
require_once __DIR__ . '/app/classes/Db.php';


$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || !isset($data['tipo_evento'])) {
    http_response_code(400);
    echo json_encode(['error' => 'payload inválido']);
    exit;
}

$tipoEvento   = substr(trim($data['tipo_evento']   ?? ''), 0, 50);
$modulo       = substr(trim($data['modulo']        ?? ''), 0, 50)  ?: null;
$elementoId   = substr(trim($data['elemento_id']   ?? ''), 0, 100) ?: null;
$elementoTipo = substr(trim($data['elemento_tipo'] ?? ''), 0, 50)  ?: null;
$valorExtra   = isset($data['valor_extra']) ? substr($data['valor_extra'], 0, 500) : null;
$urlVisitada  = substr(trim($data['url_visitada']  ?? ''), 0, 500) ?: null;
$titulo       = substr(trim($data['titulo']        ?? ''), 0, 200) ?: null;
$referidor    = substr(trim($data['referidor']     ?? ''), 0, 500) ?: null;
$dispositivo  = substr(trim($data['dispositivo']   ?? ''), 0, 20)  ?: null;
$navegador    = substr(trim($data['navegador']     ?? ''), 0, 100) ?: null;
$tiempoEnPag  = isset($data['tiempo_en_pagina']) ? (int)$data['tiempo_en_pagina'] : null;

$idUsuario    = isset($data['usuario_id']) ? (int)$data['usuario_id'] ?: null : null;
$idCliente    = isset($data['cliente_id']) ? (int)$data['cliente_id'] ?: null : null;
$sesionId     = substr(trim($data['sesion_id'] ?? ''), 0, 100) ?: null;


$ipHash = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '');


try {
    switch ($tipoEvento) {

        case 'page_view':
            Db::query(
                "INSERT INTO VistasDePagina
                    (UrlVisitada, Titulo, Referidor, IdUsuario, IdCliente,
                     SesionId, DispositivoTipo, Navegador, IpHash)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$urlVisitada, $titulo, $referidor, $idUsuario, $idCliente,
                 $sesionId, $dispositivo, $navegador, $ipHash]
            );
            break;

        case 'tiempo_pagina':
            // Actualiza el último registro de esa URL/sesión con el tiempo real
            Db::query(
                "UPDATE VistasDePagina
                 SET TiempoEnPagina = ?
                 WHERE SesionId = ? AND UrlVisitada = ?
                 ORDER BY Id DESC LIMIT 1",
                [$tiempoEnPag, $sesionId, $urlVisitada]
            );
            break;

        case 'busqueda':
            $query = null;
            if ($valorExtra) {
                $vObj  = json_decode($valorExtra, true);
                $query = $vObj['query'] ?? $valorExtra;
            }
            if ($query) {
                Db::query(
                    "INSERT INTO Busquedas
                        (TerminoBuscado, IdUsuario, IdCliente, SesionId)
                     VALUES (?, ?, ?, ?)",
                    [substr($query, 0, 300), $idUsuario, $idCliente, $sesionId]
                );
            }
            break;

        default:
            
            Db::query(
                "INSERT INTO EventosDeUsuario
                    (TipoEvento, Modulo, ElementoId, ElementoTipo, ValorExtra,
                     IdUsuario, IdCliente, SesionId, IpHash)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$tipoEvento, $modulo, $elementoId, $elementoTipo, $valorExtra,
                 $idUsuario, $idCliente, $sesionId, $ipHash]
            );
            break;
    }

    http_response_code(200);
    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    error_log('collect.php ERROR: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'error interno']);
}
exit;