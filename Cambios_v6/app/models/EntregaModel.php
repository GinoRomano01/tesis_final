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
        $where  = "WHERE e.FechaBorrado IS NULL
                    AND (p.Id IS NULL OR p.Estado IN ('Listo', 'Entregado'))";

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
            LEFT JOIN Comprobante   fc ON fc.Id   = v.IdComprobante
            LEFT JOIN Pedido           p  ON p.IdVenta = e.IdVenta AND p.FechaBorrado IS NULL
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
            LEFT JOIN Comprobante   fc ON fc.Id   = v.IdComprobante
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
                                                           
    public const ESTADOS_COLUMNAS = [1 => 'Pendiente', 2 => 'En preparación', 3 => 'Listo para retirar', 4 => 'En camino'];
    public const ESTADO_ANCHO_ID  = 5;             
    public const ESTADOS_DRAG     = [1, 2, 3];                                                                     

    private const ORDENES = [
        'fecha_desc'  => 'e.FechadeEntrega DESC',
        'fecha_asc'   => 'e.FechadeEntrega ASC',
        'monto_desc'  => 'fc.MontoTotal DESC',
        'monto_asc'   => 'fc.MontoTotal ASC',
        'tiempo_desc' => 'TiempoFabricacionTotal DESC',
        'tiempo_asc'  => 'TiempoFabricacionTotal ASC',
    ];

    private function _orderBy(string $orden): string {
        return self::ORDENES[$orden] ?? self::ORDENES['fecha_desc'];
    }

    private function _whereBase(int $idEstado, string $buscar, array &$params): string {
        $params[] = $idEstado;
        $where = "WHERE e.FechaBorrado IS NULL
                   AND (p.Id IS NULL OR p.Estado IN ('Listo', 'Entregado'))
                   AND e.IdEstadosdeEntrega = ?";
        if (!empty($buscar)) {
            $where .= ' AND (
                c.Nombre                LIKE ?
                OR c.Apellido           LIKE ?
                OR pr.NombredelProducto LIKE ?
                OR e.CodigoEntrega      LIKE ?
            )';
            $like = "%$buscar%";
            array_push($params, $like, $like, $like, $like);
        }
        return $where;
    }

    public function listarPorEstadoPaginado(int $idEstado, int $pagina, int $porPagina, string $buscar = '', string $orden = 'fecha_desc'): array {
        $params = [];
        $where  = $this->_whereBase($idEstado, $buscar, $params);
        $offset = max(0, ($pagina - 1) * $porPagina);
        $params[] = $porPagina;
        $params[] = $offset;

        return Db::query("
            SELECT
                e.Id, e.CodigoEntrega, e.FechadeEntrega, e.Direccion, e.CostoEnvio,
                e.IdEstadosdeEntrega, e.IdTipodeEntrega, e.IdVenta,
                ee.Nombre  AS EstadoNombre,
                te.Nombre  AS TipoEntregaNombre,
                c.Nombre   AS ClienteNombre,
                c.Apellido AS ClienteApellido,
                c.Telefono AS ClienteTelefono,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos,
                v.NumerodeVenta,
                fc.MontoTotal AS MontoFactura,
                (SELECT COALESCE(SUM(pr2.TiempoFabricacionHoras * pc2.Cantidad), 0)
                 FROM ProductoCarrito pc2
                 JOIN Producto pr2 ON pr2.Id = pc2.IdProducto
                 WHERE pc2.IdCarrito = ca.Id) AS TiempoFabricacionTotal
            FROM Entrega e
            LEFT JOIN EstadosdeEntrega ee ON ee.Id  = e.IdEstadosdeEntrega
            LEFT JOIN TipodeEntrega    te ON te.Id  = e.IdTipodeEntrega
            LEFT JOIN Venta             v  ON v.Id   = e.IdVenta
            LEFT JOIN Carrito           ca ON ca.Id  = v.IdCarrito
            LEFT JOIN Clientes          c  ON c.Id   = ca.IdCliente
            LEFT JOIN ProductoCarrito  pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto         pr ON pr.Id   = pc.IdProducto
            LEFT JOIN Comprobante   fc ON fc.Id   = v.IdComprobante
            LEFT JOIN Pedido           p  ON p.IdVenta = e.IdVenta AND p.FechaBorrado IS NULL
            $where
            GROUP BY e.Id
            ORDER BY {$this->_orderBy($orden)}
            LIMIT ? OFFSET ?
        ", $params)->fetchAll();
    }

    public function contarConFiltro(int $idEstado, string $buscar = ''): int {
        $params = [];
        $where  = $this->_whereBase($idEstado, $buscar, $params);
        return (int)Db::query("
            SELECT COUNT(DISTINCT e.Id)
            FROM Entrega e
            LEFT JOIN Venta             v  ON v.Id   = e.IdVenta
            LEFT JOIN Carrito           ca ON ca.Id  = v.IdCarrito
            LEFT JOIN Clientes          c  ON c.Id   = ca.IdCliente
            LEFT JOIN ProductoCarrito  pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto         pr ON pr.Id   = pc.IdProducto
            LEFT JOIN Pedido           p  ON p.IdVenta = e.IdVenta AND p.FechaBorrado IS NULL
            $where
        ", $params)->fetchColumn();
    }
                                                                         
    public function contarPorEstado(): array {
        $rows = Db::query("
            SELECT ee.Nombre, COUNT(e.Id) AS Total
            FROM Entrega e
            LEFT JOIN EstadosdeEntrega ee ON ee.Id = e.IdEstadosdeEntrega
            LEFT JOIN Pedido p ON p.IdVenta = e.IdVenta AND p.FechaBorrado IS NULL
            WHERE e.FechaBorrado IS NULL
              AND (p.Id IS NULL OR p.Estado IN ('Listo', 'Entregado'))
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
       
    public function actualizarUbicacion(int $idRepartidor, float $lat, float $lng): bool {
        try {
                                                                             
                                                                             
                                                                             
                                                                           
            $ahora = date('Y-m-d H:i:s');
            Db::query("
                INSERT INTO UbicacionRepartidor (IdRepartidor, Lat, Lng, FechaActualizacion)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE Lat = VALUES(Lat), Lng = VALUES(Lng), FechaActualizacion = VALUES(FechaActualizacion)
            ", [$idRepartidor, $lat, $lng, $ahora]);
            return true;
        } catch (Exception $e) {
            error_log('EntregaModel::actualizarUbicacion() - ' . $e->getMessage());
            return false;
        }
    }

    public function getPorId(int $id): ?array {
        return Db::query("SELECT * FROM Entrega WHERE Id = ?", [$id])->fetch() ?: null;
    }
                                                                                                                                 
    public function getListasParaRuta(): array {
        return Db::query("
            SELECT
                e.Id, e.CodigoEntrega, e.Direccion, e.CostoEnvio,
                c.Nombre AS ClienteNombre, c.Apellido AS ClienteApellido, c.Telefono,
                l.Nombre AS Localidad,
                fc.FechadeEmision,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos
            FROM Entrega e
            JOIN Venta           v  ON v.Id  = e.IdVenta
            JOIN Carrito         ca ON ca.Id = v.IdCarrito
            JOIN Clientes        c  ON c.Id  = ca.IdCliente
            LEFT JOIN Domicilio  d  ON d.Id  = c.IdDomicilio
            LEFT JOIN Localidad  l  ON l.Id  = c.IdLocalidad
            LEFT JOIN Comprobante  fc ON fc.Id = v.IdComprobante
            LEFT JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto        pr ON pr.Id = pc.IdProducto
            WHERE e.FechaBorrado IS NULL
              AND e.IdEstadosdeEntrega = 3
              AND e.IdRuta IS NULL
              AND e.IdTipodeEntrega = 2
              AND (
                    c.IdLocalidad = 97
                    OR (d.CodigoPostal REGEXP '^[0-9]+$' AND CAST(d.CodigoPostal AS UNSIGNED) BETWEEN 5000 AND 5009)
                  )
            GROUP BY e.Id
            ORDER BY l.Nombre ASC, fc.FechadeEmision ASC
        ")->fetchAll();
    }
                                                                                         
    public function getRepartidores(): array {
        return Db::query("
            SELECT u.Id, c.Nombre, c.Apellido
            FROM Usuario u
            JOIN Clientes c ON c.Id = u.IdCliente
            WHERE u.IdTipodeRol = 3 AND u.FechaBorrado IS NULL
            ORDER BY c.Nombre
        ")->fetchAll();
    }

    public function crearRuta(int $idRepartidor, array $idsEnOrden): int|false {
        if (empty($idsEnOrden)) return false;
        try {
            Db::query("INSERT INTO RutaEntrega (IdRepartidor, FechaInicio) VALUES (?, ?)",
                [$idRepartidor, date('Y-m-d H:i:s')]);
            $idRuta = (int)Db::getInstance()->getConnection()->lastInsertId();

            foreach ($idsEnOrden as $orden => $idEntrega) {
                Db::query("
                    UPDATE Entrega
                    SET IdRuta = ?, OrdenEnRuta = ?, IdEstadosdeEntrega = 4
                    WHERE Id = ? AND IdRuta IS NULL
                ", [$idRuta, $orden + 1, (int)$idEntrega]);
            }

            return $idRuta;
        } catch (Exception $e) {
            error_log('EntregaModel::crearRuta() - ' . $e->getMessage());
            return false;
        }
    }

                                                                      
    public function getRutaActiva(int $idRepartidor): ?array {
        $row = Db::query("
            SELECT * FROM RutaEntrega
            WHERE IdRepartidor = ? AND Estado = 'En curso'
            ORDER BY Id DESC LIMIT 1
        ", [$idRepartidor])->fetch();
        return $row ?: null;
    }
                                                                                  
    public function getEntregasDeRuta(int $idRuta): array {
        return Db::query("
            SELECT
                e.Id, e.CodigoEntrega, e.Direccion, e.OrdenEnRuta, e.IdEstadosdeEntrega,
                c.Nombre AS ClienteNombre, c.Apellido AS ClienteApellido, c.Telefono,
                l.Nombre AS Localidad,
                GROUP_CONCAT(DISTINCT pr.NombredelProducto ORDER BY pr.Id SEPARATOR ', ') AS Productos
            FROM Entrega e
            JOIN Venta           v  ON v.Id  = e.IdVenta
            JOIN Carrito         ca ON ca.Id = v.IdCarrito
            JOIN Clientes        c  ON c.Id  = ca.IdCliente
            LEFT JOIN Localidad  l  ON l.Id  = c.IdLocalidad
            LEFT JOIN ProductoCarrito pc ON pc.IdCarrito = ca.Id
            LEFT JOIN Producto        pr ON pr.Id = pc.IdProducto
            WHERE e.IdRuta = ? AND e.FechaBorrado IS NULL
            GROUP BY e.Id
            ORDER BY e.OrdenEnRuta ASC
        ", [$idRuta])->fetchAll();
    }

    public function marcarEntregado(int $idEntrega, int $idRuta): void {
        Db::query("UPDATE Entrega SET IdEstadosdeEntrega = 5 WHERE Id = ? AND IdRuta = ?", [$idEntrega, $idRuta]);

        $quedan = (int)Db::query("
            SELECT COUNT(*) FROM Entrega
            WHERE IdRuta = ? AND IdEstadosdeEntrega != 5 AND FechaBorrado IS NULL
        ", [$idRuta])->fetchColumn();

        if ($quedan === 0) {
            Db::query("UPDATE RutaEntrega SET Estado = 'Finalizada', FechaFin = ? WHERE Id = ?",
                [date('Y-m-d H:i:s'), $idRuta]);
        }
    }
}