const API = '../api';
let imagenesServicioActual = []; 

async function verificarSesion() {
  const res = await fetch(`${API}/check_session.php`);
  const data = await res.json();
  if (!data.autenticado) {
    if (!location.pathname.endsWith('login.html')) {
      window.location.href = 'login.html';
    }
    return null;
  }
  return data.usuario;
}

function initLogout() {
  const btn = document.getElementById('logout-btn');
  if (!btn) return;
  btn.addEventListener('click', async () => {
    await fetch(`${API}/logout.php`, { method: 'POST' });
    window.location.href = 'login.html';
  });
}

function initNav() {
  document.querySelectorAll('.sidebar nav a').forEach((link) => {
    link.addEventListener('click', () => {
      document.querySelectorAll('.sidebar nav a').forEach((l) => l.classList.remove('active'));
      link.classList.add('active');
      document.querySelectorAll('.main > section').forEach((s) => (s.style.display = 'none'));
      document.getElementById(`section-${link.dataset.section}`).style.display = 'block';

      if (link.dataset.section === 'estadisticas') cargarEstadisticas();
      if (link.dataset.section === 'servicios') cargarTablaServicios();
      if (link.dataset.section === 'clientes') cargarTablaClientes();
    });
  });
}

async function cargarEstadisticas() {
  const res = await fetch(`${API}/estadisticas.php`);
  const data = await res.json();

  const grid = document.getElementById('stats-grid');
  const t = data.totales;
  grid.innerHTML = `
    <div class="stat-card"><div class="num">${t.total_visitas}</div><div class="label">Visitas totales</div></div>
    <div class="stat-card"><div class="num">${t.total_clicks}</div><div class="label">Clicks registrados</div></div>
    <div class="stat-card"><div class="num">${t.total_preguntas}</div><div class="label">Preguntas al chatbot</div></div>
    <div class="stat-card"><div class="num">${t.total_clientes}</div><div class="label">Clientes cargados</div></div>
  `;

  const tbody = document.getElementById('tabla-preguntas');
  tbody.innerHTML = data.ultimas_preguntas.length
    ? data.ultimas_preguntas.map((p) => `
        <tr>
          <td>${formatFecha(p.fecha_hora)}</td>
          <td>${escapeHtml(p.pregunta)}</td>
          <td>${escapeHtml(p.respuesta || '')}</td>
        </tr>`).join('')
    : '<tr><td colspan="3">Todavía no hay preguntas registradas.</td></tr>';
}

let lineasCache = [];

async function cargarLineasSelect() {
  const res = await fetch(`${API}/lineas.php`);
  lineasCache = await res.json();
  const select = document.getElementById('servicio-linea');
  select.innerHTML = lineasCache.map((l) => `<option value="${l.id}">${l.nombre}</option>`).join('');

  const selectCliente = document.getElementById('cliente-servicio');
}

async function cargarTablaServicios() {
  const res = await fetch(`${API}/servicios.php`);
  const servicios = await res.json();
  const tbody = document.getElementById('tabla-servicios');

  if (!servicios.length) {
    tbody.innerHTML = '<tr><td colspan="6">No hay servicios cargados todavía.</td></tr>';
    return;
  }

  tbody.innerHTML = servicios.map((s) => `
    <tr>
      <td>${s.linea_nombre}</td>
      <td>${escapeHtml(s.nombre)}</td>
      <td>${s.precio_desde ? '$' + Number(s.precio_desde).toLocaleString('es-AR') : '—'}</td>
      <td>${(s.imagenes || []).length} imagen(es)</td>
      <td>${s.activo ? 'Activo' : 'Inactivo'}</td>
      <td>
        <button class="btn btn-outline btn-small" onclick="abrirModalServicio(${s.id})">Editar</button>
        <button class="btn btn-danger btn-small" onclick="eliminarServicio(${s.id})">Eliminar</button>
      </td>
    </tr>
  `).join('');

  cargarServiciosSelectCliente(servicios);
}

function cargarServiciosSelectCliente(servicios) {
  const select = document.getElementById('cliente-servicio');
  const actual = select.value;
  select.innerHTML = '<option value="">— Sin especificar —</option>' +
    servicios.map((s) => `<option value="${s.id}">${s.nombre}</option>`).join('');
  select.value = actual;
}

