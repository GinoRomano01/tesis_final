<?php

class AvisoPedidoClienteService {

    public static function alCambiarEstado(int $idPedido, string $anterior, string $nuevo): void {
        if ($anterior === $nuevo) return;

        $aviso = self::aviso($nuevo);
        if (!$aviso) return;

        $pedido = self::datosPedido($idPedido);
        if (!$pedido || empty($pedido['IdCliente'])) return;

        $numero    = $pedido['NumerodeVenta'] ?: $idPedido;
        $productos = $pedido['Productos'] ?: '—';
        $titulo    = sprintf($aviso['titulo'], $numero);

        $data = [
            'IdCliente'  => (int)$pedido['IdCliente'],
            'Tipo'       => 'pedido_actualizado',
            'Titulo'     => $titulo,
            'Contenido'  => mb_strimwidth($aviso['texto'] . ' Productos: ' . $productos, 0, 500, '...'),
            'UrlDestino' => 'pedidocliente',
            'Icono'      => $aviso['icono'],
        ];

        if (!$aviso['mail']) {
            NotificacionModel::crear($data);
            return;
        }

        $html = '<p>' . htmlspecialchars($aviso['texto']) . '</p>'
              . '<p><strong>Pedido:</strong> #' . htmlspecialchars((string)$numero) . '<br>'
              . '<strong>Productos:</strong> ' . htmlspecialchars($productos) . '</p>';

        NotificacionModel::crearConEmail(
            $data,
            $titulo . ' — San Plácido',
            $titulo,
            $html,
            'Ver mi pedido',
            'pedidocliente'
        );
    }
    
    private static function aviso(string $estado): ?array {
        return match ($estado) {
            'En producción' => [
                'titulo' => 'Empezamos a fabricar tu pedido #%s',
                'texto'  => 'Tu pedido ya está en el taller y lo estamos fabricando.',
                'icono'  => 'fa-hammer',
                'mail'   => false,
            ],
            'Listo' => [
                'titulo' => '¡Tu pedido #%s está terminado!',
                'texto'  => 'Terminamos de fabricar tu pedido. Te avisamos cuando salga para entregarse.',
                'icono'  => 'fa-circle-check',
                'mail'   => true,
            ],
            'Cancelado' => [
                'titulo' => 'Tu pedido #%s fue cancelado',
                'texto'  => 'Tu pedido fue cancelado. Si tenés alguna consulta, comunicate con nosotros.',
                'icono'  => 'fa-circle-xmark',
                'mail'   => true,
            ],
            default => null,
        };
    }

    private static function datosPedido(int $idPedido): array|false {
        return Db::query("
            SELECT ca.IdCliente, v.NumerodeVenta,
                   GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos
            FROM Pedido p
            JOIN Venta   v  ON v.Id  = p.IdVenta
            JOIN Carrito ca ON ca.Id = v.IdCarrito
            LEFT JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto        pr ON pr.Id = pc.IdProducto
            WHERE p.Id = ?
            GROUP BY p.Id, ca.IdCliente, v.NumerodeVenta
        ", [$idPedido])->fetch();
    }
}
