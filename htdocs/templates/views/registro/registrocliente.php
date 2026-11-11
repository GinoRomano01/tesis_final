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
    <div class="auth-card wide">

        <div class="auth-brand">
            <div class="auth-brand-title">SAN <span>PLÁCIDO</span></div>
            <div class="auth-brand-sub">Creá tu cuenta</div>
        </div>

        <div class="auth-steps">
            <div class="auth-step active"> Datos personales</div>
            <div class="auth-step"> Acceso</div>
            <div class="auth-step"> Confirmación</div>
        </div>

        <form id="formCliente">

            <div class="auth-field-row">
                <div class="auth-field">
                    <label>DNI *</label>
                    <input type="text" name="dni" id="dni"
                           maxlength="8" required pattern="\d{7,8}">
                    <div class="auth-msg" id="mensajeDni"></div>
                </div>
                <div class="auth-field">
                    <label>Tipo de DNI *</label>
                    <select name="tipodni" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($tiposDni as $t): ?>
                            <option value="<?= $t['Id'] ?>"><?= htmlspecialchars($t['Nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="auth-field-row">
                <div class="auth-field">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" id="nombre" required
                           style="text-transform:capitalize;">
                </div>
                <div class="auth-field">
                    <label>Apellido *</label>
                    <input type="text" name="apellido" id="apellido" required
                           style="text-transform:capitalize;">
                </div>
            </div>

            <div class="auth-field-row">
                <div class="auth-field">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" maxlength="20">
                </div>
                <div class="auth-field">
                    <label>Localidad *</label>
                    <select name="localidad" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($localidades as $l): ?>
                            <option value="<?= $l['Id'] ?>"><?= htmlspecialchars($l['Nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <hr class="auth-sep">

            <div class="auth-field">
                <label>Tipo de domicilio *</label>
                <select name="tipo_domicilio" id="tipo_domicilio" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($tiposDomicilio as $td): ?>
                        <option value="<?= $td['Id'] ?>"><?= htmlspecialchars($td['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="camposDomicilio" class="domicilio-campos"></div>

            <button type="submit" id="btnSiguiente" class="btn-auth-primary" style="margin-top:1rem;">
                Siguiente <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-footer-links" style="margin-top:1rem;">
            <a href="<?= URL ?>login">Ya tengo cuenta — Iniciar sesión</a>
        </div>

    </div>
</div>

<script>
const form            = document.getElementById('formCliente');
const camposContainer = document.getElementById('camposDomicilio');
const tipoDomicilio   = document.getElementById('tipo_domicilio');
const dniInput        = document.getElementById('dni');
const mensajeDni      = document.getElementById('mensajeDni');
const btnSiguiente    = document.getElementById('btnSiguiente');

['nombre', 'apellido'].forEach(id => {
    document.getElementById(id).addEventListener('input', function () {
        const pos = this.selectionStart;
        this.value = this.value.replace(/\b\w/g, c => c.toUpperCase());
        this.setSelectionRange(pos, pos);
    });
});

dniInput.addEventListener('blur', async () => {
    const dni = dniInput.value.trim();
    if (dni.length < 7 || dni.length > 8 || !/^\d+$/.test(dni)) {
        mensajeDni.textContent = 'El DNI debe tener 7 u 8 dígitos numéricos.';
        mensajeDni.className = 'auth-msg error';
        btnSiguiente.disabled = true;
        return;
    }
    mensajeDni.textContent = 'Verificando...';
    mensajeDni.className = 'auth-msg loading';
    try {
        const response = await fetch('<?= URL ?>registro/verificar-dni', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'dni=' + encodeURIComponent(dni)
        });
        const data = await response.json();
        if (data.existe) {
            mensajeDni.textContent = 'Este DNI ya está registrado.';
            mensajeDni.className = 'auth-msg error';
            btnSiguiente.disabled = true;
        } else {
            mensajeDni.textContent = 'DNI disponible.';
            mensajeDni.className = 'auth-msg ok';
            btnSiguiente.disabled = false;
        }
    } catch {
        mensajeDni.textContent = 'Error al verificar el DNI.';
        mensajeDni.className = 'auth-msg error';
        btnSiguiente.disabled = false;
    }
});

const campoCp = `
    <div class="auth-field">
        <label>Código postal</label>
        <input type="text" name="codigo_postal" maxlength="10" placeholder="Ej: 5000">
    </div>`;

tipoDomicilio.addEventListener('change', () => {
    const tipo = tipoDomicilio.value;
    let campos = '';

    if (tipo === '1') {
        campos = `<div class="domicilio-grid">
            <div class="auth-field"><label>Calle *</label><input type="text" name="calle" required></div>
            <div class="auth-field"><label>Número *</label><input type="number" name="numero" required></div>
            ${campoCp}
        </div>`;
    } else if (tipo === '2') {
        campos = `<div class="domicilio-grid">
            <div class="auth-field"><label>Calle *</label><input type="text" name="calle" required></div>
            <div class="auth-field"><label>Número *</label><input type="number" name="numero" required></div>
            <div class="auth-field"><label>Piso</label><input type="number" name="piso"></div>
            <div class="auth-field"><label>Depto</label><input type="text" name="departamento"></div>
            ${campoCp}
        </div>`;
    } else if (tipo === '3') {
        campos = `<div class="domicilio-grid">
            <div class="auth-field domicilio-full"><label>Nombre del barrio *</label><input type="text" name="country" required></div>
            <div class="auth-field domicilio-full"><label>Barrio / Manzana</label><input type="text" name="barrio"></div>
            <div class="auth-field"><label>Calle *</label><input type="text" name="calle" required></div>
            <div class="auth-field"><label>Número *</label><input type="number" name="numero" required></div>
            ${campoCp}
        </div>`;
    }
    camposContainer.innerHTML = campos;
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    btnSiguiente.disabled = true;
    btnSiguiente.innerHTML = 'Guardando...';
    try {
        const data = await (await fetch('<?= URL ?>registro/guardar-cliente', {
            method: 'POST', body: new FormData(form)
        })).json();
        if (data.success) {
            window.location.href = '<?= URL ?>registro/usuario';
        } else {
            alert(data.error || 'Error al guardar los datos');
            btnSiguiente.disabled = false;
            btnSiguiente.innerHTML = 'Siguiente <i class="fas fa-arrow-right"></i>';
        }
    } catch {
        alert('Error al comunicarse con el servidor');
        btnSiguiente.disabled = false;
        btnSiguiente.innerHTML = 'Siguiente <i class="fas fa-arrow-right"></i>';
    }
});
</script>
</body>
</html>