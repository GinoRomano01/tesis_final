<?php

class Controller {

    protected $data = [];

    protected $title = 'Sanplacido';

    function __construct() {
        $this->data['title'] = $this->title;
        $this->data['site_name'] = get_sitename();
    }

    
    protected function render($view, $data = []) {
        $viewData = array_merge($this->data, ['title' => $this->title], $data);
        View::render($view, $viewData);
    }

    
    protected function redirectWithMessage($url, $message, $type = 'success') {
        Toast::new($message, $type);
        Redirect::to($url);
    }

    protected function validatePost($required = []) {
        foreach ($required as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                return false;
            }
        }
        return true;
    }

    protected function post($key, $default = null) {
        return isset($_POST[$key]) ? clean($_POST[$key]) : $default;
    }

    protected function get($key, $default = null) {
        return isset($_GET[$key]) ? clean($_GET[$key]) : $default;
    }
}
