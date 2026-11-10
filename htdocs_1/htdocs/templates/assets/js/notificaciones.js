(function () {
    let notifCargadas = false;

    function iconoPorTipo(icono) {
        return icono && icono.trim() !== '' ? icono : 'fa-bell';
    }

    function renderNotificaciones(items) {
        const list = document.getElementById('notifList');
        if (!items || items.length === 0) {
            list.innerHTML = '<div class="notif-empty">No tenés notificaciones</div>';
            return;
        }

        list.innerHTML = items.map(function (n) {
            const url = n.UrlDestino ? (URL_BASE + n.UrlDestino) : '#';
            const noLeidaClass = parseInt(n.Leida) === 0 ? 'notif-item--unread' : '';
            return `
                <a href="${url}" class="notif-item ${noLeidaClass}">
                    <span class="notif-item-icon"><i class="fas ${iconoPorTipo(n.Icono)}"></i></span>
                    <span class="notif-item-body">
                        <span class="notif-item-title">${n.Titulo}</span>
                        ${n.Contenido ? `<span class="notif-item-content">${n.Contenido}</span>` : ''}
                        <span class="notif-item-date">${n.FechaTexto}</span>
                    </span>
                </a>`;
        }).join('');
    }

    function actualizarBadge(cantidad) {
        const badge = document.getElementById('notifBadge');
        if (!badge) return;
        if (cantidad > 0) {
            badge.style.display = 'inline-flex';
            badge.textContent = cantidad > 9 ? '9+' : cantidad;
        } else {
            badge.style.display = 'none';
        }
    }

    function cargarNotificaciones() {
        fetch(URL_BASE + 'notificacion/index')
            .then(res => res.json())
            .then(data => {
                if (!data.ok) return;
                renderNotificaciones(data.notificaciones);
                actualizarBadge(data.no_leidas);
            });
    }

    function marcarComoLeidas() {
        fetch(URL_BASE + 'notificacion/marcarleidas', { method: 'POST' })
            .then(() => {
                actualizarBadge(0);
                document.querySelectorAll('.notif-item--unread').forEach(el => {
                    el.classList.remove('notif-item--unread');
                });
            });
    }

    window.toggleNotifPanel = function () {
        const panel = document.getElementById('notifPanel');
        const abriendo = !panel.classList.contains('show');
        panel.classList.toggle('show');

        if (abriendo) {
            if (!notifCargadas) {
                cargarNotificaciones();
                notifCargadas = true;
            }
            marcarComoLeidas();
        }
    };

    document.addEventListener('click', function (e) {
        const wrap = document.querySelector('.notif-wrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('notifPanel')?.classList.remove('show');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        fetch(URL_BASE + 'notificacion/index')
            .then(res => res.json())
            .then(data => data.ok && actualizarBadge(data.no_leidas));

        setInterval(function () {
            if (!document.getElementById('notifPanel')?.classList.contains('show')) {
                fetch(URL_BASE + 'notificacion/index')
                    .then(res => res.json())
                    .then(data => data.ok && actualizarBadge(data.no_leidas));
            }
        }, 60000);
    });
})();