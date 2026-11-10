<?php
$title = 'Configurador de Muebles - San Plácido';
include INCLUDES . 'header_cliente.php';
?>

<link rel="stylesheet" href="<?= URL ?>templates/assets/css/configurador.css">

<div class="configurador-wrapper">
    
    <header class="config-header">
        <h1>Configurador Premium</h1>
        <p>Seleccioná un modelo y personalizalo</p>
    </header>

    <main class="main-container">

        
        <section id="product-section">
            <h2>Elegí el producto</h2>
            <div class="grid" id="product-list"></div>
        </section>

        
        <section id="model-section" class="hidden">
            <div class="section-header">
                <button class="btn-back" id="backToProducts">
                    <i class="fas fa-arrow-left"></i> Volver a productos
                </button>
                <h2 id="model-title"></h2>
            </div>
            <div class="grid" id="model-list"></div>
        </section>

        
        <section id="detail-section" class="hidden detail-layout">

            <div class="image-area">
                <button class="btn-back" id="backToModels">
                    <i class="fas fa-arrow-left"></i> Volver a modelos
                </button>
                <img id="main-image" src="" alt="Producto">
            </div>

            <div class="config-area">
                <h3 id="selected-model-name"></h3>

                <div class="color-selector">
                    <h4>Colores disponibles</h4>
                    <div id="color-list"></div>
                </div>

                <div class="price-area">
                    <span>Precio:</span>
                    <div id="price">$0</div>
                </div>

                <button class="btn" id="quoteBtn">Solicitar presupuesto</button>
            </div>

        </section>

    </main>

</div>


<div id="quoteModal" class="modal hidden">
    <div class="modal-content">

        <h2>Resumen del pedido</h2>

        <img id="modalImage" src="" alt="Producto seleccionado">

        <ul id="modalSummary"></ul>

        <div class="modal-price" id="modalPrice"></div>

        <div class="modal-buttons">
            <button id="confirmQuote" class="btn">Enviar por WhatsApp</button>
            <button id="closeModal" class="btn btn-secondary">Cancelar</button>
        </div>

    </div>
</div>

<script>
const IMG_BASE = '<?= URL ?>templates/assets/imagenes/configurador/';
</script>
<script src="<?= URL ?>templates/assets/js/configurador.js"></script>

<?php include INCLUDES . 'footer_cliente.php'; ?>