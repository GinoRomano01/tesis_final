<?php
require_once __DIR__ . '/../config/helpers.php';

startSecureSession();

if (empty($_SESSION['usuario_id'])) {
    jsonResponse(['autenticado' => false]);
}

jsonResponse([
    'autenticado' => true,
    'usuario' => [
        'id'     => $_SESSION['usuario_id'],
        'nombre' => $_SESSION['nombre'],
        'rol'    => $_SESSION['rol'],
    ],
]);
