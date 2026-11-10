<?php
// Cargar configuración
require_once 'app/config/core_config.php';

// Cargar autoloader
require_once CLASSES . 'Autoloader.php';
Autoloader::init();

// Cargar clases base manualmente
require_once CLASSES . 'Controller.php';
require_once CLASSES . 'Model.php';
require_once CLASSES . 'View.php';
require_once CLASSES . 'Db.php';
require_once CLASSES . 'Redirect.php';
require_once CLASSES . 'Toast.php';  // ← IMPORTANTE

// Cargar funciones
foreach (glob(FUNCTIONS . '*.php') as $file) {
    require_once $file;
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir constantes necesarias
if (!defined('INCLUDES')) define('INCLUDES', TEMPLATES . 'includes' . DS);
if (!defined('CSS')) define('CSS', URL . 'templates/assets/css/');
if (!defined('JS')) define('JS', URL . 'templates/assets/js/');
if (!defined('IMG_PRODUCTOS')) define('IMG_PRODUCTOS', URL . 'templates/assets/imagenes/productos/');
if (!defined('IMG_HERO')) define('IMG_HERO', URL . 'templates/assets/imagenes/hero/');
if (!defined('IMG_CATEGORIAS')) define('IMG_CATEGORIAS', URL . 'templates/assets/imagenes/categorias/');

$title = 'Configurador - San Plácido';

include VIEWS . 'configurador' . DS . 'index.php';