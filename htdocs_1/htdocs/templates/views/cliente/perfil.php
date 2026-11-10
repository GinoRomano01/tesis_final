<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root{
  --lino:#f7f0e6;--lino2:#ede4d4;--papel:#fdfaf6;
  --caoba:#5c2d0a;--caoba2:#7a3e14;--amb:#b8722a;
  --tinta:#2c1a0e;--tinta2:#4a3020;--g1:#8a7560;
  --borde:#d4c4aa;--borde2:#e8dcc8;--verde:#2e6b3a;
  --sombra:rgba(92,45,10,.08);
}
.perfil-page{background:var(--lino);min-height:100vh;padding:2.5rem 0 4rem;font-family:'Source Sans 3',Georgia,sans-serif;}
.perfil-inner{max-width:960px;margin:0 auto;padding:0 1.5rem;display:grid;grid-template-columns:220px 1fr;gap:1.5rem;align-items:start;}
.perfil-sidebar{background:var(--papel);border:1.5px solid var(--borde);border-radius:12px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);position:sticky;top:1rem;}
.perfil-sidebar-top{background:linear-gradient(to bottom right,var(--caoba),var(--caoba2));padding:1.4rem 1rem;text-align:center;}
.perfil-avatar{width:60px;height:60px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .7rem;font-size:1.6rem;color:rgba(255,255,255,.85);}
.perfil-nombre{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:#fff;margin-bottom:.15rem;}
.perfil-dni{font-size:.78rem;color:rgba(255,255,255,.65);}
.perfil-nav{padding:.5rem 0;}
.perfil-nav a{display:flex;align-items:center;gap:.6rem;padding:.65rem 1.1rem;font-size:.88rem;color:var(--tinta2);text-decoration:none;transition:background .12s,color .12s;border-left:3px solid transparent;}
.perfil-nav a:hover{background:var(--lino);color:var(--caoba);}
.perfil-nav a.active{background:var(--lino2);color:var(--caoba);font-weight:700;border-left-color:var(--caoba);}
.perfil-nav a.danger{color:#c0392b;}
.perfil-nav a.danger:hover{background:#fdf0f0;}
.perfil-nav-sep{height:1px;background:var(--borde2);margin:.4rem .8rem;}
.perfil-panel{background:var(--papel);border:1.5px solid var(--borde);border-radius:12px;overflow:hidden;box-shadow:0 2px 12px var(--sombra);}
.perfil-panel-hd{background:linear-gradient(to right,var(--caoba),var(--caoba2));padding:1rem 1.4rem;display:flex;align-items:center;justify-content:space-between;}
.perfil-panel-hd h2{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:#fff;margin:0;}
.perfil-panel-bd{padding:1.5rem;}
.dato-grupo{display:grid;grid-template-columns:1fr 1fr;gap:1rem 1.5rem;margin-bottom:1.3rem;}
.dato-label{font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--g1);margin-bottom:.2rem;}
.dato-valor{font-size:.97rem;color:var(--tinta);font-weight:600;text-transform:capitalize;}
.dato-valor.vacio{color:var(--g1);font-weight:400;font-style:italic;text-transform:none;}
.dato-sep{height:1px;background:var(--borde2);margin:1.2rem 0;}
.btn-editar{display:inline-flex;align-items:center;gap:.4rem;padding:.52rem 1.1rem;background:var(--caoba);color:#fff;border:none;border-radius:8px;font-size:.86rem;font-weight:700;text-decoration:none;cursor:pointer;transition:background .15s;font-family:'Source Sans 3',sans-serif;}
.btn-editar:hover{background:var(--caoba2);color:#fff;}
@media(max-width:680px){.perfil-inner{grid-template-columns:1fr;}.perfil-sidebar{position:static;}.dato-grupo{grid-template-columns:1fr;}}
</style>

<div class="perfil-page">
    <div class="perfil-inner">

        <aside class="perfil-sidebar">
            <div class="perfil-sidebar-top">
                <div class="perfil-avatar"><i class="fas fa-user"></i></div>
                <div class="perfil-nombre">
                    <?= htmlspecialchars(mb_convert_case($cliente['Nombre'], MB_CASE_TITLE, 'UTF-8') . ' ' . mb_convert_case($cliente['Apellido'], MB_CASE_TITLE, 'UTF-8')) ?>
                </div>
                <div class="perfil-dni">DNI <?= htmlspecialchars($cliente['DNI']) ?></div>
            </div>
            <nav class="perfil-nav">
                <a href="<?= URL ?>cliente/perfil" class="active">
                    <i class="fas fa-user" style="width:16px;text-align:center;"></i> Mi Perfil
                </a>
                <a href="<?= URL ?>pedidocliente">
                    <i class="fas fa-shopping-bag" style="width:16px;text-align:center;"></i> Mis Pedidos
                </a>
                <a href="<?= URL ?>cliente/editar">
                    <i class="fas fa-edit" style="width:16px;text-align:center;"></i> Editar Perfil
                </a>
                <div class="perfil-nav-sep"></div>
                <a href="<?= URL ?>login/logout" class="danger">
                    <i class="fas fa-sign-out-alt" style="width:16px;text-align:center;"></i> Cerrar Sesión
                </a>
            </nav>
        </aside>

        <div class="perfil-panel">
            <div class="perfil-panel-hd">
                <h2>Mi Perfil</h2>
                <a href="<?= URL ?>cliente/editar" class="btn-editar">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
            <div class="perfil-panel-bd">

                <div class="dato-grupo">
                    <div class="dato-item">
                        <div class="dato-label">Nombre</div>
                        <div class="dato-valor">
                            <?= htmlspecialchars(mb_convert_case($cliente['Nombre'], MB_CASE_TITLE, 'UTF-8')) ?>
                        </div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">Apellido</div>
                        <div class="dato-valor">
                            <?= htmlspecialchars(mb_convert_case($cliente['Apellido'], MB_CASE_TITLE, 'UTF-8')) ?>
                        </div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">DNI</div>
                        <div class="dato-valor"><?= htmlspecialchars($cliente['DNI']) ?></div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">Teléfono</div>
                        <div class="dato-valor <?= empty($cliente['Telefono']) ? 'vacio' : '' ?>">
                            <?= !empty($cliente['Telefono']) ? htmlspecialchars($cliente['Telefono']) : 'No registrado' ?>
                        </div>
                    </div>
                </div>

                <div class="dato-sep"></div>

                <div class="dato-grupo">
                    <div class="dato-item">
                        <div class="dato-label">Localidad</div>
                        <div class="dato-valor <?= empty($cliente['NombreLocalidad']) ? 'vacio' : '' ?>">
                            <?= !empty($cliente['NombreLocalidad']) ? htmlspecialchars($cliente['NombreLocalidad']) : 'No registrada' ?>
                        </div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">Tipo de domicilio</div>
                        <div class="dato-valor <?= empty($cliente['TipoDomicilio']) ? 'vacio' : '' ?>">
                            <?= !empty($cliente['TipoDomicilio']) ? htmlspecialchars($cliente['TipoDomicilio']) : '—' ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($cliente['Calle'])): ?>
                <div class="dato-sep"></div>
                <div class="dato-grupo">
                    <div class="dato-item">
                        <div class="dato-label">Calle</div>
                        <div class="dato-valor"><?= htmlspecialchars($cliente['Calle']) ?></div>
                    </div>
                    <div class="dato-item">
                        <div class="dato-label">Número</div>
                        <div class="dato-valor"><?= htmlspecialchars($cliente['Numero'] ?? '—') ?></div>
                    </div>
                    <?php if (!empty($cliente['Piso'])): ?>
                    <div class="dato-item">
                        <div class="dato-label">Piso</div>
                        <div class="dato-valor"><?= htmlspecialchars($cliente['Piso']) ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($cliente['Departamento'])): ?>
                    <div class="dato-item">
                        <div class="dato-label">Departamento</div>
                        <div class="dato-valor"><?= htmlspecialchars($cliente['Departamento']) ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($cliente['Barrio'])): ?>
                    <div class="dato-item">
                        <div class="dato-label">Barrio</div>
                        <div class="dato-valor"><?= htmlspecialchars($cliente['Barrio']) ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($cliente['Country'])): ?>
                    <div class="dato-item">
                        <div class="dato-label">Country / Barrio privado</div>
                        <div class="dato-valor"><?= htmlspecialchars($cliente['Country']) ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($cliente['CodigoPostal'])): ?>
                    <div class="dato-item">
                        <div class="dato-label">Código postal</div>
                        <div class="dato-valor"><?= htmlspecialchars($cliente['CodigoPostal']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?php include INCLUDES . 'footer_cliente.php'; ?>