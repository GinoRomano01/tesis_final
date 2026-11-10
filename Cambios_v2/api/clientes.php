<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$session = requireAuth(); 
$pdo = getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (!empty($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT c.*, s.nombre AS servicio_nombre
                                FROM clientes c LEFT JOIN servicios s ON s.id = c.servicio_id
                                WHERE c.id = ?');
        $stmt->execute([(int)$_GET['id']]);
        $cliente = $stmt->fetch();
        if (!$cliente) jsonResponse(['error' => 'No encontrado'], 404);
        jsonResponse($cliente);
    }

    $sql = 'SELECT c.*, s.nombre AS servicio_nombre
            FROM clientes c LEFT JOIN servicios s ON s.id = c.servicio_id';
    $params = [];
    if (!empty($_GET['estado'])) {
        $sql .= ' WHERE c.estado = ?';
        $params[] = $_GET['estado'];
    }
    $sql .= ' ORDER BY c.fecha_solicitud DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    jsonResponse($stmt->fetchAll());
}

if ($method === 'POST') {
    $body = readJsonBody();
    if (trim($body['nombre'] ?? '') === '') {
        jsonResponse(['error' => 'El nombre del cliente es obligatorio'], 400);
    }

    $stmt = $pdo->prepare('INSERT INTO clientes
        (nombre, telefono, email, domicilio, localidad, servicio_id, fecha_solicitud, estado, observaciones, registrado_por)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        trim($body['nombre']),
        $body['telefono'] ?? null,
        $body['email'] ?? null,
        $body['domicilio'] ?? null,
        $body['localidad'] ?? null,
        $body['servicio_id'] ?? null,
        $body['fecha_solicitud'] ?? date('Y-m-d H:i:s'),
        $body['estado'] ?? 'nuevo',
        $body['observaciones'] ?? null,
        $session['usuario_id'],
    ]);

    jsonResponse(['ok' => true, 'id' => (int)$pdo->lastInsertId()], 201);
}

if ($method === 'PUT') {
    $body = readJsonBody();
    $id = (int)($body['id'] ?? 0);
    if (!$id) jsonResponse(['error' => 'Falta id'], 400);

    $stmt = $pdo->prepare('UPDATE clientes SET nombre=?, telefono=?, email=?, domicilio=?, localidad=?,
                            servicio_id=?, estado=?, observaciones=? WHERE id=?');
    $stmt->execute([
        trim($body['nombre'] ?? ''),
        $body['telefono'] ?? null,
        $body['email'] ?? null,
        $body['domicilio'] ?? null,
        $body['localidad'] ?? null,
        $body['servicio_id'] ?? null,
        $body['estado'] ?? 'nuevo',
        $body['observaciones'] ?? null,
        $id,
    ]);
    jsonResponse(['ok' => true]);
}

if ($method === 'DELETE') {
    requireAdmin();
    $body = readJsonBody();
    $id = (int)($body['id'] ?? ($_GET['id'] ?? 0));
    if (!$id) jsonResponse(['error' => 'Falta id'], 400);

    $pdo->prepare('DELETE FROM clientes WHERE id = ?')->execute([$id]);
    jsonResponse(['ok' => true]);
}

jsonResponse(['error' => 'Método no permitido'], 405);
