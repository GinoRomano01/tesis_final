<style>
#sp-chat-btn {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9000;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #5c2d0a;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(92,45,10,.35);
    transition: background .15s, transform .15s;
}
#sp-chat-btn:hover { background: #7a3e14; transform: scale(1.05); }
#sp-chat-btn svg  { width: 26px; height: 26px; fill: #fff; }

#sp-chat-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 18px;
    height: 18px;
    background: #c0392b;
    border-radius: 50%;
    border: 2px solid #fff;
    display: none;
}

#sp-chat-window {
    position: fixed;
    bottom: 92px;
    right: 24px;
    z-index: 9001;
    width: 340px;
    height: 480px;
    border-radius: 16px;
    background: #fdfaf6;
    border: 1.5px solid #d4c4aa;
    box-shadow: 0 8px 40px rgba(44,26,14,.22);
    display: none;
    flex-direction: column;
    overflow: hidden;
    font-family: 'Source Sans 3', sans-serif;
}
#sp-chat-window.open { display: flex; }

.sp-ch-header {
    background: #5c2d0a;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.sp-ch-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #b8722a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    flex-shrink: 0;
}
.sp-ch-header-info { flex: 1; }
.sp-ch-name { font-size: 14px; font-weight: 600; color: #fff; line-height: 1.2; }
.sp-ch-sub  { font-size: 11px; color: rgba(255,255,255,.65); }
.sp-ch-close {
    background: none;
    border: none;
    color: rgba(255,255,255,.7);
    font-size: 18px;
    cursor: pointer;
    line-height: 1;
    padding: 0;
}
.sp-ch-close:hover { color: #fff; }

.sp-ch-messages {
    flex: 1;
    overflow-y: auto;
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 9px;
}
.sp-msg {
    display: flex;
    flex-direction: column;
    max-width: 82%;
}
.sp-msg.user  { align-self: flex-end;  align-items: flex-end; }
.sp-msg.bot   { align-self: flex-start; align-items: flex-start; }
.sp-bubble {
    padding: 8px 12px;
    border-radius: 14px;
    font-size: 13px;
    line-height: 1.5;
    word-break: break-word;
}
.sp-msg.user .sp-bubble {
    background: #5c2d0a;
    color: #fff;
    border-bottom-right-radius: 4px;
}
.sp-msg.bot .sp-bubble {
    background: #ede4d4;
    color: #2c1a0e;
    border-bottom-left-radius: 4px;
}
.sp-bubble a { color: #5c2d0a; text-decoration: underline; }
.sp-msg-time { font-size: 10px; color: #8a7560; margin-top: 2px; padding: 0 3px; }

.sp-typing {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 9px 13px;
    background: #ede4d4;
    border-radius: 14px;
    border-bottom-left-radius: 4px;
    align-self: flex-start;
}
.sp-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #8a7560;
    animation: sp-blink 1.2s infinite;
}
.sp-dot:nth-child(2) { animation-delay: .2s; }
.sp-dot:nth-child(3) { animation-delay: .4s; }
@keyframes sp-blink { 0%,80%,100%{opacity:.2;} 40%{opacity:1;} }

.sp-quick {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    padding: 0 14px 8px;
    flex-shrink: 0;
}
.sp-qbtn {
    font-size: 11px;
    padding: 4px 9px;
    border-radius: 20px;
    border: 1.5px solid #d4c4aa;
    background: #f7f0e6;
    color: #4a3020;
    cursor: pointer;
    font-family: inherit;
    transition: border-color .12s, background .12s;
}
.sp-qbtn:hover { border-color: #5c2d0a; background: #ede4d4; }

.sp-ch-input-area {
    border-top: 1.5px solid #d4c4aa;
    padding: 9px 11px;
    display: flex;
    gap: 7px;
    flex-shrink: 0;
    background: #fdfaf6;
}
.sp-ch-input {
    flex: 1;
    border: 1.5px solid #d4c4aa;
    border-radius: 8px;
    padding: 7px 11px;
    font-size: 13px;
    font-family: inherit;
    background: #f7f0e6;
    color: #2c1a0e;
    outline: none;
    resize: none;
    height: 36px;
}
.sp-ch-input:focus { border-color: #5c2d0a; }
.sp-ch-send {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #5c2d0a;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background .12s;
}
.sp-ch-send:hover    { background: #7a3e14; }
.sp-ch-send:disabled { opacity: .4; cursor: not-allowed; }
.sp-ch-send svg { width: 16px; height: 16px; fill: #fff; }

@media (max-width: 400px) {
    #sp-chat-window { width: calc(100vw - 20px); right: 10px; bottom: 84px; }
    #sp-chat-btn    { right: 16px; bottom: 16px; }
}
</style>

<button id="sp-chat-btn" onclick="spToggleChat()" aria-label="Abrir chat de ayuda">
    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
    <span id="sp-chat-badge"></span>
</button>

<div id="sp-chat-window" role="dialog" aria-label="Chat de ayuda San Plácido">
    <div class="sp-ch-header">
        <div class="sp-ch-avatar">SP</div>
        <div class="sp-ch-header-info">
            <div class="sp-ch-name">San Plácido</div>
            <div class="sp-ch-sub">Asistente virtual</div>
        </div>
        <button class="sp-ch-close" onclick="spToggleChat()" aria-label="Cerrar chat">✕</button>
    </div>

    <div class="sp-ch-messages" id="spMsgs">
        <div class="sp-msg bot">
            <div class="sp-bubble">Hola, soy el asistente de <strong>San Plácido</strong>. ¿En qué te puedo ayudar hoy?</div>
            <div class="sp-msg-time">ahora</div>
        </div>
    </div>

    <div class="sp-quick" id="spQuick">
        <button class="sp-qbtn" onclick="spQuickSend('¿Cómo hago un pedido?')">¿Cómo hago un pedido?</button>
        <button class="sp-qbtn" onclick="spQuickSend('¿Tienen envío a domicilio?')">¿Tienen envío?</button>
        <button class="sp-qbtn" onclick="spQuickSend('¿Cómo puedo pagar?')">Métodos de pago</button>
        <button class="sp-qbtn" onclick="spQuickSend('¿Cómo cancelo una compra?')">Cancelaciones</button>
    </div>

    <div class="sp-ch-input-area">
        <input class="sp-ch-input" id="spInput"
               placeholder="Escribí tu consulta..."
               onkeydown="spHandleKey(event)"
               maxlength="500">
        <button class="sp-ch-send" id="spSendBtn" onclick="spSend()">
            <svg viewBox="0 0 24 24"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
        </button>
    </div>
</div>

<script>
(function() {
    var history  = [];
    var busy     = false;
    var opened   = false;

    window.spToggleChat = function() {
        var w = document.getElementById('sp-chat-window');
        var badge = document.getElementById('sp-chat-badge');
        opened = !opened;
        w.classList.toggle('open', opened);
        badge.style.display = 'none';
        if (opened) document.getElementById('spInput').focus();
    };

    function getTime() {
        var d = new Date();
        return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
    }

    function addMsg(role, text) {
        var msgs = document.getElementById('spMsgs');
        var wrap = document.createElement('div');
        wrap.className = 'sp-msg ' + role;

        var bubble = document.createElement('div');
        bubble.className = 'sp-bubble';

        var waRegex = /(https:\/\/wa\.me\/\S+)/g;
        if (role === 'bot' && waRegex.test(text)) {
            var parts = text.split(/(https:\/\/wa\.me\/\S+)/g);
            parts.forEach(function(p) {
                if (p.match(/^https:\/\/wa\.me\//)) {
                    var a = document.createElement('a');
                    a.href = p;
                    a.target = '_blank';
                    a.rel = 'noopener noreferrer';
                    a.textContent = 'Escribinos por WhatsApp';
                    bubble.appendChild(a);
                } else if (p) {
                    bubble.appendChild(document.createTextNode(p));
                }
            });
        } else {
            bubble.textContent = text;
        }

        var time = document.createElement('div');
        time.className = 'sp-msg-time';
        time.textContent = getTime();

        wrap.appendChild(bubble);
        wrap.appendChild(time);
        msgs.appendChild(wrap);
        msgs.scrollTop = msgs.scrollHeight;
    }

    function showTyping() {
        var msgs = document.getElementById('spMsgs');
        var t = document.createElement('div');
        t.id = 'spTyping';
        t.className = 'sp-typing';
        t.innerHTML = '<div class="sp-dot"></div><div class="sp-dot"></div><div class="sp-dot"></div>';
        msgs.appendChild(t);
        msgs.scrollTop = msgs.scrollHeight;
    }

    function hideTyping() {
        var t = document.getElementById('spTyping');
        if (t) t.remove();
    }

    window.spSend = function() {
        if (busy) return;
        var input = document.getElementById('spInput');
        var text  = input.value.trim();
        if (!text) return;

        document.getElementById('spQuick').style.display = 'none';

        addMsg('user', text);
        history.push({ role: 'user', content: text });
        input.value = '';
        busy = true;
        document.getElementById('spSendBtn').disabled = true;

        showTyping();

        var payload = 'messages=' + encodeURIComponent(JSON.stringify(history));

        fetch('<?= URL ?>asistente/mensaje', {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    payload
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            hideTyping();
            if (data.error) {
                addMsg('bot', 'No pude procesar tu consulta. Intentá de nuevo o escribinos por WhatsApp: https://wa.me/543543579974');
            } else {
                addMsg('bot', data.reply);
                history.push({ role: 'assistant', content: data.reply });
            }
        })
        .catch(function() {
            hideTyping();
            addMsg('bot', 'Hubo un error de conexión. Intentá de nuevo o escribinos: https://wa.me/543543579974');
        })
        .finally(function() {
            busy = false;
            document.getElementById('spSendBtn').disabled = false;
            document.getElementById('spInput').focus();
        });
    };

    window.spQuickSend = function(text) {
        document.getElementById('spInput').value = text;
        spSend();
    };

    window.spHandleKey = function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            spSend();
        }
    };

    setTimeout(function() {
        if (!opened) {
            document.getElementById('sp-chat-badge').style.display = 'block';
        }
    }, 3000);
})();
</script>