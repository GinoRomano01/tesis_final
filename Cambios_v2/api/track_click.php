<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método no permitido'], 405);
}

$body = readJsonBody();
$tipo = trim($body['tipo_elemento'] ?? '');
if ($tipo === '') jsonResponse(['error' => 'Falta tipo_elemento'], 400);

$pdo = getConnection();
$stmt = $pdo->prepare('INSERT INTO clicks (tipo_elemento, referencia_id, etiqueta, ip) VALUES (?, ?, ?, ?)');
$stmt->execute([
    $tipo,
    $body['referencia_id'] ?? null,
    $body['etiqueta'] ?? null,
    getClientIp(),
]);

jsonResponse(['ok' => true]);
