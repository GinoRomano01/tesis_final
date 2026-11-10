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
            <div class="auth-brand-sub">Paso 2 de 3 — Datos de acceso</div>
        </div>

        <div class="auth-steps">
            <div class="auth-step done"> Datos personales</div>
            <div class="auth-step active"> Acceso</div>
            <div class="auth-step"> Confirmación</div>
        </div>

        <div class="auth-cliente-info">
            <strong>Cliente:</strong> <?= htmlspecialchars($cliente['Nombre']) ?>
            <?= htmlspecialchars($cliente['Apellido']) ?><br>
            <strong>DNI:</strong> <?= htmlspecialchars($cliente['DNI']) ?><br>
            <strong>Teléfono:</strong> <?= htmlspecialchars($cliente['Telefono'] ?? '(sin teléfono)') ?>
        </div>

        <form id="formUsuario">
            <div class="auth-field">
                <label>Nombre de usuario *</label>
                <input type="text" name="nombre_usuario" required minlength="4" maxlength="40">
            </div>
            <div class="auth-field">
                <label>Correo electrónico *</label>
                <input type="email" name="correo" id="correo" required maxlength="50">
                <div class="auth-msg" id="mensajeCorreo"></div>
            </div>
            <div class="auth-field-row">
                <div class="auth-field">
                    <label>Contraseña *</label>
                    <input type="password" name="password" id="password" required minlength="6" maxlength="30">
                </div>
                <div class="auth-field">
                    <label>Confirmar contraseña *</label>
                    <input type="password" name="confirmar" id="confirmar" required minlength="6" maxlength="30">
                </div>
            </div>

            <div class="auth-nav">
                <a href="<?= URL ?>registro/cliente" class="btn-auth-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button type="submit" id="btnContinuar" class="btn-auth-primary">
                    Continuar <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>

    </div>
</div>

<script>
const correoInput   = document.getElementById('correo');
const mensajeCorreo = document.getElementById('mensajeCorreo');
const btnContinuar  = document.getElementById('btnContinuar');
const form          = document.getElementById('formUsuario');

correoInput.addEventListener('blur', async () => {
    const correo = correoInput.value.trim();
    if (!correo) return;
    const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    if (!emailRegex.test(correo)) {
        mensajeCorreo.textContent = 'Ingresá un correo válido.';
        mensajeCorreo.className = 'auth-msg error'; return;
    }
    mensajeCorreo.textContent = 'Verificando...';
    mensajeCorreo.className = 'auth-msg loading';
    try {
        const data = await (await fetch('<?= URL ?>registro/verificar-correo', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'correo=' + encodeURIComponent(correo)
        })).json();
        mensajeCorreo.textContent = data.existe ? 'Este correo ya está registrado.' : 'Correo disponible.';
        mensajeCorreo.className = 'auth-msg ' + (data.existe ? 'error' : 'ok');
    } catch {
        mensajeCorreo.textContent = 'Error al verificar el correo.';
        mensajeCorreo.className = 'auth-msg error';
    }
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (document.getElementById('password').value !== document.getElementById('confirmar').value) {
        alert('Las contraseñas no coinciden.'); return;
    }
    btnContinuar.disabled = true;
    btnContinuar.innerHTML = 'Enviando código...';
    try {
        const data = await (await fetch('<?= URL ?>registro/guardar-usuario', {
            method: 'POST', body: new FormData(form)
        })).json();
        if (data.success) {
            window.location.href = '<?= URL ?>registro/confirmar';
        } else {
            alert(data.error || 'Error al enviar el código');
            btnContinuar.disabled = false;
            btnContinuar.innerHTML = 'Continuar <i class="fas fa-arrow-right"></i>';
        }
    } catch {
        alert('Error al comunicarse con el servidor');
        btnContinuar.disabled = false;
        btnContinuar.innerHTML = 'Continuar <i class="fas fa-arrow-right"></i>';
    }
});
</script>
</body>
</html>