<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo '<pre style="background:#fee;padding:1rem;border:2px solid red;">';
        echo "ERROR: " . htmlspecialchars($error['message']) . "\n";
        echo "ARCHIVO: " . htmlspecialchars($error['file']) . "\n";
        echo "LINEA: " . $error['line'] . "\n";
        echo '</pre>';
    }
});

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

require_once 'app/classes/Core.php';

Core::run();