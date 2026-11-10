<?php

class Controller {
    
    protected $data = [];
    
    protected $title = 'Core Framework';
    
    function __construct() {
        $this->data['title'] = $this->title;
        $this->data['site_name'] = get_sitename();
    }
    
    /**
     * Helper para renderizar vistas con datos
     * @param string $view Nombre de la vista
     * @param array $data Datos adicionales (opcional)
     */
    protected function render($view, $data = []) {
        $viewData = array_merge($this->data, ['title' => $this->title], $data);
        View::render($view, $viewData);
    }
    
    /**
     * Helper para redirecciones con notificación
     * @param string $url URL de destino
     * @param string $message Mensaje de notificación
     * @param string $type Tipo de notificación (success, error, etc.)
     */
    protected function redirectWithMessage($url, $message, $type = 'success') {
        Toast::new($message, $type);
        Redirect::to($url);
    }
    
    /**
     * @param array $required 
     * @return bool
     */
    protected function validatePost($required = []) {
        foreach ($required as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * @param string $key 
     * @param mixed $default 
     * @return mixed 
     */
    protected function post($key, $default = null) {
        return isset($_POST[$key]) ? clean($_POST[$key]) : $default;
    }
    
    /**
     * @param string $key 
     * @param mixed $default 
     * @return mixed 
     */
    protected function get($key, $default = null) {
        return isset($_GET[$key]) ? clean($_GET[$key]) : $default;
    }
}