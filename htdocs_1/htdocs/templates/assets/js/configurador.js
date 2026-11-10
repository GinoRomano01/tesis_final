const DATA = {
  placard: {
    nombre: "Placard",
    modelos: {
      p2: { nombre: "2 puertas corredizas", precio: 180000 },
      p3: { nombre: "3 puertas corredizas", precio: 220000 },
      p2e: { nombre: "2 puertas con espejo", precio: 210000 }
    }
  },
  mesa_luz: {
    nombre: "Mesa de luz",
    modelos: {
      ml1: { nombre: "1 cajón", precio: 45000 },
      ml2: { nombre: "2 cajones", precio: 55000 }
    }
  },
  comoda_tv: {
    nombre: "Cómoda TV",
    modelos: {
      tv1: { nombre: "2 cajones + estante", precio: 95000 },
      tv2: { nombre: "4 cajones", precio: 120000 }
    }
  },
  cajonera: {
    nombre: "Cajonera",
    modelos: {
      cj3: { nombre: "3 cajones", precio: 75000 },
      cj5: { nombre: "5 cajones", precio: 98000 }
    }
  },
  bajo_mesada: {
    nombre: "Bajo mesada",
    modelos: {
      bm2: { nombre: "2 puertas", precio: 160000 },
      bm3: { nombre: "3 puertas", precio: 185000 }
    }
  },
  alacena: {
    nombre: "Alacena",
    modelos: {
      al2: { nombre: "2 puertas", precio: 140000 },
      al3: { nombre: "3 puertas", precio: 165000 }
    }
  },
  isla: {
    nombre: "Isla de cocina",
    modelos: {
      is1: { nombre: "Simple", precio: 260000 },
      is2: { nombre: "Con banquetas", precio: 310000 }
    }
  },
  respaldo: {
    nombre: "Respaldo de cama",
    modelos: {
      r1: { nombre: "Liso", precio: 70000 },
      r2: { nombre: "Con mesas integradas", precio: 95000 }
    }
  }
};

const COLORS = [
  { key: "blanco", nombre: "Blanco", hex: "#f5f5f5" },
  { key: "roble", nombre: "Roble", hex: "#c49a6c" },
  { key: "nogal", nombre: "Nogal", hex: "#5c3a21" }
];

let state = {
  product: null,
  model: null,
  color: null,
  price: 0
};

const productList = document.getElementById("product-list");
const modelList = document.getElementById("model-list");
const colorList = document.getElementById("color-list");
const mainImage = document.getElementById("main-image");
const priceDisplay = document.getElementById("price");

const productSection = document.getElementById("product-section");
const modelSection = document.getElementById("model-section");
const detailSection = document.getElementById("detail-section");

const modelTitle = document.getElementById("model-title");
const selectedModelName = document.getElementById("selected-model-name");

const quoteBtn = document.getElementById("quoteBtn");
const quoteModal = document.getElementById("quoteModal");
const closeModal = document.getElementById("closeModal");
const confirmQuote = document.getElementById("confirmQuote");
const modalImage = document.getElementById("modalImage");
const modalSummary = document.getElementById("modalSummary");
const modalPrice = document.getElementById("modalPrice");

const backToProducts = document.getElementById("backToProducts");
const backToModels = document.getElementById("backToModels");

