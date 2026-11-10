<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$pdo = getConnection();
$stmt = $pdo->query('SELECT id, nombre, slug, descripcion FROM lineas ORDER BY id');
jsonResponse($stmt->fetchAll());
