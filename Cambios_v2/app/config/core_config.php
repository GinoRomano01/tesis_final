<?php
require_once __DIR__ . '/../../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('IS_LOCAL', ($_SERVER['HTTP_HOST'] ?? '') !== '');

date_default_timezone_set('America/Argentina/Buenos_Aires');

define('LANG', 'es');

define('BASEPATH', IS_LOCAL
    ? '/SanPlacido/'
    : '/'
);

define('AUTH_SALT', 'San Placido!');

define('PORT', IS_LOCAL ? '81' : '80');


define('URL', IS_LOCAL
    ? ''
    : ''
);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', getcwd() . DS);

define('APP', ROOT . 'app' . DS);
define('CLASSES', APP . 'classes' . DS);
define('CONFIG', APP . 'config' . DS);
define('CONTROLLERS', APP . 'controllers' . DS);
define('FUNCTIONS', APP . 'functions' . DS);
define('MODELS', APP . 'models' . DS);

define('TEMPLATES', ROOT . 'templates' . DS);
define('INCLUDES', TEMPLATES . 'includes' . DS);
define('MODULES', TEMPLATES . 'modules' . DS);
define('VIEWS', TEMPLATES . 'views' . DS);

define('ASSETS', URL . 'assets/');
define('CSS', ASSETS . 'css/');
define('FAVICON', ASSETS . 'favicon/');
define('FONTS', ASSETS . 'fonts/');
define('IMAGES', ASSETS . 'images/');
define('JS', ASSETS . 'js/');
define('PLUGINS', ASSETS . 'plugins/');
define('UPLOADS', ASSETS . 'uploads/');

define('IMG', URL . 'templates/assets/imagenes/');
define('IMG_PRODUCTOS', IMG . 'productos/');
define('IMG_HERO', IMG . 'hero/');
define('IMG_CATEGORIAS', IMG . 'categorias/');

define('SERVICIOS', APP . 'servicios' . DS);


define('LDB_ENGINE', 'mysql');
define('LDB_HOST', '');
define('LDB_NAME', '');
define('LDB_USER', '');
define('LDB_PASS', '');
define('LDB_CHARSET', 'utf8mb4');


define('DB_ENGINE', 'mysql');
define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', 'A');
define('DB_CHARSET', '');


define('GROQ_API_KEY', ''); 
define('GROQ_API_URL', '');
define('GROQ_MODEL', '');
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_ERROR_CONTROLLER', 'error');
define('DEFAULT_METHOD', 'index');
define('GROK_TIMEOUT', 15);

define('RESENA_AUTO_APROBAR_TOXICIDAD_MAX', 0.20);
define('RESENA_AUTO_RECHAZAR_TOXICIDAD_MIN', 0.75);
define('RESENA_MAX_CARACTERES', 1500);

define('STOCK_UMBRAL_BAJO', 2);
define('STOCK_UMBRAL_SIN',  0);
define('DIAS_MATERIAL_MUERTO', 90);


define('MP_PUBLIC_KEY', IS_LOCAL
    ? ''
    : '' 
);

define('MP_ACCESS_TOKEN', IS_LOCAL
    ? ''
    : '' 
);


define('MP_MODO_TEST', true);

define('MP_PUBLIC_KEY', MP_MODO_TEST
    ? ''
    : ''
);

define('MP_ACCESS_TOKEN', MP_MODO_TEST
    ? ''
    : ''
);

define('MP_WEBHOOK_SECRET', 'CLAVE-SECRETA-DEL-PANEL-DE-MP'); 