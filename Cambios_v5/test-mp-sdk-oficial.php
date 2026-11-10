<?php

require_once __DIR__ . '/app/config/core_config.php';
                                                                   
function buscarCarpetaSDK(string $dir): ?string {
    $items = @scandir($dir);
    if (!$items) return null;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_file($path) && $item === 'MercadoPagoConfig.php') {
            return $dir;                                                         
        }
        if (is_dir($path)) {
            $r = buscarCarpetaSDK($path);
            if ($r) return $r;
        }
    }
    return null;
}

$baseEncontrada = buscarCarpetaSDK(__DIR__ . '/vendor');

if (!$baseEncontrada) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "No encontré MercadoPagoConfig.php en ningún lado dentro de vendor/.\n";
    echo "Fijate en tu file manager la ruta completa donde quedó ese archivo\n";
    echo "puntual y pasámela.\n";
    exit;
}

spl_autoload_register(function ($class) use ($baseEncontrada) {
    $prefix = 'MercadoPago\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $file = $baseEncontrada . '/' . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) require $file;
});

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

header('Content-Type: text/plain; charset=utf-8');
echo "PHP version: " . PHP_VERSION . "\n";
echo "Carpeta del SDK encontrada en: $baseEncontrada\n\n";

if (empty($_GET['token'])) {
    echo "Uso: ?token=TOKEN&email=MAIL&issuer_id=3&payment_method_id=master\n";
    exit;
}

MercadoPagoConfig::setAccessToken(MP_ACCESS_TOKEN);

$request = [
    'transaction_amount' => 100,
    'token'              => trim($_GET['token']),
    'description'        => 'Test SDK oficial San Placido',
    'installments'       => 1,
    'payment_method_id'  => $_GET['payment_method_id'] ?? 'master',
    'payer'              => [
        'email' => $_GET['email'] ?? 'gino.crack.carp@gmail.com',
        'identification' => ['type' => 'DNI', 'number' => '12345678'],
    ],
];
if (!empty($_GET['issuer_id'])) {
    $request['issuer_id'] = (int)$_GET['issuer_id'];
}

echo "=== Prueba con el SDK OFICIAL v3.0.0 de Mercado Pago ===\n";
echo "Request armado: " . json_encode($request) . "\n\n";

$client = new PaymentClient();

try {
    $payment = $client->create($request);
    echo "✅ ÉXITO — el SDK oficial consiguió crear el pago.\n";
    echo "Payment ID: {$payment->id}\n";
    echo "Status: {$payment->status}\n";
    echo "Status detail: {$payment->status_detail}\n";
} catch (MPApiException $e) {
    echo "❌ MPApiException (la API respondió con un error):\n";
    echo "Status code: " . $e->getApiResponse()->getStatusCode() . "\n";
    echo "Content: " . $e->getApiResponse()->getContent() . "\n";
} catch (\Exception $e) {
    echo "❌ Excepción de PHP (no llegó ni a responder la API):\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";
}