<?php

function startSecureSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function jsonResponse($data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function requireAuth(): array {
    startSecureSession();
    if (empty($_SESSION['usuario_id'])) {
        jsonResponse(['error' => 'No autenticado'], 401);
    }
    return $_SESSION;
}

function requireAdmin(): array {
    $session = requireAuth();
    if (($session['rol'] ?? '') !== 'admin') {
        jsonResponse(['error' => 'No autorizado'], 403);
    }
    return $session;
}

function getClientIp(): string {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function readJsonBody(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}
