<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método no permitido'], 405);
}

$body = readJsonBody();
$pregunta = trim($body['pregunta'] ?? '');
if ($pregunta === '') jsonResponse(['error' => 'Falta pregunta'], 400);

function generarRespuesta(string $pregunta): string {
    $p = mb_strtolower($pregunta, 'UTF-8');

    $reglas = [
        ['palabras' => ['presupuesto', 'cotiza', 'precio', 'cuanto sale', 'cuánto sale'],
         'respuesta' => 'Para un presupuesto a medida contanos qué instalación o servicio necesitás y te contactamos por WhatsApp al 3543-551211.'],
        ['palabras' => ['certificaci', 'ersep', 'aea'],
         'respuesta' => 'Hacemos certificaciones de instalaciones nuevas y existentes conforme a normas AEA y ERSeP. ¿Querés que te derivemos a un asesor?'],
        ['palabras' => ['puesta a tierra', 'tierra'],
         'respuesta' => 'Sí, hacemos medición y verificación de puesta a tierra. Coordinamos la visita por WhatsApp.'],
        ['palabras' => ['plano', 'unifilar', 'carpeta tecnica', 'carpeta técnica'],
         'respuesta' => 'Armamos planos unifilares y carpetas técnicas completas para instaladores matriculados. Contanos la cantidad de bocas y el tipo de obra.'],
        ['palabras' => ['horario', 'atienden', 'atención'],
         'respuesta' => 'Respondemos consultas por WhatsApp durante el día. Escribinos al 3543-551211 y te contestamos a la brevedad.'],
        ['palabras' => ['whatsapp', 'contacto', 'telefono', 'teléfono'],
         'respuesta' => 'Podés escribirnos directo por WhatsApp al 3543-551211.'],
    ];

    foreach ($reglas as $regla) {
        foreach ($regla['palabras'] as $palabra) {
            if (mb_strpos($p, $palabra) !== false) {
                return $regla['respuesta'];
            }
        }
    }

    return 'Gracias por tu consulta. Para una respuesta personalizada, escribinos por WhatsApp al 3543-551211.';
}

$respuesta = generarRespuesta($pregunta);

$pdo = getConnection();
$stmt = $pdo->prepare('INSERT INTO chatbot_preguntas (pregunta, respuesta, ip) VALUES (?, ?, ?)');
$stmt->execute([$pregunta, $respuesta, getClientIp()]);

jsonResponse(['respuesta' => $respuesta]);
