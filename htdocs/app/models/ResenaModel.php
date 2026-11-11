<?php
class ResenaModel extends Model {

    protected $table = 'Resena';

    public function crear(array $data): int {
        $sql = "INSERT INTO Resena
                (IdCliente, IdProducto, IdPedido, Puntuacion, Titulo,
                 ContenidoOriginal, ContenidoPublicado, Estado, FueEmbellecida,
                 FechaCreacion, FechaModeracion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

        $estado = $data['Estado'] ?? 'pendiente';
        $fechaMod = in_array($estado, ['aprobada','rechazada'], true) ? date('Y-m-d H:i:s') : null;

        Db::query($sql, [
            $data['IdCliente'],
            $data['IdProducto'],
            $data['IdPedido'] ?? null,
            $data['Puntuacion'],
            $data['Titulo'] ?? null,
            $data['ContenidoOriginal'],
            $data['ContenidoPublicado'] ?? $data['ContenidoOriginal'],
            $estado,
            !empty($data['FueEmbellecida']) ? 1 : 0,
            $fechaMod,
        ]);

        return (int) Db::getInstance()->getConnection()->lastInsertId();
    }

    public function listarPorProducto(int $idProducto, string $estado = 'aprobada'): array {
        $sql = "SELECT r.*,
                       CONCAT(c.Nombre, ' ', LEFT(COALESCE(c.Apellido,''),1)) AS AutorNombre
                FROM Resena r
                INNER JOIN Clientes c ON c.Id = r.IdCliente
                WHERE r.IdProducto = ?
                  AND r.Estado = ?
                  AND r.FechaBorrado IS NULL
                ORDER BY r.FechaCreacion DESC";
        return Db::query($sql, [$idProducto, $estado])->fetchAll();
    }

    public function resumenPorProducto(int $idProducto): array {
        $sql = "SELECT COUNT(*) AS total,
                       COALESCE(AVG(Puntuacion), 0) AS promedio
                FROM Resena
                WHERE IdProducto = ? AND Estado = 'aprobada' AND FechaBorrado IS NULL";
        $row = Db::query($sql, [$idProducto])->fetch();
        return [
            'total'    => (int)($row['total'] ?? 0),
            'promedio' => round((float)($row['promedio'] ?? 0), 2),
        ];
    }

    public function listarPorEstado(string $estado): array {
        $sql = "SELECT r.*, p.NombredelProducto,
                    CONCAT(c.Nombre,' ',COALESCE(c.Apellido,'')) AS AutorNombre,
                    a.Sentimiento, a.ScoreToxicidad, a.Categorias, a.Flags, a.ResumenCorto
                FROM Resena r
                INNER JOIN Clientes c ON c.Id = r.IdCliente
                INNER JOIN Producto p ON p.Id = r.IdProducto
                LEFT  JOIN ResenaAnalisisIA a ON a.IdResena = r.Id
                WHERE r.Estado = ? AND r.FechaBorrado IS NULL
                ORDER BY r.FechaCreacion DESC";
        return Db::query($sql, [$estado])->fetchAll();
    }

    public function cambiarEstado(int $id, string $estado): void {
        Db::query(
            "UPDATE Resena SET Estado = ?, FechaModeracion = NOW() WHERE Id = ?",
            [$estado, $id]
        );
    }
}