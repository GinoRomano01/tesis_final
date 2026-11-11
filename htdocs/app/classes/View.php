<?php

class View {

    public static function render($view, $data = []) {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }

        $viewPath = VIEWS . CONTROLLER . DS . $view . '.php';

        if (is_file($viewPath)) {
            require $viewPath;
        } else {
            require VIEWS . 'error' . DS . 'View_error_404.php';
        }
    }
}
