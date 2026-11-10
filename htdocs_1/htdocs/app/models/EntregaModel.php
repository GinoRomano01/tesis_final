<?php

class EntregaModel extends Model {

    public function getEstados(): array {
        return Db::query("
            SELECT Id, Nombre
            FROM EstadosdeEntrega
            WHERE FechaBorrado IS NULL
            ORDER BY Id
        ")->fetchAll();
    }

    public function getTiposEntrega(): array {
        return Db::query("
            SELECT Id, Nombre
            FROM TipodeEntrega
            WHERE FechaBorrado IS NULL
            ORDER BY Nombre
        ")->fetchAll();
    }

    public function getLocalidades(): array {
        return Db::query(
            "SELECT Id, Nombre FROM Localidad ORDER BY Nombre ASC"
        )->fetchAll();
    }

    public function getTiposDomicilio(): array {
        return Db::query(
            "SELECT Id, Nombre FROM TipoDomicilio ORDER BY Id ASC"
        )->fetchAll();
    }

    public function getDomicilioCliente(int $clienteId): array|false {
        return Db::query("
            SELECT c.Nombre, c.Apellido, c.Telefono,
                   d.Calle, d.Numero, d.Barrio, d.Country,
                   d.Departamento, d.Piso, d.NumeroPiso,
                   d.IdTipoDomicilio,
                   l.Nombre AS Localidad,
                   l.Id     AS IdLocalidad,
                   c.IdDomicilio
            FROM Clientes c
            LEFT JOIN Domicilio d ON d.Id = c.IdDomicilio
            LEFT JOIN Localidad l ON l.Id = c.IdLocalidad
            WHERE c.Id = ?
            LIMIT 1
        ", [$clienteId])->fetch();
    }

    public function crearDomicilio(array $data): int {
        Db::query("
            INSERT INTO Domicilio
                (Calle, Numero, Country, Departamento, Barrio, IdTipoDomicilio, Piso, NumeroPiso)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $data['calle']          ?? '',
            $data['numero']         ?? 0,
            $data['country']        ?? null,
            $data['departamento']   ?? null,
            $data['barrio']         ?? null,
            $data['tipo_domicilio'] ?? 1,
            $data['piso']           ?? null,
            $data['numero_piso']    ?? null,
        ]);
        return (int) Db::getInstance()->getConnection()->lastInsertId();
    }

    public function listarEntregas(string $buscar = '', int $idEstado = 0): array {
        $params = [];
        $where  = 'WHERE e.FechaBorrado IS NULL';

        if (!empty($buscar)) {
            $where   .= ' AND (
                c.Nombre               LIKE ?
                OR c.Apellido          LIKE ?
                OR pr.NombredelProducto LIKE ?
                OR e.CodigoEntrega     LIKE ?
            )';
            $like     = "%$buscar%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($idEstado > 0) {
            $where   .= ' AND e.IdEstadosdeEntrega = ?';
            $params[] = $idEstado;
        }

        return Db::query("
            SELECT
                e.Id,
                e.CodigoEntrega,
                e.FechadeEntrega,
                e.Direccion,
                e.CostoEnvio,
                e.IdEstadosdeEntrega,
                e.IdTipodeEntrega,
                e.IdVenta,
                ee.Nombre  AS EstadoNombre,
                te.Nombre  AS TipoEntregaNombre,
                c.Nombre   AS ClienteNombre,
                c.Apellido AS ClienteApellido,
                c.Telefono AS ClienteTelefono,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos,
                v.NumerodeVenta,
                fc.MontoTotal AS MontoFactura
            FROM Entrega e
            LEFT JOIN EstadosdeEntrega ee ON ee.Id  = e.IdEstadosdeEntrega
            LEFT JOIN TipodeEntrega    te ON te.Id  = e.IdTipodeEntrega
            LEFT JOIN Venta             v  ON v.Id   = e.IdVenta
            LEFT JOIN Carrito           ca ON ca.Id  = v.IdCarrito
            LEFT JOIN Clientes          c  ON c.Id   = ca.IdCliente
            LEFT JOIN ProductoCarrito  pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto         pr ON pr.Id   = pc.IdProducto
            LEFT JOIN FacturaCliente   fc ON fc.Id   = v.IdFacturaCliente
            $where
            GROUP BY e.Id
            ORDER BY e.FechadeEntrega DESC, e.Id DESC
        ", $params)->fetchAll();
    }

    public function getEntregaById(int $id): array|false {
        return Db::query("
            SELECT
                e.*,
                ee.Nombre  AS EstadoNombre,
                te.Nombre  AS TipoEntregaNombre,
                c.Nombre   AS ClienteNombre,
                c.Apellido AS ClienteApellido,
                c.Telefono AS ClienteTelefono,
                v.NumerodeVenta,
                fc.MontoTotal AS MontoFactura,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos
            FROM Entrega e
            LEFT JOIN EstadosdeEntrega ee ON ee.Id  = e.IdEstadosdeEntrega
            LEFT JOIN TipodeEntrega    te ON te.Id  = e.IdTipodeEntrega
            LEFT JOIN Venta             v  ON v.Id   = e.IdVenta
            LEFT JOIN Carrito           ca ON ca.Id  = v.IdCarrito
            LEFT JOIN Clientes          c  ON c.Id   = ca.IdCliente
            LEFT JOIN ProductoCarrito  pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto         pr ON pr.Id   = pc.IdProducto
            LEFT JOIN FacturaCliente   fc ON fc.Id   = v.IdFacturaCliente
            WHERE e.Id = ? AND e.FechaBorrado IS NULL
            GROUP BY e.Id
        ", [$id])->fetch();
    }

    public function getPorVenta(int $idVenta): array|false {
        return Db::query("
            SELECT e.*,
                   te.Nombre AS TipoEntrega,
                   ee.Nombre AS EstadoEntrega
            FROM Entrega e
            JOIN TipodeEntrega    te ON te.Id = e.IdTipodeEntrega
            JOIN EstadosdeEntrega ee ON ee.Id = e.IdEstadosdeEntrega
            WHERE e.IdVenta = ?
            LIMIT 1
        ", [$idVenta])->fetch();
    }

    public function guardarEntrega(int $idVenta, array $data): array {
        $codigo = 'SP-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        Db::query("
            INSERT INTO Entrega
                (FechadeEntrega, IdTipodeEntrega, IdEstadosdeEntrega,
                 IdUsuario, IdVenta, CodigoEntrega, Direccion, CostoEnvio)
            VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?)
        ", [
            $data['tipo'],
            $data['idUsuario'],
            $idVenta,
            $codigo,
            $data['direccion'] ?? '',
            $data['costo']     ?? 0,
        ]);

        return [
            'id'     => (int) Db::getInstance()->getConnection()->lastInsertId(),
            'codigo' => $codigo,
        ];
    }

    public function editarEntrega(int $id, array $d): bool {
        Db::query("
            UPDATE Entrega
            SET
                IdEstadosdeEntrega = ?,
                IdTipodeEntrega    = ?,
                FechadeEntrega     = ?,
                Direccion          = ?,
                CostoEnvio         = ?,
                CodigoEntrega      = ?
            WHERE Id = ?
        ", [
            (int)   $d['IdEstadosdeEntrega'],
            (int)   $d['IdTipodeEntrega'],
                    $d['FechadeEntrega'] ?: null,
            trim(   $d['Direccion']),
            (float) $d['CostoEnvio'],
            trim(   $d['CodigoEntrega']),
            $id,
        ]);
        return true;
    }

