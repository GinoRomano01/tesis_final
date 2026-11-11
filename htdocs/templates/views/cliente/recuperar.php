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
    <title>Recuperar contraseña — San Plácido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/tokens.css">
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/registro.css">
    <style>
        .paso { display: none; }
        .paso.activo { display: block; }

        .codigo-inputs {
            display: flex;
            gap: .5rem;
            justify-content: center;
            margin: 1rem 0;
        }
        .codigo-inputs input {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 1.4rem;
            font-weight: 700;
            border: 2px solid #ddd;
            border-radius: 10px;
            outline: none;
            transition: border-color .2s;
        }
        .codigo-inputs input:focus { border-color: #c8a96e; }
        .codigo-inputs input.error { border-color: #ef4444; background: #fef2f2; }
        .codigo-inputs input.ok    { border-color: #22c55e; background: #f0fdf4; }

        .req-list {
            list-style: none;
            padding: 0;
            margin: .5rem 0 1rem;
            font-size: .82rem;
        }
        .req-list li {
            display: flex;
            align-items: center;
            gap: .4rem;
            color: #aaa;
            margin-bottom: .25rem;
            transition: color .2s;
        }
        .req-list li.ok  { color: #22c55e; }
        .req-list li.bad { color: #ef4444; }
        .req-list li i   { width: 14px; }

        .pasos-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            margin-bottom: 1.5rem;
        }
        .paso-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #e0e0e0;
            transition: background .3s;
        }
        .paso-dot.activo { background: #c8a96e; }

        .reenviar-wrap {
            text-align: center;
            margin-top: .8rem;
            font-size: .85rem;
            color: #888;
        }
        .reenviar-wrap button {
            background: none;
            border: none;
            color: #c8a96e;
            cursor: pointer;
            font-size: .85rem;
            padding: 0;
            text-decoration: underline;
        }
        .reenviar-wrap button:disabled {
            color: #aaa;
            text-decoration: none;
            cursor: default;
        }

        .msg-box {
            padding: .75rem 1rem;
            border-radius: 8px;
            font-size: .9rem;
            margin-bottom: 1rem;
            display: none;
        }
        .msg-box.error   { background:#fee2e2;color:#991b1b;border:1px solid #fca5a5; }
        .msg-box.success { background:#dcfce7;color:#166534;border:1px solid #86efac; }
        .msg-box.info    { background:#eff6ff;color:#1e40af;border:1px solid #93c5fd; }
    </style>
</head>
<body class="auth-body">

<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-brand">
            <div class="auth-brand-title">SAN <span>PLÁCIDO</span></div>
            <div class="auth-brand-sub">Recuperar contraseña</div>
        </div>

        <div class="pasos-indicator">
            <div class="paso-dot activo" id="dot1"></div>
            <div class="paso-dot"        id="dot2"></div>
            <div class="paso-dot"        id="dot3"></div>
        </div>

        <div class="paso activo" id="paso1">
            <p style="text-align:center;color:#666;font-size:.9rem;margin-bottom:1.2rem;">
                Ingresá tu correo y te enviamos un código de 6 dígitos.
            </p>
            <div id="msg1" class="msg-box"></div>
            <div class="auth-field">
                <label>Correo electrónico</label>
                <input type="email" id="inp-correo" placeholder="ejemplo@correo.com" required>
            </div>
            <button id="btn-enviar" class="btn-auth-primary" onclick="enviarCodigo()">
                <i class="fas fa-paper-plane"></i> Enviar código
            </button>
            <div style="text-align:center;margin-top:1rem;">
                <a href="<?= URL ?>login" style="font-size:.85rem;color:#888;">
                    <i class="fas fa-arrow-left"></i> Volver al login
                </a>
            </div>
        </div>

        <div class="paso" id="paso2">
            <p style="text-align:center;color:#666;font-size:.9rem;margin-bottom:1.2rem;">
                Ingresá el código que enviamos a <strong id="correo-mostrado"></strong>
            </p>
            <div id="msg2" class="msg-box"></div>
            <div class="codigo-inputs">
                <input type="text" maxlength="1" class="dig" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="dig" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="dig" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="dig" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="dig" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="dig" inputmode="numeric" pattern="[0-9]">
            </div>
            <button id="btn-verificar" class="btn-auth-primary" onclick="verificarCodigo()">
                <i class="fas fa-check-circle"></i> Verificar código
            </button>
            <div class="reenviar-wrap">
                <span>¿No llegó? </span>
                <button id="btn-reenviar" onclick="reenviarCodigo()">Reenviar</button>
                <span id="countdown"></span>
            </div>
        </div>

        <div class="paso" id="paso3">
            <p style="text-align:center;color:#666;font-size:.9rem;margin-bottom:1.2rem;">
                Código verificado. Creá tu nueva contraseña.
            </p>
            <div id="msg3" class="msg-box"></div>
            <div class="auth-field">
                <label>Nueva contraseña</label>
                <input type="password" id="inp-pass1" placeholder="Mínimo 8 caracteres"
                       oninput="validarPass()">
            </div>
            <ul class="req-list" id="req-list">
                <li id="req-len"><i class="fas fa-circle"></i> Al menos 8 caracteres</li>
                <li id="req-may"><i class="fas fa-circle"></i> Una mayúscula</li>
                <li id="req-num"><i class="fas fa-circle"></i> Un número</li>
            </ul>
            <div class="auth-field">
                <label>Confirmar contraseña</label>
                <input type="password" id="inp-pass2" placeholder="Repetí la contraseña"
                       oninput="validarConfirm()">
            </div>
            <p id="match-msg" style="font-size:.82rem;margin-top:-.5rem;margin-bottom:.8rem;display:none;"></p>
            <button id="btn-cambiar" class="btn-auth-primary" onclick="cambiarPassword()">
                <i class="fas fa-lock"></i> Cambiar contraseña
            </button>
        </div>

    </div>
</div>

<script>
const URL_BASE = '<?= URL ?>';
let correoActual = '';
let tokenActual  = '';
let timerInterval = null;

async function enviarCodigo() {
    const correo = document.getElementById('inp-correo').value.trim();
    const msg    = document.getElementById('msg1');
    const btn    = document.getElementById('btn-enviar');

    if (!correo || !correo.includes('@')) {
        mostrarMsg(msg, 'Ingresá un correo válido.', 'error');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    ocultarMsg(msg);

    try {
        const r    = await fetch(URL_BASE + 'login/enviar_codigo', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo })
        });
        const data = await r.json();

        if (data.ok) {
            correoActual = correo;
            document.getElementById('correo-mostrado').textContent = correo;
            irAPaso(2);
            iniciarCountdown(60);
        } else {
            mostrarMsg(msg, data.error || 'Error al enviar el código.', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar código';
        }
    } catch {
        mostrarMsg(msg, 'Error de conexión. Intentá nuevamente.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar código';
    }
}

async function verificarCodigo() {
    const digs = [...document.querySelectorAll('.dig')].map(i => i.value).join('');
    const msg  = document.getElementById('msg2');
    const btn  = document.getElementById('btn-verificar');

    if (digs.length < 6 || !/^\d{6}$/.test(digs)) {
        mostrarMsg(msg, 'Ingresá los 6 dígitos del código.', 'error');
        document.querySelectorAll('.dig').forEach(d => d.classList.add('error'));
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
    ocultarMsg(msg);

    try {
        const r    = await fetch(URL_BASE + 'login/verificar_codigo', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo: correoActual, codigo: digs })
        });
        const data = await r.json();

        if (data.ok) {
            tokenActual = data.token;
            document.querySelectorAll('.dig').forEach(d => d.classList.add('ok'));
            clearInterval(timerInterval);
            setTimeout(() => irAPaso(3), 400);
        } else {
            mostrarMsg(msg, data.error || 'Código incorrecto.', 'error');
            document.querySelectorAll('.dig').forEach(d => {
                d.classList.add('error');
                d.value = '';
            });
            document.querySelectorAll('.dig')[0].focus();
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Verificar código';
        }
    } catch {
        mostrarMsg(msg, 'Error de conexión.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle"></i> Verificar código';
    }
}

async function reenviarCodigo() {
    const btn = document.getElementById('btn-reenviar');
    btn.disabled = true;

    try {
        const r    = await fetch(URL_BASE + 'login/enviar_codigo', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo: correoActual })
        });
        const data = await r.json();
        const msg  = document.getElementById('msg2');

        if (data.ok) {
            mostrarMsg(msg, 'Código reenviado. Revisá tu correo.', 'info');
            document.querySelectorAll('.dig').forEach(d => {
                d.value = '';
                d.classList.remove('error', 'ok');
            });
            document.querySelectorAll('.dig')[0].focus();
            iniciarCountdown(60);
        } else {
            mostrarMsg(msg, data.error || 'Error al reenviar.', 'error');
            btn.disabled = false;
        }
    } catch {
        btn.disabled = false;
    }
}

async function cambiarPassword() {
    const pass1 = document.getElementById('inp-pass1').value;
    const pass2 = document.getElementById('inp-pass2').value;
    const msg   = document.getElementById('msg3');
    const btn   = document.getElementById('btn-cambiar');

    if (!validarPassCompleto(pass1)) {
        mostrarMsg(msg, 'La contraseña no cumple los requisitos.', 'error');
        return;
    }
    if (pass1 !== pass2) {
        mostrarMsg(msg, 'Las contraseñas no coinciden.', 'error');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    ocultarMsg(msg);

    try {
        const r    = await fetch(URL_BASE + 'login/cambiar_password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token: tokenActual, password: pass1 })
        });
        const data = await r.json();

        if (data.ok) {
            mostrarMsg(msg, '¡Contraseña actualizada! Redirigiendo al login...', 'success');
            btn.innerHTML = '<i class="fas fa-check"></i> ¡Listo!';
            setTimeout(() => window.location.href = URL_BASE + 'login', 2000);
        } else {
            mostrarMsg(msg, data.error || 'Error al cambiar la contraseña.', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock"></i> Cambiar contraseña';
        }
    } catch {
        mostrarMsg(msg, 'Error de conexión.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock"></i> Cambiar contraseña';
    }
}

function validarPassCompleto(p) {
    return p.length >= 8 && /[A-Z]/.test(p) && /[0-9]/.test(p);
}

function validarPass() {
    const p    = document.getElementById('inp-pass1').value;
    const reqs = {
        'req-len': p.length >= 8,
        'req-may': /[A-Z]/.test(p),
        'req-num': /[0-9]/.test(p),
    };
    Object.entries(reqs).forEach(([id, ok]) => {
        const el = document.getElementById(id);
        el.className = ok ? 'ok' : '';
        el.querySelector('i').className = ok ? 'fas fa-check-circle' : 'fas fa-circle';
    });
    validarConfirm();
}

function validarConfirm() {
    const p1  = document.getElementById('inp-pass1').value;
    const p2  = document.getElementById('inp-pass2').value;
    const msg = document.getElementById('match-msg');
    if (!p2) { msg.style.display = 'none'; return; }
    msg.style.display = 'block';
    if (p1 === p2) {
        msg.style.color   = '#22c55e';
        msg.textContent   = '✓ Las contraseñas coinciden';
    } else {
        msg.style.color   = '#ef4444';
        msg.textContent   = '✗ Las contraseñas no coinciden';
    }
}

function iniciarCountdown(segs) {
    const btn = document.getElementById('btn-reenviar');
    const cd  = document.getElementById('countdown');
    btn.disabled = true;
    clearInterval(timerInterval);
    let restante = segs;
    cd.textContent = ' (' + restante + 's)';
    timerInterval = setInterval(() => {
        restante--;
        cd.textContent = restante > 0 ? ' (' + restante + 's)' : '';
        if (restante <= 0) {
            clearInterval(timerInterval);
            btn.disabled = false;
        }
    }, 1000);
}

function irAPaso(n) {
    document.querySelectorAll('.paso').forEach((p, i) => {
        p.classList.toggle('activo', i + 1 === n);
    });
    document.querySelectorAll('.paso-dot').forEach((d, i) => {
        d.classList.toggle('activo', i + 1 <= n);
    });
    if (n === 2) document.querySelectorAll('.dig')[0].focus();
}

function mostrarMsg(el, texto, tipo) {
    el.className  = 'msg-box ' + tipo;
    el.textContent = texto;
    el.style.display = 'block';
}
function ocultarMsg(el) { el.style.display = 'none'; }

document.querySelectorAll('.dig').forEach((inp, idx, all) => {
    inp.addEventListener('input', () => {
        inp.classList.remove('error', 'ok');
        const v = inp.value.replace(/\D/g, '');
        inp.value = v ? v[0] : '';
        if (v && idx < all.length - 1) all[idx + 1].focus();
    });
    inp.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !inp.value && idx > 0) all[idx - 1].focus();
    });
    inp.addEventListener('paste', (e) => {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        [...texto].slice(0, 6).forEach((c, i) => { if (all[i]) all[i].value = c; });
        all[Math.min(texto.length, 5)].focus();
    });
});

document.getElementById('inp-correo').addEventListener('keydown', e => {
    if (e.key === 'Enter') enviarCodigo();
});
</script>
</body>
</html>