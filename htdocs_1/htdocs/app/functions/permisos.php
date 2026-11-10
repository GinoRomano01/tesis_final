<?php

function getTipoUsuario(): int {
    return (int)($_SESSION['tipo_usuario'] ?? 2);
}

function getRol(): int {
    return (int)($_SESSION['tipo_rol'] ?? 2);
}

function esAdmin(): bool {
    return getTipoUsuario() === 3;
}

function esGerente(): bool {
    return getRol() === 1;
}

function esVendedor(): bool {
    return getRol() === 4;
}

function esCarpintero(): bool {
    return getRol() === 5;
}

function esRepartidor(): bool {
    return getRol() === 3;
}

function puedeVerUsuarios(): bool {
    return esAdmin() || esGerente();
}

function puedeEliminar(): bool {
    return esAdmin() || esGerente();
}

function puedeVerVentas(): bool {
    return esAdmin() || esGerente() || esVendedor();
}

function puedeVerStock(): bool {
    return esAdmin() || esGerente() || esCarpintero();
}

function puedeVerProduccion(): bool {
    return esAdmin() || esGerente() || esCarpintero() || esVendedor();
}

function puedeVerEntregas(): bool {
    return esAdmin() || esGerente() || esCarpintero() || esVendedor() || esRepartidor();
}

function puedeVerEstadisticas(): bool {
    return esAdmin() || esGerente();
}

function checkPermiso(string $funcion): void {
    if (!$funcion()) {
        Toast::new('No tenés permisos para acceder a esa sección.', 'danger');
        Redirect::to('admin/LobbyAdmin');
        exit;
    }
}