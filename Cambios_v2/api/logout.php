<?php
require_once __DIR__ . '/../config/helpers.php';

startSecureSession();
$_SESSION = [];
session_destroy();

jsonResponse(['ok' => true]);
