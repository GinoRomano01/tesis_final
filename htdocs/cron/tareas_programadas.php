<?php

require_once __DIR__ . '/../app/config/core_config.php';
require_once __DIR__ . '/../app/classes/Autoloader.php';
Autoloader::init();
require_once __DIR__ . '/../app/classes/Model.php';

header('Content-Type: application/json; charset=utf-8');

$claveEsperada = defined('CRON_SECRET') ? CRON_SECRET : null;
if (!$claveEsperada || $claveEsperada === 'CAMBIAR-ESTA-CLAVE-2026' || ($_GET['clave'] ?? '') !== $claveEsperada) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Clave inválida o no configurada']);
    exit;
}

$resultado = [
    'carritos_abandonados' => 0,
    'productos_nuevos'     => 0,
    'mails_producto_nuevo' => 0,
];

try {
    $carritos = Db::query("
        SELECT ca.Id, ca.IdCliente,
               GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos
        FROM Carrito ca
        JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
        LEFT JOIN Producto pr ON pr.Id = pc.IdProducto
        WHERE ca.Estado = 0
          AND ca.FechaCreacion IS NOT NULL
          AND ca.FechaNotificadoAbandono IS NULL
          AND TIMESTAMPDIFF(DAY, ca.FechaCreacion, NOW()) >= 30
        GROUP BY ca.Id
        LIMIT 20
    ")->fetchAll();

    foreach ($carritos as $c) {

        if (!empty($c['IdCliente']) && !empty($c['Productos'])) {
            $contenidoHtml = "
                <p>Vimos que dejaste estos productos en tu carrito hace un tiempo:</p>
                <p><strong>" . htmlspecialchars($c['Productos']) . "</strong></p>
                <p>Todavía están disponibles — ¿los terminamos de comprar?</p>
            ";

            NotificacionModel::crearConEmail(
                [
                    'IdCliente'  => (int)$c['IdCliente'],
                    'Tipo'       => 'carrito_abandonado',
                    'Titulo'     => 'Dejaste productos en tu carrito',
                    'Contenido'  => $c['Productos'],
                    'UrlDestino' => 'carrito',
                    'Icono'      => 'fa-cart-shopping',
                ],
                'Todavía tenés productos esperándote — San Plácido',
                '¿Te olvidaste de algo?',
                $contenidoHtml,
                'Ver mi carrito',
                'carrito'
            );
            $resultado['carritos_abandonados']++;
        }

        Db::query("UPDATE Carrito SET FechaNotificadoAbandono = ? WHERE Id = ?",
            [date('Y-m-d H:i:s'), $c['Id']]);
    }
} catch (\Throwable $e) {
    error_log('cron/tareas_programadas - carritos abandonados - ' . $e->getMessage());
}

try {
    $productosNuevos = Db::query("
        SELECT Id, NombredelProducto, URLImagen, PrecioVenta
        FROM Producto
        WHERE FechaBorrado IS NULL
          AND EstadoAprobacion = 'Aprobado'
          AND FechaNotificadoNuevo IS NULL
        ORDER BY Id ASC
        LIMIT 3
    ")->fetchAll();

    if (!empty($productosNuevos)) {
        $clientes = Db::query("
            SELECT DISTINCT u.IdCliente
            FROM Usuario u
            WHERE u.FechaBorrado IS NULL
              AND u.CorreoElectronico IS NOT NULL AND u.CorreoElectronico != ''
              AND u.IdCliente IS NOT NULL
        ")->fetchAll();

        foreach ($productosNuevos as $p) {
            $imgHtml = !empty($p['URLImagen'])
                ? "<img src='" . htmlspecialchars($p['URLImagen']) . "' style='max-width:100%;border-radius:8px;margin-bottom:12px;'>"
                : '';
            $precio = number_format((float)($p['PrecioVenta'] ?? 0), 2, ',', '.');
            $contenidoHtml = "
                {$imgHtml}
                <p>Sumamos <strong>" . htmlspecialchars($p['NombredelProducto']) . "</strong> al catálogo.</p>
                <p>Precio: \${$precio}</p>
            ";

            foreach ($clientes as $cl) {
                NotificacionModel::crearConEmail(
                    [
                        'IdCliente'  => (int)$cl['IdCliente'],
                        'Tipo'       => 'producto_nuevo',
                        'Titulo'     => 'Nuevo producto: ' . $p['NombredelProducto'],
                        'Contenido'  => 'Precio: $' . $precio,
                        'UrlDestino' => 'productocliente/index/' . $p['Id'],
                        'Icono'      => 'fa-couch',
                    ],
                    'Nuevo en San Plácido: ' . $p['NombredelProducto'],
                    '¡Tenemos algo nuevo para vos!',
                    $contenidoHtml,
                    'Ver producto',
                    'productocliente/index/' . $p['Id']
                );
                $resultado['mails_producto_nuevo']++;
            }

            Db::query("UPDATE Producto SET FechaNotificadoNuevo = ? WHERE Id = ?",
                [date('Y-m-d H:i:s'), $p['Id']]);
            $resultado['productos_nuevos']++;
        }
    }
} catch (\Throwable $e) {
    error_log('cron/tareas_programadas - productos nuevos - ' . $e->getMessage());
}

echo json_encode(['ok' => true] + $resultado);