async function abrirModalServicio(id = null) {
  document.getElementById('form-servicio').reset();
  document.getElementById('servicio-preview').innerHTML = '';
  imagenesServicioActual = [];
  document.getElementById('servicio-id').value = '';

  await cargarLineasSelect();

  if (id) {
    const res = await fetch(`${API}/servicios.php?id=${id}`);
    const s = await res.json();
    document.getElementById('modal-servicio-titulo').textContent = 'Editar servicio';
    document.getElementById('servicio-id').value = s.id;
    document.getElementById('servicio-linea').value = s.linea_id;
    document.getElementById('servicio-nombre').value = s.nombre;
    document.getElementById('servicio-descripcion').value = s.descripcion || '';
    document.getElementById('servicio-precio').value = s.precio_desde || '';
    document.getElementById('servicio-orden').value = s.orden || 0;
    imagenesServicioActual = (s.imagenes || []).map((i) => i.imagen_url || i);
    renderPreviewImagenes();
  } else {
    document.getElementById('modal-servicio-titulo').textContent = 'Nuevo servicio';
  }

  document.getElementById('modal-servicio').classList.add('abierto');
}

function renderPreviewImagenes() {
  const cont = document.getElementById('servicio-preview');
  cont.innerHTML = imagenesServicioActual.map((url, i) =>
    `<div style="position:relative;">
       <img src="../${url}">
       <span onclick="quitarImagen(${i})" style="position:absolute;top:-6px;right:-6px;background:#C0392B;color:white;border-radius:50%;width:18px;height:18px;font-size:12px;line-height:18px;text-align:center;cursor:pointer;">×</span>
     </div>`
  ).join('');
}

function quitarImagen(i) {
  imagenesServicioActual.splice(i, 1);
  renderPreviewImagenes();
}

async function subirImagenesSeleccionadas() {
  const input = document.getElementById('servicio-imagen-input');
  for (const file of input.files) {
    const formData = new FormData();
    formData.append('imagen', file);
    const res = await fetch(`${API}/upload_imagen.php`, { method: 'POST', body: formData });
    const data = await res.json();
    if (data.ok) imagenesServicioActual.push(data.url);
  }
  input.value = '';
  renderPreviewImagenes();
}

async function eliminarServicio(id) {
  if (!confirm('¿Dar de baja este servicio? Dejará de mostrarse en el sitio público.')) return;
  await fetch(`${API}/servicios.php`, {
    method: 'DELETE',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id }),
  });
  cargarTablaServicios();
}

function initFormServicio() {
  document.getElementById('btn-nuevo-servicio').addEventListener('click', () => abrirModalServicio());
  document.getElementById('cerrar-modal-servicio').addEventListener('click', () =>
    document.getElementById('modal-servicio').classList.remove('abierto'));

  document.getElementById('servicio-imagen-input').addEventListener('change', subirImagenesSeleccionadas);

  document.getElementById('form-servicio').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('servicio-id').value;
    const payload = {
      linea_id: document.getElementById('servicio-linea').value,
      nombre: document.getElementById('servicio-nombre').value,
      descripcion: document.getElementById('servicio-descripcion').value,
      precio_desde: document.getElementById('servicio-precio').value || null,
      orden: document.getElementById('servicio-orden').value || 0,
      imagenes: imagenesServicioActual,
      imagen_url: imagenesServicioActual[0] || null,
    };

    const method = id ? 'PUT' : 'POST';
    if (id) payload.id = id;

    await fetch(`${API}/servicios.php`, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });

    document.getElementById('modal-servicio').classList.remove('abierto');
    cargarTablaServicios();
  });
}

