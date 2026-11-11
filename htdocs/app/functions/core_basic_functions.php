<?php

function to_Object($data) {
    if (is_array($data)) {
        return (object) $data;
    }
    return $data;
}

function get_sitename() {
    return 'San Plácido';
}

function clean($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function format_price($price) {
    return '$' . number_format($price, 2, ',', '.');
}


function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