    public function cambiarEstado(int $id, int $idEstado): bool {
        Db::query(
            "UPDATE Entrega SET IdEstadosdeEntrega = ? WHERE Id = ?",
            [$idEstado, $id]
        );
        return true;
    }

    public function contarPorEstado(): array {
        $rows = Db::query("
            SELECT ee.Nombre, COUNT(e.Id) AS Total
            FROM Entrega e
            LEFT JOIN EstadosdeEntrega ee ON ee.Id = e.IdEstadosdeEntrega
            WHERE e.FechaBorrado IS NULL
            GROUP BY e.IdEstadosdeEntrega
        ")->fetchAll();

        $result = [];
        foreach ($rows as $r) {
            $result[$r['Nombre']] = (int) $r['Total'];
        }
        return $result;
    }

    public function seedEstados(): void {
        $estados = ['Pendiente', 'En curso', 'Finalizada'];
        foreach ($estados as $nombre) {
            $existe = Db::query(
                "SELECT Id FROM EstadosdeEntrega WHERE Nombre = ? LIMIT 1",
                [$nombre]
            )->fetch();
            if (!$existe) {
                Db::query(
                    "INSERT INTO EstadosdeEntrega (Nombre) VALUES (?)",
                    [$nombre]
                );
            }
        }
    }


    public function getPorId(int $id): ?array {
        return Db::query("SELECT * FROM Entrega WHERE Id = ?", [$id])->fetch() ?: null;
    }
}