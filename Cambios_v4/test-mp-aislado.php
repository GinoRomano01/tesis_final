<?php

require_once __DIR__ . '/app/config/core_config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== Datos de prueba oficiales de MP (tarjeta APRO) ===\n";

$paymentData = [
    'transaction_amount' => 100.00,
    'token'              => null,                                                     
    'payment_method_id'  => 'master',
    'installments'       => 1,
    'description'        => 'Test aislado San Placido',
    'payer'              => [
        'email'          => 'test@testuser.com',
        'identification' => ['type' => 'DNI', 'number' => '12345678'],
    ],
];

echo "PASO 1: Este script solo no puede generar el token de tarjeta (eso lo\n";
echo "hace el JS de Mercado Pago en el navegador, con Public Key). Pero SÍ\n";
echo "podemos confirmar dos cosas clave sin necesitar un token real:\n\n";
                                                                                           
if (!empty($_GET['token'])) {
    echo "=== TEST C: pago con el token REAL pasado por URL ===\n";
    $paymentData['token'] = trim($_GET['token']);
    if (!empty($_GET['email'])) {
        $paymentData['payer']['email'] = trim($_GET['email']);
    }

    $ch = curl_init('https://api.mercadopago.com/v1/payments');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,                                                                                
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($paymentData),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . MP_ACCESS_TOKEN,
            'Content-Type: application/json',
            'X-Idempotency-Key: ' . uniqid('', true),
        ],
        CURLOPT_TIMEOUT => 15,
    ]);
    $respCFull  = curl_exec($ch);
    $httpCodeC  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    $headersC = substr($respCFull, 0, $headerSize);
    $respC    = substr($respCFull, $headerSize);                                                              
                                                       
    preg_match_all('/^(x-request-id|x-trace-id|x-correlation-id|request-id):.*/mi', $headersC, $matchesId);

    echo "Payload enviado (email: {$paymentData['payer']['email']}): " . json_encode($paymentData) . "\n";
    echo "HTTP Code: $httpCodeC\n";
    echo "Headers relacionados a request-id encontrados:\n";
    echo (!empty($matchesId[0]) ? implode("\n", $matchesId[0]) : '(ninguno encontrado con esos nombres — pego todos los headers abajo por las dudas)') . "\n\n";
    echo "TODOS los headers de la respuesta:\n$headersC\n";
    echo "Respuesta completa:\n" . $respC . "\n\n";
    exit;
}
echo "(Para probar un token real: agregá ?token=EL_TOKEN_COMPLETO&email=test_payer_123456@testuser.com a esta URL — ver más abajo cómo conseguir el token)\n\n";
                                                                                           
echo "=== TEST A: ¿Access Token válido? (consulta /v1/payment_methods) ===\n";
$ch = curl_init('https://api.mercadopago.com/v1/payment_methods');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . MP_ACCESS_TOKEN],
    CURLOPT_TIMEOUT        => 15,
]);
$respA    = curl_exec($ch);
$httpCodeA = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$errnoA    = curl_errno($ch);
$errorA    = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCodeA\n";
echo "curl errno: $errnoA " . ($errorA ? "($errorA)" : '') . "\n";
echo "Respuesta (primeros 400 caracteres):\n" . substr($respA, 0, 400) . "\n\n";

if ($httpCodeA === 200) {
    echo "✅ El Access Token FUNCIONA — la cuenta/token están bien.\n\n";
} else {
    echo "❌ El Access Token dio un problema — HTTP $httpCodeA. Esto ya explicaría el error.\n\n";
}                                                                  
                                                                         
echo "=== TEST B: intento de pago con token de prueba genérico ===\n";
$paymentData['token'] = 'test_token_' . time();                          

$ch = curl_init('https://api.mercadopago.com/v1/payments');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($paymentData),
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . MP_ACCESS_TOKEN,
        'Content-Type: application/json',
        'X-Idempotency-Key: ' . uniqid('', true),
    ],
    CURLOPT_TIMEOUT => 15,
]);
$respB     = curl_exec($ch);
$httpCodeB = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$errnoB    = curl_errno($ch);
$errorB    = curl_error($ch);
curl_close($ch);

echo "Payload enviado: " . json_encode($paymentData) . "\n";
echo "HTTP Code: $httpCodeB\n";
echo "curl errno: $errnoB " . ($errorB ? "($errorB)" : '') . "\n";
echo "Respuesta completa:\n" . $respB . "\n\n";
echo "=== Cómo leer esto ===\n";
echo "- Si TEST B dice algo como 'invalid token' o 'cannot find resource\n";
echo "  card_token' con HTTP 400 -> MP está funcionando bien, el problema\n";
echo "  de antes era específico de un token real generado por el navegador.\n";
echo "- Si TEST B da el MISMO internal_error/500 que ya veníamos viendo ->\n";
echo "  el problema NO es el token, es algo previo (cuenta, formato,\n";
echo "  algo bloqueado del lado de MP) y hay que escalarlo a soporte de MP.\n";