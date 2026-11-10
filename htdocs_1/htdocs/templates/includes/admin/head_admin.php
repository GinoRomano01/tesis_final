<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? 'Estadisticas' ?></title>

<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= URL ?>templates/assets/css/tokens.css">
<link rel="stylesheet" href="<?= URL ?>templates/assets/css/admin-base.css">
<link rel="stylesheet" href="<?= URL ?>templates/assets/css/admin-tablas.css">
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<link rel="stylesheet" href="<?= URL ?>templates/assets/css/notificaciones.css">
<script>const URL_BASE = "<?= URL ?>";</script>
<script src="<?= URL ?>templates/assets/js/notificaciones.js"></script>
</head>
<body class="admin-body"></body>