console.log("San Plácido - sistema cargado");

(() => {
  const saved = localStorage.getItem("theme");
  if (saved === "light") document.body.classList.add("light");
})();

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("themeToggle");
  if (!btn) return;
  btn.textContent = document.body.classList.contains("light") ? "🌙" : "☀️";
  btn.addEventListener("click", () => {
    const isLight = document.body.classList.toggle("light");
    localStorage.setItem("theme", isLight ? "light" : "dark");
    btn.textContent = isLight ? "🌙" : "☀️";
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const notifBtn   = document.getElementById("notifBtn");
  const notifPanel = document.getElementById("notifPanel");
  if (!notifBtn || !notifPanel) return;

  notifBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    const isOpen = notifPanel.classList.toggle("open");
    notifBtn.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });

  document.addEventListener("click", () => {
    if (notifPanel.classList.contains("open")) {
      notifPanel.classList.remove("open");
      notifBtn.setAttribute("aria-expanded", "false");
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && notifPanel.classList.contains("open")) {
      notifPanel.classList.remove("open");
      notifBtn.setAttribute("aria-expanded", "false");
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const toggle  = document.getElementById("menuToggle");
  const sidebar = document.querySelector(".sidebar");
  if (!toggle || !sidebar) return;
  toggle.addEventListener("click", () => sidebar.classList.toggle("closed"));
});

document.addEventListener("DOMContentLoaded", () => {
  const toggles = document.querySelectorAll(".nav-group .nav-toggle");
  toggles.forEach(btn => {
    btn.addEventListener("click", () => {
      const group  = btn.closest(".nav-group");
      const isOpen = group.classList.toggle("open");
      btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });
  });
});

function showToast(msg = "Hecho") {
  const t = document.getElementById("toast");
  if (!t) return;
  t.textContent = msg;
  t.classList.add("show");
  setTimeout(() => t.classList.remove("show"), 2000);
}

document.addEventListener("DOMContentLoaded", () => {
  const modal  = document.getElementById("modalEntrega");
  const abrir  = document.getElementById("nuevaEntregaBtn");
  const cerrar = document.getElementById("cerrarModalEntrega");
  const form   = document.getElementById("formEntrega");

  if (!modal) return;

  if (abrir)  abrir.addEventListener("click",  () => modal.classList.remove("hidden"));
  if (cerrar) cerrar.addEventListener("click", () => modal.classList.add("hidden"));
  modal.addEventListener("click", (e) => { if (e.target === modal) modal.classList.add("hidden"); });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !modal.classList.contains("hidden"))
      modal.classList.add("hidden");
  });

  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      modal.classList.add("hidden");
      form.reset();
      showToast("Entrega guardada");
    });
  }
});