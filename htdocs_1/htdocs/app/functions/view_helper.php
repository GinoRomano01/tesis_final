<?php
function load_view($viewPath, $data = []) {
    
    foreach ($data as $key => $value) {
        $GLOBALS[$key] = $value;
    }
    
    extract($data);
    
    if (is_file($viewPath)) {
        require $viewPath;
    } else {
        die('❌ Vista no encontrada: ' . $viewPath);
    }
}