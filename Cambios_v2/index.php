<?php

if (IS_LOCAL) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}


if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

require_once 'app/classes/Core.php';


Core::run();