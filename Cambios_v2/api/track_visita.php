<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método no permitido'], 405);
}

$body = readJsonBody();
$pagina = trim($body['pagina'] ?? '');
if ($pagina === '') jsonResponse(['error' => 'Falta pagina'], 400);

$pdo = getConnection();
$stmt = $pdo->prepare('INSERT INTO visitas (pagina, ip, user_agent, referer) VALUES (?, ?, ?, ?)');
$stmt->execute([
    $pagina,
    getClientIp(),
    $_SERVER['HTTP_USER_AGENT'] ?? null,
    $body['referer'] ?? ($_SERVER['HTTP_REFERER'] ?? null),
]);

jsonResponse(['ok' => true]);
