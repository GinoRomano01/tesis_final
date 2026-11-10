<?php

class PedidoModel extends Model {

    public const ESTADOS = ['Pendiente', 'En producción', 'Listo', 'Entregado', 'Cancelado'];

    public function sincronizar(): void {
        Db::query("CALL sp_sincronizar_pedidos()");
    }

    public function getVentasElegibles(): array {
        return Db::query("
            SELECT
                v.Id,
                v.NumerodeVenta,
                fc.FechadeEmision,
                fc.MontoTotal,
                TIMESTAMPDIFF(HOUR, fc.FechadeEmision, NOW()) AS HorasTranscurridas,
                c.Nombre   AS ClienteNombre,
                c.Apellido AS ClienteApellido,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos
            FROM Venta v
            JOIN FacturaCliente   fc ON fc.Id  = v.IdFacturaCliente
            JOIN Carrito          ca ON ca.Id  = v.IdCarrito
            JOIN Clientes         c  ON c.Id   = ca.IdCliente
            LEFT JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto     pr ON pr.Id = pc.IdProducto
            JOIN EstadodePago     ep ON ep.Id  = fc.IdEstadodePago AND ep.Nombre = 'Aprobado'
            LEFT JOIN Pedido       p  ON p.IdVenta = v.Id AND p.FechaBorrado IS NULL
            WHERE v.FechaBorrado IS NULL
              AND p.Id IS NULL
            GROUP BY v.Id
            ORDER BY fc.FechadeEmision DESC
        ")->fetchAll();
    }

    public function listarPedidos(string $buscar = '', string $estado = ''): array {
        $params = [];
        $where  = 'WHERE p.FechaBorrado IS NULL';

        if (!empty($buscar)) {
            $where   .= ' AND (
                c.Nombre                LIKE ?
                OR c.Apellido           LIKE ?
                OR pr.NombredelProducto LIKE ?
                OR p.Responsable        LIKE ?
                OR v.NumerodeVenta      LIKE ?
            )';
            $like     = "%$buscar%";
            $params[] = $like; $params[] = $like; $params[] = $like;
            $params[] = $like; $params[] = $like;
        }

        if (!empty($estado)) {
            $where   .= ' AND p.Estado = ?';
            $params[] = $estado;
        }

        return Db::query("
            SELECT
                p.Id,
                p.Estado,
                p.Responsable,
                p.IdVenta,
                v.NumerodeVenta,
                fc.FechadeEmision,
                fc.MontoTotal,
                TIMESTAMPDIFF(HOUR, fc.FechadeEmision, NOW()) AS HorasDesdeVenta,
                c.Nombre   AS ClienteNombre,
                c.Apellido AS ClienteApellido,
                c.Telefono AS ClienteTelefono,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos,
                e.CodigoEntrega,
                ee.Nombre  AS EstadoEntrega
            FROM Pedido p
            JOIN Venta           v  ON v.Id   = p.IdVenta
            JOIN FacturaCliente  fc ON fc.Id  = v.IdFacturaCliente
            JOIN Carrito         ca ON ca.Id  = v.IdCarrito
            JOIN Clientes        c  ON c.Id   = ca.IdCliente
            LEFT JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto   pr ON pr.Id  = pc.IdProducto
            LEFT JOIN Entrega    e  ON e.IdVenta = v.Id
            LEFT JOIN EstadosdeEntrega ee ON ee.Id = e.IdEstadosdeEntrega
            $where
            GROUP BY p.Id
            ORDER BY p.Id DESC
        ", $params)->fetchAll();
    }

    public function contarPorEstado(): array {
        $rows = Db::query("
            SELECT Estado, COUNT(Id) AS Total
            FROM Pedido
            WHERE FechaBorrado IS NULL
            GROUP BY Estado
        ")->fetchAll();
        $result = [];
        foreach ($rows as $r) {
            $result[$r['Estado']] = (int)$r['Total'];
        }
        return $result;
    }

    public function ventaTienePedido(int $idVenta): bool {
        return (int)Db::query(
            "SELECT COUNT(*) FROM Pedido WHERE IdVenta = ? AND FechaBorrado IS NULL",
            [$idVenta]
        )->fetchColumn() > 0;
    }

    public function crearPedido(int $idVenta, string $responsable, string $estado): int|false {
        if ($this->ventaTienePedido($idVenta)) {
            return false;
        }
        Db::query("
            INSERT INTO Pedido (Estado, Responsable, IdVenta)
            VALUES (?, ?, ?)
        ", [$estado, trim($responsable), $idVenta]);
        return (int)Db::getInstance()->getConnection()->lastInsertId();
    }

    public function editarPedido(int $id, string $estado, string $responsable): bool {
        Db::query("
            UPDATE Pedido SET Estado = ?, Responsable = ? WHERE Id = ?
        ", [$estado, trim($responsable), $id]);
        return true;
    }

    public function darDeBaja(int $id): bool {
        Db::query("UPDATE Pedido SET FechaBorrado = NOW() WHERE Id = ?", [$id]);
        return true;
    }
}