<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - San Plácido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/tokens.css">
    <link rel="stylesheet" href="<?= URL ?>templates/assets/css/registro.css">
</head>
<body>
    <div class="error-page">
        <div class="error-card">
            <div class="error-number">404</div>
            <div class="error-titulo">Página no encontrada</div>
            <div class="error-desc">
                Lo sentimos, la página que estás buscando no existe o fue movida.
            </div>
            <div class="error-actions">
                <a href="<?= URL ?>" class="btn-error-primary">
                    <i class="fas fa-home"></i> Volver al inicio
                </a>
                <a href="javascript:history.back()" class="btn-error-secondary">
                    <i class="fas fa-arrow-left"></i> Volver atrás
                </a>
            </div>
        </div>
    </div>
</body>
</html>