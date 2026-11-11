<?php
require_once __DIR__ . '/../../vendor/autoload.php';

define('IS_LOCAL', ($_SERVER['HTTP_HOST'] ?? '') !== '');

if (IS_LOCAL) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

if (!is_file(__DIR__ . '/secrets.php')) {
    http_response_code(500);
    exit('Falta app/config/secrets.php .');
}
$secrets = require __DIR__ . '/secrets.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

define('LANG', 'es');

define('BASEPATH', IS_LOCAL
    ? '/SanPlacido/'
    : '/'
);

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
define('LDB_HOST', $secrets['db_local']['host']);
define('LDB_NAME', $secrets['db_local']['name']);
define('LDB_USER', $secrets['db_local']['user']);
define('LDB_PASS', $secrets['db_local']['pass']);
define('LDB_CHARSET', '');

define('DB_ENGINE', 'mysql');
define('DB_HOST', $secrets['db_prod']['host']);
define('DB_NAME', $secrets['db_prod']['name']);
define('DB_USER', $secrets['db_prod']['user']);
define('DB_PASS', $secrets['db_prod']['pass']);
define('DB_CHARSET', '');

define('GROQ_API_KEY', $secrets['groq_api_key']);
define('GROQ_API_URL', '');
define('GROQ_MODEL', ''); 
 
define('CRON_SECRET', $secrets['cron_secret']);
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_ERROR_CONTROLLER', 'error');
define('DEFAULT_METHOD', 'index');
define('GROK_TIMEOUT', 15);

define('RESENA_AUTO_APROBAR_TOXICIDAD_MAX', 0.20);
define('RESENA_AUTO_RECHAZAR_TOXICIDAD_MIN', 0.75);
define('RESENA_MAX_CARACTERES', 1500);

define('STOCK_UMBRAL_MADERAS_FALLBACK', 5);
define('STOCK_UMBRAL_INSUMOS_FALLBACK', 5);
define('STOCK_UMBRAL_SIN',  0);



define('DIAS_MATERIAL_MUERTO', 90);

// Modo de MercadoPago: true cuando esta usando las credenciales de prueba
// hay que pasarlo a false cuando se usen las credenciales reales de produccion
define('MP_MODO_TEST', true);

define('MP_PUBLIC_KEY', MP_MODO_TEST
    ? $secrets['mercadopago']['test_public_key']
    : $secrets['mercadopago']['prod_public_key']
);

define('MP_ACCESS_TOKEN', MP_MODO_TEST
    ? $secrets['mercadopago']['test_access_token']
    : $secrets['mercadopago']['prod_access_token']
);

define('MP_WEBHOOK_SECRET', $secrets['mercadopago']['webhook_secret']);

// Correo (SMTP de Brevo, lo usa app/classes/Mailer.php)
define('SMTP_HOST', $secrets['smtp']['host']);
define('SMTP_PORT', $secrets['smtp']['port']);
define('SMTP_USER', $secrets['smtp']['user']);
define('SMTP_PASS', $secrets['smtp']['pass']);
define('SMTP_FROM', $secrets['smtp']['from']);

define('PENDIENTE_AUTO_APROBAR_HORAS', 24 * 30);

unset($secrets);
