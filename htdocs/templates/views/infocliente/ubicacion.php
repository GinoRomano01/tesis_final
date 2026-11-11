<?php include INCLUDES . 'header_cliente.php'; ?>

<div class="container my-5">

    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold" style="font-family:'Playfair Display',serif; color:#5c2d0a;">
            Dónde Estamos
        </h1>
        <p class="text-muted fs-5">Visitanos o escribinos, estamos para ayudarte</p>
        <hr style="width:80px; border-top:3px solid #c9a84c; margin:1rem auto 0;">
    </div>

    <div class="row g-5 align-items-start">

        <div class="col-md-5">
            <div class="p-4 rounded-4 h-100" style="background:#faf7f2; border:1px solid #e8dfd0;">
                <h4 class="fw-bold mb-4" style="color:#5c2d0a; font-family:'Playfair Display',serif;">
                    Información de Contacto
                </h4>
                <ul class="list-unstyled" style="line-height:2.6;">
                    <li>
                        <i class="fas fa-map-marker-alt me-2" style="color:#c9a84c; width:20px;"></i>
                        <strong>Dirección:</strong>
                        Calle Zarate 1920,Ana María Zumarán, Córdoba
                    </li>
                    <li>
                        <i class="fas fa-phone me-2" style="color:#c9a84c; width:20px;"></i>
                        <strong>Teléfono:</strong>
                        +54 9 3543 XX-XXXX
                    </li>
                    <li>
                        <i class="fas fa-envelope me-2" style="color:#c9a84c; width:20px;"></i>
                        <strong>Email:</strong>
                        contacto@sanplacido.com.ar
                    </li>
                    <li>
                        <i class="fab fa-whatsapp me-2" style="color:#25D366; width:20px;"></i>
                        <strong>WhatsApp:</strong>
                        <a href="https://wa.me/5493543579974" target="_blank"
                           class="text-decoration-none" style="color:#5c2d0a;">
                            Escribinos
                        </a>
                    </li>
                </ul>

                <hr style="border-color:#e8dfd0;">

                <h5 class="fw-bold mb-3" style="color:#5c2d0a;">Horarios de Atención</h5>
                <table class="table table-sm table-borderless mb-0" style="font-size:.92rem;">
                    <tbody>
                        <tr>
                            <td class="text-muted">Lun – Vie</td>
                            <td class="fw-bold">8:00 – 18:00</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Sábados</td>
                            <td class="fw-bold">9:00 – 13:00</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Domingos</td>
                            <td class="fw-bold text-danger">Cerrado</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-7">
            <div class="rounded-4 overflow-hidden shadow" style="height:420px;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8989.341095972572!2d-64.20418126231569!3d-31.37765396614865!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x943298fe5f6ba435%3A0x44a114319c0a7e64!2sZarate%201920%2C%20X5008%20C%C3%B3rdoba!5e0!3m2!1ses-419!2sar!4v1773428183094!5m2!1ses-419!2sar" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    width="100%" height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <p class="text-muted small mt-2 text-center">
                <i class="fas fa-info-circle me-1"></i>
                Reemplazá el embed con tu ubicación exacta desde Google Maps.
            </p>
        </div>

    </div>
</div>

<a href="https://wa.me/5493543579974?text=Hola,%20quiero%20consultar%20sobre%20su%20ubicaci%C3%B3n"
   class="whatsapp-btn" target="_blank" title="Contactanos por WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<style>
.whatsapp-btn {
    position: fixed; bottom: 20px; right: 20px;
    width: 60px; height: 60px;
    background-color: #25D366; color: white;
    border-radius: 50%; display: flex;
    align-items: center; justify-content: center;
    font-size: 32px; box-shadow: 0 4px 12px rgba(0,0,0,.3);
    z-index: 1000; text-decoration: none; transition: all .3s;
}
.whatsapp-btn:hover { background-color: #128C7E; transform: scale(1.1); color: white; }
</style>

<?php include INCLUDES . 'footer_cliente.php'; ?>