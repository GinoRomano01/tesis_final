<?php
require_once __DIR__ . '/../config/helpers.php';

requireAuth(); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['imagen'])) {
    jsonResponse(['error' => 'Falta el archivo imagen'], 400);
}

$archivo = $_FILES['imagen'];
$permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$maxBytes = 5 * 1024 * 1024; // 5MB

if ($archivo['error'] !== UPLOAD_ERR_OK) {
    jsonResponse(['error' => 'Error al subir el archivo'], 400);
}
if ($archivo['size'] > $maxBytes) {
    jsonResponse(['error' => 'El archivo supera los 5MB permitidos'], 400);
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $archivo['tmp_name']);
finfo_close($finfo);

if (!isset($permitidos[$mime])) {
    jsonResponse(['error' => 'Formato no permitido (solo JPG, PNG o WEBP)'], 400);
}

$carpeta = __DIR__ . '/../uploads/servicios/';
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0755, true);
}

$nombreArchivo = uniqid('srv_', true) . '.' . $permitidos[$mime];
$rutaDestino = $carpeta . $nombreArchivo;

if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
    jsonResponse(['error' => 'No se pudo guardar el archivo'], 500);
}

jsonResponse([
    'ok'  => true,
    'url' => 'uploads/servicios/' . $nombreArchivo,
]);
