<?php include INCLUDES . 'header_cliente.php'; ?>

<div class="container my-5">

    
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold" style="font-family:'Playfair Display',serif; color:#5c2d0a;">
            Quiénes Somos
        </h1>
        <p class="text-muted fs-5">Conocé la historia y los valores detrás de San Plácido</p>
        <hr style="width:80px; border-top:3px solid #c9a84c; margin:1rem auto 0;">
    </div>

    
    <div class="row align-items-center g-5 mb-5">
        <div class="col-md-6">
            <h2 style="font-family:'Playfair Display',serif; color:#5c2d0a;">Nuestra Historia</h2>
            <p class="text-muted lh-lg">
                
                San Plácido es una empresa familiar dedicada a la construcción de muebles 
                fundada en 1976 por Don Giuseppe Ruffino contruyendo hogares y cumpliendo sueños 
                en los hogares condobeses por mas de 50 años
            </p>
        </div>
        <div class="col-md-6 text-center">
            
            <div class="rounded-4 overflow-hidden shadow" style="height:280px;">
                <img src="<?= IMG ?>info/cartel.jpeg" 
                    alt="San Plácido - Nuestra Historia" 
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
    </div>

    
    <div class="row text-center g-4 mb-5">
        <?php
        $valores = [
            ['icon' => 'fa-seedling',    'titulo' => 'Sustentabilidad', 'texto' => 'Trabajamos con maderas de origen responsable.'],
            ['icon' => 'fa-medal',       'titulo' => 'Calidad',          'texto' => 'Cada mueble pasa por un estricto control de calidad.'],
            ['icon' => 'fa-handshake',   'titulo' => 'Compromiso',       'texto' => 'Cumplimos plazos y superamos expectativas.'],
            ['icon' => 'fa-paint-brush', 'titulo' => 'Diseño',           'texto' => 'Adaptamos cada pieza al estilo del cliente.'],
        ];
        foreach ($valores as $v): ?>
        <div class="col-6 col-md-3">
            <div class="p-4 rounded-4 h-100" style="background:#faf7f2; border:1px solid #e8dfd0;">
                <i class="fas <?= $v['icon'] ?> fa-2x mb-3" style="color:#c9a84c;"></i>
                <h5 class="fw-bold" style="color:#5c2d0a;"><?= $v['titulo'] ?></h5>
                <p class="text-muted small mb-0"><?= $v['texto'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    
    <div class="text-center mb-4">
        <h2 style="font-family:'Playfair Display',serif; color:#5c2d0a;">Nuestro Equipo</h2>
        <p class="text-muted">Las personas que hacen posible cada proyecto</p>
    </div>
    <div class="row justify-content-center g-4 mb-5">
        <?php
        
        $equipo = [
            ['nombre' => 'Juan Pérez',  'rol' => 'Fundador & Maestro carpintero'],
            ['nombre' => 'Ana López',   'rol' => 'Diseño & Atención al cliente'],
            ['nombre' => 'Carlos Ruiz', 'rol' => 'Producción & Acabados'],
        ];
        foreach ($equipo as $m):
            $inicial = strtoupper(mb_substr($m['nombre'], 0, 1));
        ?>
        <div class="col-6 col-md-3 text-center">
            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3 shadow"
                 style="width:90px; height:90px; background:linear-gradient(135deg,#5c2d0a,#b8722a);
                        font-size:2rem; color:#fff; font-family:'Playfair Display',serif;">
                <?= $inicial ?>
            </div>
            <h6 class="fw-bold mb-0" style="color:#1a1008;"><?= $m['nombre'] ?></h6>
            <small class="text-muted"><?= $m['rol'] ?></small>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<?php include INCLUDES . 'footer_cliente.php'; ?>