function goToProducts() {
  productSection.classList.remove("hidden");
  modelSection.classList.add("hidden");
  detailSection.classList.add("hidden");
  
  state.product = null;
  state.model = null;
  state.color = null;
  state.price = 0;
  
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goToModels() {
  productSection.classList.add("hidden");
  modelSection.classList.remove("hidden");
  detailSection.classList.add("hidden");
  
  state.model = null;
  state.color = null;
  state.price = 0;
  
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goToDetail() {
  productSection.classList.add("hidden");
  modelSection.classList.add("hidden");
  detailSection.classList.remove("hidden");
  
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function renderProducts() {
  productList.innerHTML = "";
  
  Object.keys(DATA).forEach(key => {
    const product = DATA[key];
    const btn = document.createElement("button");
    btn.textContent = product.nombre;
    btn.onclick = () => selectProduct(key);
    productList.appendChild(btn);
  });
}

function selectProduct(productKey) {
  state.product = productKey;
  state.model = null;
  state.color = null;
  
  productSection.classList.add("hidden");
  modelSection.classList.remove("hidden");
  detailSection.classList.add("hidden");
  
  modelTitle.textContent = `Modelos de ${DATA[productKey].nombre}`;
  
  renderModels();
  
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function renderModels() {
  modelList.innerHTML = "";
  
  const modelos = DATA[state.product].modelos;
  
  Object.keys(modelos).forEach(key => {
    const modelo = modelos[key];
    
    const card = document.createElement("div");
    card.className = "model-card";
    
    card.innerHTML = `
      <div class="icon-placeholder">
        <i class="fas fa-cube"></i>
      </div>
      <h4>${modelo.nombre}</h4>
      <p>$${Number(modelo.precio).toLocaleString('es-AR')}</p>
    `;
    
    card.onclick = () => selectModel(key);
    modelList.appendChild(card);
  });
}

function selectModel(modelKey) {
  state.model = modelKey;
  state.color = "blanco";
  state.price = DATA[state.product].modelos[modelKey].precio;
  
  goToDetail();
  
  selectedModelName.textContent = DATA[state.product].modelos[modelKey].nombre;
  
  renderColors();
  updateImage();
  updatePrice();
}

function renderColors() {
  colorList.innerHTML = "";
  
  COLORS.forEach(color => {
    const circle = document.createElement("div");
    circle.className = "color-circle";
    if (color.key === state.color) {
      circle.classList.add("active");
    }
    circle.style.backgroundColor = color.hex;
    circle.title = color.nombre;
    circle.onclick = () => selectColor(color.key);
    colorList.appendChild(circle);
  });
}

function selectColor(colorKey) {
  state.color = colorKey;
  renderColors();
  updateImage();
}

function updateImage() {
  const imageArea = document.querySelector('.image-area');
  
  const backButton = imageArea.querySelector('.btn-back');
  imageArea.innerHTML = '';
  
  if (backButton) {
    imageArea.appendChild(backButton);
  }
  
  const placeholder = document.createElement('div');
  placeholder.style.cssText = `
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #c9a227;
    padding: 40px;
  `;
  
  placeholder.innerHTML = `
    <i class="fas fa-box-open" style="font-size: 80px; margin-bottom: 20px; opacity: 0.5;"></i>
    <p style="font-size: 18px; color: #eee; margin-bottom: 10px;">${DATA[state.product].nombre}</p>
    <p style="font-size: 16px; color: #888;">${DATA[state.product].modelos[state.model].nombre}</p>
    <p style="font-size: 14px; color: #666; margin-top: 15px;">Color: ${COLORS.find(c => c.key === state.color).nombre}</p>
  `;
  
  imageArea.appendChild(placeholder);
}

function updatePrice() {
  priceDisplay.textContent = `$${Number(state.price).toLocaleString('es-AR')}`;
}

quoteBtn.addEventListener("click", () => {
  const modalImageContainer = document.createElement('div');
  modalImageContainer.style.cssText = `
    width: 100%;
    height: 250px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #1a1a1f;
    border-radius: 12px;
    margin-bottom: 25px;
  `;
  
  modalImageContainer.innerHTML = `
    <i class="fas fa-check-circle" style="font-size: 60px; color: #c9a227; margin-bottom: 15px;"></i>
    <p style="color: #eee; font-size: 16px;">${DATA[state.product].nombre}</p>
    <p style="color: #888; font-size: 14px;">${DATA[state.product].modelos[state.model].nombre}</p>
  `;
  
  const modalContent = quoteModal.querySelector('.modal-content');
  const existingImage = modalContent.querySelector('img');
  if (existingImage) {
    existingImage.replaceWith(modalImageContainer);
  }
  
  modalSummary.innerHTML = `
    <li><span>Producto:</span> <strong>${DATA[state.product].nombre}</strong></li>
    <li><span>Modelo:</span> <strong>${DATA[state.product].modelos[state.model].nombre}</strong></li>
    <li><span>Color:</span> <strong>${COLORS.find(c => c.key === state.color).nombre}</strong></li>
  `;
  
  modalPrice.textContent = `$${Number(state.price).toLocaleString('es-AR')}`;
  
  quoteModal.classList.remove("hidden");
});

closeModal.addEventListener("click", () => {
  quoteModal.classList.add("hidden");
});

confirmQuote.addEventListener("click", () => {
  const producto = DATA[state.product].nombre;
  const modelo = DATA[state.product].modelos[state.model].nombre;
  const color = COLORS.find(c => c.key === state.color).nombre;
  const precio = Number(state.price).toLocaleString('es-AR');
  
  const mensaje = encodeURIComponent(
    `Hola! Quiero solicitar un presupuesto:\n\n` +
    `📦 Producto: ${producto}\n` +
    `🔧 Modelo: ${modelo}\n` +
    `🎨 Color: ${color}\n` +
    `💰 Precio: $${precio}`
  );
  
  const whatsappURL = `https://wa.me/5493512345678?text=${mensaje}`;
  window.open(whatsappURL, '_blank');
  
  quoteModal.classList.add("hidden");
});

quoteModal.addEventListener("click", (e) => {
  if (e.target === quoteModal) {
    quoteModal.classList.add("hidden");
  }
});

backToProducts.addEventListener("click", () => {
  goToProducts();
});

backToModels.addEventListener("click", () => {
  goToModels();
  renderModels();
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && !quoteModal.classList.contains('hidden')) {
    quoteModal.classList.add('hidden');
  }
});

document.addEventListener("DOMContentLoaded", () => {
  console.log('🎨 Configurador iniciado');
  console.log('📦 Productos disponibles:', Object.keys(DATA).length);
  renderProducts();
});