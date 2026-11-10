<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

requireAuth();
$pdo = getConnection();

$dias = (int)($_GET['dias'] ?? 30);

$visitasPorDia = $pdo->prepare(
    "SELECT DATE(fecha_hora) AS dia, COUNT(*) AS total
     FROM visitas WHERE fecha_hora >= DATE_SUB(NOW(), INTERVAL ? DAY)
     GROUP BY DATE(fecha_hora) ORDER BY dia"
);
$visitasPorDia->execute([$dias]);

$clicksPorServicio = $pdo->prepare(
    "SELECT c.referencia_id, s.nombre, COUNT(*) AS total
     FROM clicks c LEFT JOIN servicios s ON s.id = c.referencia_id
     WHERE c.tipo_elemento = 'servicio' AND c.fecha_hora >= DATE_SUB(NOW(), INTERVAL ? DAY)
     GROUP BY c.referencia_id ORDER BY total DESC LIMIT 10"
);
$clicksPorServicio->execute([$dias]);

$totales = $pdo->query(
    "SELECT
        (SELECT COUNT(*) FROM visitas) AS total_visitas,
        (SELECT COUNT(*) FROM clicks) AS total_clicks,
        (SELECT COUNT(*) FROM chatbot_preguntas) AS total_preguntas,
        (SELECT COUNT(*) FROM clientes) AS total_clientes"
)->fetch();

$ultimasPreguntas = $pdo->query(
    "SELECT pregunta, respuesta, fecha_hora FROM chatbot_preguntas ORDER BY fecha_hora DESC LIMIT 20"
)->fetchAll();

jsonResponse([
    'totales'            => $totales,
    'visitas_por_dia'    => $visitasPorDia->fetchAll(),
    'clicks_por_servicio'=> $clicksPorServicio->fetchAll(),
    'ultimas_preguntas'  => $ultimasPreguntas,
]);
