<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$pdo = getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (!empty($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT s.*, l.slug AS linea_slug, l.nombre AS linea_nombre
                                FROM servicios s JOIN lineas l ON l.id = s.linea_id
                                WHERE s.id = ?');
        $stmt->execute([(int)$_GET['id']]);
        $servicio = $stmt->fetch();
        if (!$servicio) jsonResponse(['error' => 'No encontrado'], 404);

        $imgs = $pdo->prepare('SELECT imagen_url, orden FROM servicio_imagenes WHERE servicio_id = ? ORDER BY orden');
        $imgs->execute([$servicio['id']]);
        $servicio['imagenes'] = $imgs->fetchAll();
        jsonResponse($servicio);
    }

    $sql = 'SELECT s.*, l.slug AS linea_slug, l.nombre AS linea_nombre
            FROM servicios s JOIN lineas l ON l.id = s.linea_id
            WHERE s.activo = 1';
    $params = [];
    if (!empty($_GET['linea'])) {
        $sql .= ' AND l.slug = ?';
        $params[] = $_GET['linea'];
    }
    $sql .= ' ORDER BY s.orden ASC, s.id ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $servicios = $stmt->fetchAll();

    if ($servicios) {
        $ids = array_column($servicios, 'id');
        $in = implode(',', array_fill(0, count($ids), '?'));
        $imgStmt = $pdo->prepare("SELECT servicio_id, imagen_url, orden FROM servicio_imagenes
                                   WHERE servicio_id IN ($in) ORDER BY orden");
        $imgStmt->execute($ids);
        $porServicio = [];
        foreach ($imgStmt->fetchAll() as $row) {
            $porServicio[$row['servicio_id']][] = $row['imagen_url'];
        }
        foreach ($servicios as &$s) {
            $s['imagenes'] = $porServicio[$s['id']] ?? [];
        }
        unset($s);
    }

    jsonResponse($servicios);
}

// ---------------------------------------------------------
// A partir de acá: requiere sesión (admin o empleado)
// ---------------------------------------------------------
$session = requireAuth();

if ($method === 'POST') {
    $body = readJsonBody();
    $stmt = $pdo->prepare('INSERT INTO servicios (linea_id, nombre, descripcion, precio_desde, imagen_url, orden, creado_por)
                            VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        (int)($body['linea_id'] ?? 0),
        trim($body['nombre'] ?? ''),
        $body['descripcion'] ?? null,
        $body['precio_desde'] ?? null,
        $body['imagen_url'] ?? null,
        (int)($body['orden'] ?? 0),
        $session['usuario_id'],
    ]);
    $id = (int)$pdo->lastInsertId();

    if (!empty($body['imagenes']) && is_array($body['imagenes'])) {
        $imgStmt = $pdo->prepare('INSERT INTO servicio_imagenes (servicio_id, imagen_url, orden) VALUES (?, ?, ?)');
        foreach ($body['imagenes'] as $i => $url) {
            $imgStmt->execute([$id, $url, $i]);
        }
    }

    jsonResponse(['ok' => true, 'id' => $id], 201);
}

if ($method === 'PUT') {
    $body = readJsonBody();
    $id = (int)($body['id'] ?? 0);
    if (!$id) jsonResponse(['error' => 'Falta id'], 400);

    $stmt = $pdo->prepare('UPDATE servicios SET linea_id=?, nombre=?, descripcion=?, precio_desde=?,
                            imagen_url=?, orden=?, activo=? WHERE id=?');
    $stmt->execute([
        (int)($body['linea_id'] ?? 0),
        trim($body['nombre'] ?? ''),
        $body['descripcion'] ?? null,
        $body['precio_desde'] ?? null,
        $body['imagen_url'] ?? null,
        (int)($body['orden'] ?? 0),
        (int)($body['activo'] ?? 1),
        $id,
    ]);

    if (isset($body['imagenes']) && is_array($body['imagenes'])) {
        $pdo->prepare('DELETE FROM servicio_imagenes WHERE servicio_id = ?')->execute([$id]);
        $imgStmt = $pdo->prepare('INSERT INTO servicio_imagenes (servicio_id, imagen_url, orden) VALUES (?, ?, ?)');
        foreach ($body['imagenes'] as $i => $url) {
            $imgStmt->execute([$id, $url, $i]);
        }
    }

    jsonResponse(['ok' => true]);
}

if ($method === 'DELETE') {
    requireAdmin();
    $body = readJsonBody();
    $id = (int)($body['id'] ?? ($_GET['id'] ?? 0));
    if (!$id) jsonResponse(['error' => 'Falta id'], 400);

    $stmt = $pdo->prepare('UPDATE servicios SET activo = 0 WHERE id = ?');
    $stmt->execute([$id]);
    jsonResponse(['ok' => true]);
}

jsonResponse(['error' => 'Método no permitido'], 405);
