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
            <div class="auth-brand-sub">Paso 3 de 3 — Confirmación</div>
        </div>

        <div class="auth-steps">
            <div class="auth-step done">Paso 1 — Datos personales</div>
            <div class="auth-step done">Paso 2 — Acceso</div>
            <div class="auth-step active">Paso 3 — Confirmación</div>
        </div>

        <div class="auth-alert">
            <i class="fas fa-envelope"></i>
            Enviamos un código de verificación a:<br>
            <strong><?= $_SESSION['usuario_data']['CorreoElectronico'] ?? '' ?></strong><br>
            Ingresalo a continuación.
        </div>

        <form id="formCodigo">
            <div class="auth-field">
                <label>Código de verificación</label>
                <input type="text" name="codigo" id="codigo" class="code-input"
                       maxlength="6" pattern="\d{6}" required
                       placeholder="000000">
                <div class="auth-hint" style="text-align:center;">Código de 6 dígitos</div>
            </div>

            <button type="submit" id="btnVerificar" class="btn-auth-primary">
                <i class="fas fa-check-circle"></i> Verificar y registrar
            </button>

            <button type="button" id="btnReenviar" class="btn-auth-link">
                <i class="fas fa-envelope"></i> No recibí el código — Reenviar
            </button>
        </form>

        <div class="auth-footer-links" style="margin-top:1rem;">
            <a href="<?= URL ?>registro/cliente">
                <i class="fas fa-arrow-left"></i> Volver al inicio del registro
            </a>
        </div>

    </div>
</div>

<script>
const form        = document.getElementById('formCodigo');
const btnVerificar= document.getElementById('btnVerificar');
const btnReenviar = document.getElementById('btnReenviar');
const inputCodigo = document.getElementById('codigo');

inputCodigo.addEventListener('input', e => { e.target.value = e.target.value.replace(/\D/g, ''); });

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (inputCodigo.value.length !== 6) { alert('El código debe tener 6 dígitos'); return; }
    btnVerificar.disabled = true;
    btnVerificar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
    try {
        const formData = new FormData();
        formData.append('codigo', inputCodigo.value);
        const data = await (await fetch('<?= URL ?>registro/verificar_codigo', {
            method: 'POST', body: formData
        })).json();
        if (data.success) {
            btnVerificar.innerHTML = '<i class="fas fa-check"></i> Verificado';
            setTimeout(() => window.location.href = '<?= URL ?>login?registro=exitoso', 1500);
        } else {
            alert(data.error || 'Código incorrecto');
            btnVerificar.disabled = false;
            btnVerificar.innerHTML = '<i class="fas fa-check-circle"></i> Verificar y registrar';
            inputCodigo.value = ''; inputCodigo.focus();
        }
    } catch {
        alert('Error al verificar el código');
        btnVerificar.disabled = false;
        btnVerificar.innerHTML = '<i class="fas fa-check-circle"></i> Verificar y registrar';
    }
});

btnReenviar.addEventListener('click', async () => {
    btnReenviar.disabled = true;
    btnReenviar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Reenviando...';
    try {
        const data = await (await fetch('<?= URL ?>registro/reenviar_codigo', { method: 'POST' })).json();
        if (data.success) {
            alert(data.mensaje || 'Código reenviado correctamente');
            btnReenviar.innerHTML = '<i class="fas fa-check"></i> Código reenviado';
            setTimeout(() => {
                btnReenviar.disabled = false;
                btnReenviar.innerHTML = '<i class="fas fa-envelope"></i> No recibí el código — Reenviar';
            }, 30000);
        } else {
            alert(data.error || 'Error al reenviar');
            btnReenviar.disabled = false;
            btnReenviar.innerHTML = '<i class="fas fa-envelope"></i> No recibí el código — Reenviar';
        }
    } catch {
        alert('Error al reenviar el código');
        btnReenviar.disabled = false;
        btnReenviar.innerHTML = '<i class="fas fa-envelope"></i> No recibí el código — Reenviar';
    }
});
</script>
</body>
</html>