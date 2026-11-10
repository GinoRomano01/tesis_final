<?php if (NotificacionModel::actorActual()): ?>
<div class="notif-wrap">
    <button class="notif-btn" id="notifBtn" onclick="toggleNotifPanel()" aria-haspopup="true" title="Notificaciones">
        <i class="fas fa-bell"></i>
        <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
    </button>

    <div class="notif-panel" id="notifPanel">
        <div class="notif-panel-head">
            <span>Notificaciones</span>
        </div>

        <div class="notif-list" id="notifList">
            <div class="notif-empty">Cargando...</div>
        </div>

        <div class="notif-panel-foot">
            <a href="<?= URL ?>notificacion/listado">Ver todas</a>
        </div>
    </div>
</div>
<?php endif; ?>