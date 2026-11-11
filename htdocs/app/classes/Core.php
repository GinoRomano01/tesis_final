<?php
class Core {
   
    private $framework = 'San Placido';
    private $version = '1.0.0';
    private $uri = [];
   
    public function __construct() {
        $this->init();
    }  

    private function init() {
        $this->init_session();
        $this->init_load_config();
        $this->init_load_functions();
        $this->init_autoload();
        $this->init_load_servicios();
        $this->dispatch();
    }
   
    private function init_session() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
   
    private function init_load_config() {
        $file = 'core_config.php';
        
        if(!is_file('app/config/'.$file)) {
            die(sprintf(
                'El archivo %s no se encuentra, es requerido para que %s funcione.', 
                $file, 
                $this->framework
            ));
        }
       
        require_once 'app/config/'.$file;
    }

    private function init_load_functions() {
        $file = 'core_basic_functions.php';
        if(!is_file(FUNCTIONS.$file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione.', $file, $this->framework));
        }
        require_once FUNCTIONS.$file;
    
        $file = 'core_custom_functions.php';
        if(!is_file(FUNCTIONS.$file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione.', $file, $this->framework));
        }
        require_once FUNCTIONS.$file;
    }

    private function init_autoload() {
        require_once CLASSES . 'Autoloader.php';
        Autoloader::init();
        
        require_once CLASSES . 'Controller.php';
        require_once CLASSES . 'Model.php';
        require_once CLASSES . 'View.php';
        require_once CLASSES . 'Db.php';
        require_once CLASSES . 'Redirect.php';
        require_once CLASSES . 'Toast.php';
    }

    private function filter_url() {
        if(isset($_GET['uri'])) {
            $this->uri = $_GET['uri'];
            $this->uri = rtrim($this->uri, '/');
            $this->uri = strtolower($this->uri);
            $this->uri = filter_var($this->uri, FILTER_SANITIZE_URL);
            $this->uri = explode('/', $this->uri);
        } else {
            $this->uri = [];
        }
        
        return $this->uri;
    }

    private function dispatch() {
        $this->filter_url();
        error_log('URI: ' . print_r($this->uri, true));
        if(isset($this->uri[0])) {
            $current_controller = $this->uri[0];
            if($current_controller === 'index') {
                $current_controller = DEFAULT_CONTROLLER;
            }
            unset($this->uri[0]);
        } else {
            $current_controller = DEFAULT_CONTROLLER;
        }
        
        $current_controller = ucfirst(strtolower($current_controller));
        $controller         = $current_controller . 'Controller';

        if(!class_exists($controller)) {
            $current_controller = ucfirst(strtolower(DEFAULT_ERROR_CONTROLLER));
            $controller         = $current_controller . 'Controller';
        }
        
        if(isset($this->uri[1])) {
            $method = str_replace('-', '_', $this->uri[1]);
            if(!method_exists($controller, $method)) {
                $current_controller = ucfirst(strtolower(DEFAULT_ERROR_CONTROLLER));
                $controller         = $current_controller . 'Controller';
                $current_method     = DEFAULT_METHOD;
            } else {
                $current_method = $method;
            }
            unset($this->uri[1]);
        } else {
            $current_method = DEFAULT_METHOD;
        }
        
        define('CONTROLLER', strtolower($current_controller));
        define('METHOD', $current_method);

        $controller = new $controller;
        $params     = array_values(empty($this->uri) ? [] : $this->uri);
        
        if(empty($params)) {
            call_user_func([$controller, $current_method]);
        } else {
            call_user_func_array([$controller, $current_method], $params);
        }
        
        return;
    }
    public static function run() {
        $core = new self();
        return;
    }
    private function init_load_servicios() {
        $criticos = ['GroqClient.php', 'PromptsResena.php', 'FiltroOfensivo.php', 'ResenaIAService.php'];
        foreach ($criticos as $f) {
            if (is_file(SERVICIOS . $f)) {
                require_once SERVICIOS . $f;
            }
        }
    }
}