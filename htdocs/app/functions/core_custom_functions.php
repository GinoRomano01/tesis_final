<?php

require_once FUNCTIONS . 'permisos.php';

function calcular_descuento($precio, $porcentaje) {
    return $precio - ($precio * $porcentaje / 100);
}

function timeAgo($fecha) {
    $ahora = new DateTime();
    $entonces = new DateTime($fecha);
    $diff = $ahora->getTimestamp() - $entonces->getTimestamp();

    if ($diff < 60)     return 'hace un momento';
    if ($diff < 3600)   return 'hace ' . floor($diff / 60) . ' min';
    if ($diff < 86400)  return 'hace ' . floor($diff / 3600) . ' h';
    if ($diff < 604800) return 'hace ' . floor($diff / 86400) . ' d';

    return $entonces->format('d/m/Y H:i');
}
