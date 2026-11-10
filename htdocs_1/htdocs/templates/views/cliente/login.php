<?php
if (!defined('ROOT') && !defined('VIEWS')) {
    header('HTTP/1.0 403 Forbidden');
    die('Acceso directo no permitido');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/tokens.css">
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/registro.css">
</head>
<body class="auth-body">

<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-brand">
            <div class="auth-brand-title">SAN <span>PLÁCIDO</span></div>
            <div class="auth-brand-sub">Ingresá a tu cuenta</div>
        </div>

        <form id="formLogin">
            <div class="auth-field">
                <label>Correo electrónico</label>
                <input type="email" name="correo" id="correo"
                       placeholder="ejemplo@correo.com" required>
            </div>

            <div class="auth-field">
                <label>Contraseña</label>
                <input type="password" name="password" id="password"
                       placeholder="Ingresá tu contraseña" required>
            </div>

            <button type="submit" id="btnLogin" class="btn-auth-primary">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <hr class="auth-sep">

        <div class="auth-footer-links">
            <p style="margin-bottom:.6rem;">¿No tenés cuenta?</p>
            <a href="<?= URL ?>registro/cliente" class="btn-auth-secondary">
                <i class="fas fa-user-plus"></i> Registrarse
            </a>
            <br>
            <a href="<?= URL ?>login/recuperar-password" style="margin-top:.8rem;display:inline-block;">
                ¿Olvidaste tu contraseña?
            </a>
        </div>

    </div>
</div>

<script>
const formLogin  = document.getElementById('formLogin');
const btnLogin   = document.getElementById('btnLogin');

formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();
    btnLogin.disabled = true;
    btnLogin.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';

    try {
        const response = await fetch('<?= URL ?>login/procesar', {
            method: 'POST',
            body: new FormData(formLogin)
        });
        if (!response.ok) throw new Error('Error en el servidor');
        const data = await response.json();

        if (data.success) {
            btnLogin.innerHTML = '<i class="fas fa-check"></i> ¡Bienvenido!';
            setTimeout(() => window.location.href = data.redirect, 1000);
        } else {
            alert(data.error || 'Error al iniciar sesión');
            btnLogin.disabled = false;
            btnLogin.innerHTML = '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión';
        }
    } catch (error) {
        alert('Error al comunicarse con el servidor');
        btnLogin.disabled = false;
        btnLogin.innerHTML = '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión';
    }
});
</script>
</body>
</html>