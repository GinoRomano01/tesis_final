<?php

/**
 * @param array $data 
 * @return object 
 */
function to_Object($data) {
    if (is_array($data)) {
        return (object) $data;
    }
    return $data;
}

/**
 * @return string 
 */
function get_sitename() {
    return 'San Plácido';
}

/**
 * @param string $string 
 * @return string 
 */
function clean($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

/**
 * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * @return bool
 */
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * @param float $price 
 * @return string
 */
function format_price($price) {
    return '$' . number_format($price, 2, ',', '.');
}

/**
 * @param mixed $data 
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}