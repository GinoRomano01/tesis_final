(function () {
  'use strict';

  const cfg = window.SP_TRACKER || {};
  const ENDPOINT  = cfg.endpoint  || '/collect';
  const USUARIO   = cfg.usuarioId || null;
  const CLIENTE   = cfg.clienteId || null;
  const SESION    = cfg.sesionId  || null;
  const INICIO    = Date.now();
  function enviar(payload) {
    const datos = Object.assign({
      usuario_id: USUARIO,
      cliente_id: CLIENTE,
      sesion_id:  SESION,
    }, payload);

    const blob = new Blob([JSON.stringify(datos)], { type: 'application/json' });
    if (navigator.sendBeacon) {
      navigator.sendBeacon(ENDPOINT, blob);
    } else {
      fetch(ENDPOINT, { method: 'POST', body: blob, keepalive: true })
        .catch(function () {});
    }
  }

  enviar({
    tipo_evento:  'page_view',
    url_visitada: location.href,
    titulo:       document.title,
    referidor:    document.referrer || null,
    dispositivo:  detectarDispositivo(),
    navegador:    detectarNavegador(),
  });

  window.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'hidden') {
      enviar({
        tipo_evento:       'tiempo_pagina',
        url_visitada:      location.href,
        tiempo_en_pagina:  Math.round((Date.now() - INICIO) / 1000),
      });
    }
  });

  document.addEventListener('click', function (e) {
    const el = e.target.closest('[data-track]');
    if (!el) return;

    enviar({
      tipo_evento:    el.dataset.track       || 'clic',
      modulo:         el.dataset.modulo      || null,
      elemento_id:    el.dataset.elementoId  || null,
      elemento_tipo:  el.dataset.elementoTipo || null,
      valor_extra:    el.dataset.valor        || null,
      url_visitada:   location.href,
    });
  });

  document.addEventListener('submit', function (e) {
    const form = e.target.closest('[data-track-busqueda]');
    if (!form) return;

    const input = form.querySelector('input[name="buscar"], input[type="search"]');
    if (!input || !input.value.trim()) return;

    enviar({
      tipo_evento:     'busqueda',
      modulo:          'catalogo',
      valor_extra:     JSON.stringify({ query: input.value.trim() }),
      url_visitada:    location.href,
    });
  });

  function detectarDispositivo() {
    const ua = navigator.userAgent;
    if (/Mobi|Android/i.test(ua))  return 'mobile';
    if (/Tablet|iPad/i.test(ua))   return 'tablet';
    return 'desktop';
  }

  function detectarNavegador() {
    const ua = navigator.userAgent;
    if (/Firefox/i.test(ua))   return 'Firefox';
    if (/Edg/i.test(ua))       return 'Edge';
    if (/OPR|Opera/i.test(ua)) return 'Opera';
    if (/Chrome/i.test(ua))    return 'Chrome';
    if (/Safari/i.test(ua))    return 'Safari';
    return 'Otro';
  }

})();