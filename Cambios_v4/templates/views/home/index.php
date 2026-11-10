<?php include INCLUDES . 'header_cliente.php'; ?>

<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <a href="<?= URL ?>catalogo">
                <img src="<?= IMG_HERO ?>hero1.jpg" class="d-block w-100" alt="Diseños Personalizados"
                     style="height:500px;object-fit:cover;background:#f0f0f0;cursor:pointer;"
                     onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22500%22%3E%3Crect fill=%22%23f0f0f0%22 width=%22800%22 height=%22500%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 font-size=%2240%22%3EImagen no disponible%3C/text%3E%3C/svg%3E'">
            </a>
            <div class="carousel-caption d-none d-md-block" style="pointer-events:none;">
                <h1 class="display-4 fw-bold text-shadow">Diseños Personalizados</h1>
                <p class="lead text-shadow">Tu idea, nosotros la convertimos en madera.</p>
            </div>
        </div>
        <div class="carousel-item">
            <a href="<?= URL ?>catalogo">
                <img src="<?= IMG_HERO ?>hero2.jpg" class="d-block w-100" alt="Muebles de Calidad"
                     style="height:500px;object-fit:cover;background:#f0f0f0;cursor:pointer;"
                     onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22500%22%3E%3Crect fill=%22%23e0e0e0%22 width=%22800%22 height=%22500%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 font-size=%2240%22%3EMuebles de Calidad%3C/text%3E%3C/svg%3E'">
            </a>
            <div class="carousel-caption d-none d-md-block" style="pointer-events:none;">
                <h1 class="display-4 fw-bold text-shadow">Muebles de Calidad</h1>
                <p class="lead text-shadow">Diseños que duran toda la vida.</p>
            </div>
        </div>
        <div class="carousel-item">
            <a href="<?= URL ?>catalogo">
                <img src="<?= IMG_HERO ?>hero3.jpg" class="d-block w-100" alt="Acabados Perfectos"
                     style="height:500px;object-fit:cover;background:#f0f0f0;cursor:pointer;"
                     onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22500%22%3E%3Crect fill=%22%23d0d0d0%22 width=%22800%22 height=%22500%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 font-size=%2240%22%3EAcabados Perfectos%3C/text%3E%3C/svg%3E'">
            </a>
            <div class="carousel-caption d-none d-md-block" style="pointer-events:none;">
                <h1 class="display-4 fw-bold text-shadow">Acabados Perfectos</h1>
                <p class="lead text-shadow">Atención al detalle en cada proyecto.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<div class="container mt-5">

    <div class="container mt-2 mb-5">
        <h2 class="text-center fw-bold mb-2" style="font-family:'Playfair Display',serif;color:#5c2d0a;">
            Nos Dedicamos 
        </h2>
        <p class="text-center text-muted mb-5"> A la fabricación de muebles de madera a medida para cada rincón de tu hogar</p>
        <?php
        $dedicaciones = [
            ['titulo' => 'Mesas de Noche',      'texto' => 'Diseñamos mesas de noche a medida que combinan funcionalidad y estética para tu dormitorio.',        'img' => IMG . 'info/dedicacion1.jpg'],
            ['titulo' => 'Placares',             'texto' => 'Placares a medida que aprovechan cada centímetro de tu espacio con elegancia.',                       'img' => IMG . 'info/dedicacion2.jpg'],
            ['titulo' => 'Aberturas de Madera', 'texto' => 'Puertas y ventanas de madera, trabajadas artesanalmente para cada estilo de hogar.',                   'img' => IMG . 'info/dedicacion3.jpg'],
            ['titulo' => 'Mesadas de Cocina',   'texto' => 'Realizamos la parte de madera de mesadas de cocina con acabados resistentes y duraderos.',             'img' => IMG . 'info/dedicacion4.jpg'],
            ['titulo' => 'Muebles de Interior', 'texto' => 'Bibliotecas, aparadores, muebles de TV y todo lo que necesitás para tu interior.',                    'img' => IMG . 'info/dedicacion5.jpg'],
            ['titulo' => 'Muebles de Todo Tipo','texto' => 'Si lo podés imaginar en madera, nosotros lo podemos construir. Consultanos sin compromiso.',           'img' => IMG . 'info/dedicacion6.jpg'],
        ];
        ?>
        <div class="row g-4">
            <?php foreach ($dedicaciones as $index => $item): $inv = ($index % 2 !== 0); ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="d-flex align-items-center gap-3 p-3 rounded-4 h-100"
                     style="background:#fff8f0;border:1px solid #e8dfd0;">
                    <?php if ($inv): ?>
                        <div style="flex:1;min-width:0;">
                            <h5 class="fw-bold mb-1" style="color:#5c2d0a;font-family:'Playfair Display',serif;"><?= $item['titulo'] ?></h5>
                            <p class="text-muted small mb-0 lh-lg"><?= $item['texto'] ?></p>
                        </div>
                        <div class="flex-shrink-0 rounded-3 overflow-hidden" style="width:100px;height:100px;">
                            <img src="<?= $item['img'] ?>" alt="<?= $item['titulo'] ?>" style="width:100%;height:100%;object-fit:cover;"
                                 onerror="this.onerror=null;this.parentElement.innerHTML='<div style=\'width:100px;height:100px;background:#e8dfd0;border-radius:8px;display:flex;align-items:center;justify-content:center;\'><i class=\'fas fa-hammer\' style=\'color:#c9a84c;font-size:1.8rem;\'></i></div>'">
                        </div>
                    <?php else: ?>
                        <div class="flex-shrink-0 rounded-3 overflow-hidden" style="width:100px;height:100px;">
                            <img src="<?= $item['img'] ?>" alt="<?= $item['titulo'] ?>" style="width:100%;height:100%;object-fit:cover;"
                                 onerror="this.onerror=null;this.parentElement.innerHTML='<div style=\'width:100px;height:100px;background:#e8dfd0;border-radius:8px;display:flex;align-items:center;justify-content:center;\'><i class=\'fas fa-hammer\' style=\'color:#c9a84c;font-size:1.8rem;\'></i></div>'">
                        </div>
                        <div style="flex:1;min-width:0;">
                            <h5 class="fw-bold mb-1" style="color:#5c2d0a;font-family:'Playfair Display',serif;"><?= $item['titulo'] ?></h5>
                            <p class="text-muted small mb-0 lh-lg"><?= $item['texto'] ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <h2 class="text-center fw-bold mb-2" style="font-family:'Playfair Display',serif;color:#5c2d0a;">
            Te Ofrecemos
        </h2>
        <p class="text-center text-muted mb-5">Calidad, dedicación y experiencia en cada proyecto</p>
        <?php
        $ofertas = [
            ['titulo' => 'Diseño Personalizado',   'texto' => 'Cada mueble es único, adaptado a tu espacio y estilo de vida.',                                    'img' => IMG . 'info/oferta1.jpg'],
            ['titulo' => 'Materiales de Calidad',  'texto' => 'Trabajamos con maderas seleccionadas y terminaciones de primera línea.',                            'img' => IMG . 'info/oferta2.jpg'],
            ['titulo' => 'Carpintería Artesanal',  'texto' => 'Cada detalle es trabajado a mano por maestros carpinteros con años de experiencia.',                'img' => IMG . 'info/oferta3.jpg'],
            ['titulo' => 'Entregas a Domicilio',   'texto' => 'Llevamos tus muebles hasta tu puerta con cuidado y puntualidad.',                                  'img' => IMG . 'info/oferta4.jpg'],
            ['titulo' => 'Garantía en cada Obra',  'texto' => 'Respaldamos nuestro trabajo con garantía real sobre materiales y mano de obra.',                   'img' => IMG . 'info/oferta5.jpg'],
            ['titulo' => 'Asesoramiento Gratuito', 'texto' => 'Te ayudamos a elegir el diseño ideal sin costo adicional.',                                        'img' => IMG . 'info/oferta6.jpg'],
        ];
        ?>
        <div class="row g-4">
            <?php foreach ($ofertas as $index => $item): $inv = ($index % 2 !== 0); ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="d-flex align-items-center gap-3 p-3 rounded-4 h-100"
                     style="background:#faf7f2;border:1px solid #e8dfd0;">
                    <?php if ($inv): ?>
                        <div style="flex:1;min-width:0;">
                            <h5 class="fw-bold mb-1" style="color:#5c2d0a;font-family:'Playfair Display',serif;"><?= $item['titulo'] ?></h5>
                            <p class="text-muted small mb-0 lh-lg"><?= $item['texto'] ?></p>
                        </div>
                        <div class="flex-shrink-0 rounded-3 overflow-hidden" style="width:100px;height:100px;">
                            <img src="<?= $item['img'] ?>" alt="<?= $item['titulo'] ?>" style="width:100%;height:100%;object-fit:cover;"
                                 onerror="this.onerror=null;this.parentElement.innerHTML='<div style=\'width:100px;height:100px;background:#e8dfd0;border-radius:8px;display:flex;align-items:center;justify-content:center;\'><i class=\'fas fa-image\' style=\'color:#c9a84c;font-size:1.8rem;\'></i></div>'">
                        </div>
                    <?php else: ?>
                        <div class="flex-shrink-0 rounded-3 overflow-hidden" style="width:100px;height:100px;">
                            <img src="<?= $item['img'] ?>" alt="<?= $item['titulo'] ?>" style="width:100%;height:100%;object-fit:cover;"
                                 onerror="this.onerror=null;this.parentElement.innerHTML='<div style=\'width:100px;height:100px;background:#e8dfd0;border-radius:8px;display:flex;align-items:center;justify-content:center;\'><i class=\'fas fa-image\' style=\'color:#c9a84c;font-size:1.8rem;\'></i></div>'">
                        </div>
                        <div style="flex:1;min-width:0;">
                            <h5 class="fw-bold mb-1" style="color:#5c2d0a;font-family:'Playfair Display',serif;"><?= $item['titulo'] ?></h5>
                            <p class="text-muted small mb-0 lh-lg"><?= $item['texto'] ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <h2 class="text-center mb-4" style="font-family:'Playfair Display',serif;color:#5c2d0a;">Categorías</h2>
    <div id="carouselCategorias" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            if (!empty($categorias)) {
                $chunks = array_chunk($categorias, 6);
                foreach ($chunks as $index => $grupo) {
                    echo '<div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">';
                    echo '<div class="d-flex justify-content-center gap-3 flex-wrap">';
                    foreach ($grupo as $cat) {
                        echo '
                        <a href="' . URL . 'catalogo?categoria=' . $cat['Id'] . '" class="text-decoration-none">
                            <div class="card text-center shadow-sm hover-card" style="width:10rem;">
                                <div class="card-body p-3">
                                    <i class="fas fa-tag fa-2x mb-2" style="color:#b8722a;"></i>
                                    <p class="card-text fw-bold mb-0 text-dark">' . htmlspecialchars($cat['Nombre']) . '</p>
                                </div>
                            </div>
                        </a>';
                    }
                    echo '</div></div>';
                }
            } else {
                echo '<div class="carousel-item active"><p class="text-center text-muted">No hay categorías disponibles</p></div>';
            }
            ?>
        </div>
        <?php if (!empty($categorias) && count($categorias) > 6): ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCategorias" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselCategorias" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family:'Playfair Display',serif;color:#5c2d0a;">Productos Populares</h2>
        <a href="<?= URL ?>catalogo" style="color:#b8722a;font-weight:700;text-decoration:none;">Ver todo →</a>
    </div>

    <?php if (!empty($productosVendidos)): ?>
    <div class="row mb-5">
        <?php foreach ($productosVendidos as $p): ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm hover-card">
                <?php if (!empty($p['URLImagen'])): ?>
                    <img src="<?= htmlspecialchars($p['URLImagen']) ?>"
                         class="card-img-top"
                         alt="<?= htmlspecialchars($p['NombredelProducto']) ?>"
                         style="height:200px;object-fit:cover;"
                         onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'card-img-top d-flex align-items-center justify-content-center bg-light\' style=\'height:200px;\'><i class=\'fas fa-couch fa-4x text-muted\'></i></div>'">
                <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:200px;">
                        <i class="fas fa-couch fa-4x text-muted"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#5c2d0a;font-family:'Playfair Display',serif;">
                        <?= htmlspecialchars($p['NombredelProducto']) ?>
                    </h5>
                    <?php if (!empty($p['NombreCategoria'])): ?>
                        <p class="text-muted small mb-1"><?= htmlspecialchars($p['NombreCategoria']) ?></p>
                    <?php endif; ?>
                    <p class="fw-bold fs-5 mb-2" style="color:#2e6b3a;">
                        $<?= number_format($p['PrecioVenta'], 2, ',', '.') ?>
                    </p>
                    <a href="<?= URL ?>productocliente/<?= $p['Id'] ?>" class="btn w-100"
                       style="background:#5c2d0a;color:#fff;border:none;">
                        Ver más
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-center text-muted mb-5">Pronto habrá productos destacados aquí.</p>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family:'Playfair Display',serif;color:#5c2d0a;">Lo Más Nuevo</h2>
        <a href="<?= URL ?>catalogo" style="color:#b8722a;font-weight:700;text-decoration:none;">Ver todo →</a>
    </div>

    <?php if (!empty($productosNuevos)): ?>
    <div class="row mb-5">
        <?php foreach ($productosNuevos as $p): ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm hover-card">
                <?php if (!empty($p['URLImagen'])): ?>
                    <img src="<?= htmlspecialchars($p['URLImagen']) ?>"
                         class="card-img-top"
                         alt="<?= htmlspecialchars($p['NombredelProducto']) ?>"
                         style="height:200px;object-fit:cover;"
                         onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'card-img-top d-flex align-items-center justify-content-center bg-light\' style=\'height:200px;\'><i class=\'fas fa-couch fa-4x text-muted\'></i></div>'">
                <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:200px;">
                        <i class="fas fa-couch fa-4x text-muted"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body text-center">
                    <h5 class="card-title" style="color:#5c2d0a;font-family:'Playfair Display',serif;">
                        <?= htmlspecialchars($p['NombredelProducto']) ?>
                    </h5>
                    <?php if (!empty($p['NombreCategoria'])): ?>
                        <p class="text-muted small mb-1"><?= htmlspecialchars($p['NombreCategoria']) ?></p>
                    <?php endif; ?>
                    <p class="fw-bold fs-5 mb-2" style="color:#2e6b3a;">
                        $<?= number_format($p['PrecioVenta'], 2, ',', '.') ?>
                    </p>
                    <a href="<?= URL ?>productocliente/<?= $p['Id'] ?>" class="btn w-100"
                       style="background:#5c2d0a;color:#fff;border:none;">
                        Ver más
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-center text-muted mb-5">Pronto habrá productos nuevos aquí.</p>
    <?php endif; ?>

</div>

<a href="https://wa.me/5493543579974?text=Hola,%20estoy%20interesado%20en%20sus%20productos"
   class="whatsapp-btn" target="_blank" title="Contactanos por WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<style>
.text-shadow { text-shadow: 2px 2px 4px rgba(0,0,0,.7); }
.hover-card { transition: transform .3s, box-shadow .3s; cursor: pointer; }
.hover-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,.15) !important; }
.whatsapp-btn {
    position: fixed; bottom: 20px; right: 20px;
    width: 60px; height: 60px; background: #25D366; color: #fff;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 32px; box-shadow: 0 4px 12px rgba(0,0,0,.3);
    z-index: 1000; text-decoration: none; transition: all .3s;
}
.whatsapp-btn:hover { background: #128C7E; transform: scale(1.1); color: #fff; }
@media (max-width:768px) { .whatsapp-btn { width: 50px; height: 50px; font-size: 26px; } }
#heroCarousel .carousel-item a { display: block; position: relative; }
#heroCarousel .carousel-item a::after { content:''; position:absolute; inset:0; background:transparent; transition:background .25s; }
#heroCarousel .carousel-item a:hover::after { background: rgba(0,0,0,.08); }
</style>

<?php include INCLUDES . 'footer_cliente.php'; ?>