async function cargarTablaClientes() {
  const res = await fetch(`${API}/clientes.php`);
  const clientes = await res.json();
  const tbody = document.getElementById('tabla-clientes');

  if (!clientes.length) {
    tbody.innerHTML = '<tr><td colspan="6">No hay clientes cargados todavía.</td></tr>';
    return;
  }

  tbody.innerHTML = clientes.map((c) => `
    <tr>
      <td>${formatFecha(c.fecha_solicitud)}</td>
      <td>${escapeHtml(c.nombre)}</td>
      <td>${escapeHtml(c.servicio_nombre || '—')}</td>
      <td>${escapeHtml(c.localidad || '—')}</td>
      <td><span class="badge badge-${c.estado}">${c.estado.replace('_',' ')}</span></td>
      <td>
        <button class="btn btn-outline btn-small" onclick="abrirModalCliente(${c.id})">Editar</button>
        <button class="btn btn-danger btn-small" onclick="eliminarCliente(${c.id})">Eliminar</button>
      </td>
    </tr>
  `).join('');
}

async function abrirModalCliente(id = null) {
  document.getElementById('form-cliente').reset();
  document.getElementById('cliente-id').value = '';

  const resServicios = await fetch(`${API}/servicios.php`);
  cargarServiciosSelectCliente(await resServicios.json());

  if (id) {
    const res = await fetch(`${API}/clientes.php?id=${id}`);
    const c = await res.json();
    document.getElementById('modal-cliente-titulo').textContent = 'Editar cliente';
    document.getElementById('cliente-id').value = c.id;
    document.getElementById('cliente-nombre').value = c.nombre;
    document.getElementById('cliente-telefono').value = c.telefono || '';
    document.getElementById('cliente-email').value = c.email || '';
    document.getElementById('cliente-domicilio').value = c.domicilio || '';
    document.getElementById('cliente-localidad').value = c.localidad || '';
    document.getElementById('cliente-servicio').value = c.servicio_id || '';
    document.getElementById('cliente-fecha').value = (c.fecha_solicitud || '').replace(' ', 'T').slice(0, 16);
    document.getElementById('cliente-estado').value = c.estado;
    document.getElementById('cliente-observaciones').value = c.observaciones || '';
  } else {
    document.getElementById('modal-cliente-titulo').textContent = 'Nuevo cliente';
    const ahora = new Date().toISOString().slice(0, 16);
    document.getElementById('cliente-fecha').value = ahora;
  }

  document.getElementById('modal-cliente').classList.add('abierto');
}

async function eliminarCliente(id) {
  if (!confirm('¿Eliminar este cliente definitivamente?')) return;
  await fetch(`${API}/clientes.php`, {
    method: 'DELETE',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id }),
  });
  cargarTablaClientes();
}

function initFormCliente() {
  document.getElementById('btn-nuevo-cliente').addEventListener('click', () => abrirModalCliente());
  document.getElementById('cerrar-modal-cliente').addEventListener('click', () =>
    document.getElementById('modal-cliente').classList.remove('abierto'));

  document.getElementById('form-cliente').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('cliente-id').value;
    const fecha = document.getElementById('cliente-fecha').value;

    const payload = {
      nombre: document.getElementById('cliente-nombre').value,
      telefono: document.getElementById('cliente-telefono').value,
      email: document.getElementById('cliente-email').value,
      domicilio: document.getElementById('cliente-domicilio').value,
      localidad: document.getElementById('cliente-localidad').value,
      servicio_id: document.getElementById('cliente-servicio').value || null,
      fecha_solicitud: fecha ? fecha.replace('T', ' ') + ':00' : null,
      estado: document.getElementById('cliente-estado').value,
      observaciones: document.getElementById('cliente-observaciones').value,
    };

    const method = id ? 'PUT' : 'POST';
    if (id) payload.id = id;

    await fetch(`${API}/clientes.php`, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });

    document.getElementById('modal-cliente').classList.remove('abierto');
    cargarTablaClientes();
  });
}

function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str ?? '';
  return div.innerHTML;
}

function formatFecha(fechaStr) {
  if (!fechaStr) return '—';
  const d = new Date(fechaStr.replace(' ', 'T'));
  return d.toLocaleString('es-AR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

document.addEventListener('DOMContentLoaded', async () => {
  if (!document.getElementById('section-estadisticas')) return;

  const usuario = await verificarSesion();
  if (!usuario) return;
  document.getElementById('nombre-usuario').textContent = usuario.nombre;

  initLogout();
  initNav();
  initFormServicio();
  initFormCliente();
  cargarEstadisticas();
});
