<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

startSecureSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método no permitido'], 405);
}

$body = readJsonBody();
$email = trim($body['email'] ?? '');
$password = $body['password'] ?? '';

if ($email === '' || $password === '') {
    jsonResponse(['error' => 'Email y contraseña son obligatorios'], 400);
}

$pdo = getConnection();
$stmt = $pdo->prepare('SELECT id, nombre, email, password_hash, rol, activo FROM usuarios WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
    jsonResponse(['error' => 'Credenciales inválidas'], 401);
}

if ((int)$usuario['activo'] !== 1) {
    jsonResponse(['error' => 'Usuario deshabilitado'], 403);
}

session_regenerate_id(true);
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['nombre']     = $usuario['nombre'];
$_SESSION['rol']        = $usuario['rol'];

$update = $pdo->prepare('UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?');
$update->execute([$usuario['id']]);

jsonResponse([
    'ok' => true,
    'usuario' => [
        'id'     => $usuario['id'],
        'nombre' => $usuario['nombre'],
        'rol'    => $usuario['rol'],
    ],
]);
