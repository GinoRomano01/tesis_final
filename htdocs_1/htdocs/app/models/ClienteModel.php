<?php

class ClienteModel extends Model {

    protected $table    = 'Clientes';
    protected $fillable = ['DNI', 'Nombre', 'Apellido', 'Telefono', 'IdLocalidad', 'IdTipodeDni', 'IdDomicilio', 'IdTipodomicilio'];

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT
                        c.Id,
                        c.DNI,
                        c.Nombre,
                        c.Apellido,
                        c.Telefono,
                        c.IdLocalidad,
                        c.IdTipodeDni,
                        c.IdDomicilio,
                        c.IdTipodomicilio,
                        d.Calle,
                        d.Numero,
                        d.Country,
                        d.Departamento,
                        d.Barrio,
                        d.Piso,
                        d.NumeroPiso    AS numeroPiso,
                        d.CodigoPostal,
                        l.Nombre        AS NombreLocalidad,
                        td.Nombre       AS TipoDomicilio,
                        tdn.Nombre      AS TipoDNI
                    FROM Clientes c
                    LEFT JOIN Domicilio    d   ON c.IdDomicilio     = d.Id
                    LEFT JOIN Localidad    l   ON c.IdLocalidad     = l.Id
                    LEFT JOIN TipoDomicilio td  ON c.IdTipodomicilio = td.Id
                    LEFT JOIN TipodeDni    tdn  ON c.IdTipodeDni    = tdn.Id
                    WHERE c.Id = ?
                      AND c.FechaBorrado IS NULL";

            $result = Db::query($sql, [$id]);
            return $result->fetch();

        } catch (Exception $e) {
            error_log('ClienteModel::obtenerPorId() - ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerPorDni($dni) {
        try {
            $result = Db::query(
                "SELECT * FROM Clientes WHERE DNI = ? AND FechaBorrado IS NULL",
                [$dni]
            );
            return $result->fetch();
        } catch (Exception $e) {
            error_log('ClienteModel::obtenerPorDni() - ' . $e->getMessage());
            return false;
        }
    }

    public function actualizarDatos($id, array $datos) {
        try {
            if (empty($datos)) return false;

            $sets   = implode(' = ?, ', array_keys($datos)) . ' = ?';
            $values = array_values($datos);
            $values[] = $id;

            Db::query("UPDATE Clientes SET {$sets} WHERE Id = ?", $values);
            return true;

        } catch (Exception $e) {
            error_log('ClienteModel::actualizarDatos() - ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT
                        c.Id,
                        c.DNI,
                        c.Nombre,
                        c.Apellido,
                        c.Telefono,
                        l.Nombre AS NombreLocalidad
                    FROM Clientes c
                    LEFT JOIN Localidad l ON c.IdLocalidad = l.Id
                    WHERE c.FechaBorrado IS NULL
                    ORDER BY c.Apellido, c.Nombre";

            $result = Db::query($sql);
            return $result->fetchAll();

        } catch (Exception $e) {
            error_log('ClienteModel::obtenerTodos() - ' . $e->getMessage());
            return [];
        }
    }

    public function crearDomicilio(array $datos) {
        try {
            $campos = array_filter([
                'Calle'           => $datos['Calle']           ?? null,
                'Numero'          => $datos['Numero']          ?? null,
                'Piso'            => $datos['Piso']            ?? null,
                'NumeroPiso'      => $datos['NumeroPiso']      ?? null,
                'Barrio'          => $datos['Barrio']          ?? null,
                'Country'         => $datos['Country']         ?? null,
                'Departamento'    => $datos['Departamento']    ?? null,
                'CodigoPostal'    => $datos['CodigoPostal']    ?? null,
                'IdTipoDomicilio' => $datos['IdTipoDomicilio'] ?? null,
            ], fn($v) => $v !== null);

            if (empty($campos)) return null;

            $fields       = implode(', ', array_keys($campos));
            $placeholders = implode(', ', array_fill(0, count($campos), '?'));

            $db   = Db::getInstance();
            $conn = $db->getConnection();

            $stmt = $conn->prepare("INSERT INTO Domicilio ({$fields}) VALUES ({$placeholders})");
            $stmt->execute(array_values($campos));

            return $conn->lastInsertId();

        } catch (Exception $e) {
            error_log('ClienteModel::crearDomicilio() - ' . $e->getMessage());
            return null;
        }
    }

    public function actualizarDomicilio($idDomicilio, array $datos) {
        try {
            $campos = array_filter([
                'Calle'           => $datos['Calle']           ?? null,
                'Numero'          => $datos['Numero']          ?? null,
                'Piso'            => $datos['Piso']            ?? null,
                'NumeroPiso'      => $datos['NumeroPiso']      ?? null,
                'Barrio'          => $datos['Barrio']          ?? null,
                'Country'         => $datos['Country']         ?? null,
                'Departamento'    => $datos['Departamento']    ?? null,
                'CodigoPostal'    => $datos['CodigoPostal']    ?? null,
                'IdTipoDomicilio' => $datos['IdTipoDomicilio'] ?? null,
            ], fn($v) => $v !== null);

            $camposNullables = ['Piso', 'NumeroPiso', 'Barrio', 'Country', 'Departamento', 'CodigoPostal'];
            foreach ($camposNullables as $campo) {
                if (array_key_exists($campo, $datos)) {
                    $campos[$campo] = $datos[$campo];
                }
            }

            if (empty($campos)) return false;

            $sets   = implode(' = ?, ', array_keys($campos)) . ' = ?';
            $values = array_values($campos);
            $values[] = $idDomicilio;

            Db::query("UPDATE Domicilio SET {$sets} WHERE Id = ?", $values);
            return true;

        } catch (Exception $e) {
            error_log('ClienteModel::actualizarDomicilio() - ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerLocalidades() {
        try {
            $result = Db::query("SELECT Id, Nombre FROM Localidad WHERE FechaBorrado IS NULL ORDER BY Nombre");
            return $result->fetchAll();
        } catch (Exception $e) {
            error_log('ClienteModel::obtenerLocalidades() - ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerTiposDomicilio() {
        try {
            $result = Db::query("SELECT Id, Nombre FROM TipoDomicilio WHERE FechaBorrado IS NULL ORDER BY Id");
            return $result->fetchAll();
        } catch (Exception $e) {
            error_log('ClienteModel::obtenerTiposDomicilio() - ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerTiposDni() {
        try {
            $result = Db::query("SELECT Id, Nombre FROM TipodeDni WHERE FechaBorrado IS NULL ORDER BY Id");
            return $result->fetchAll();
        } catch (Exception $e) {
            error_log('ClienteModel::obtenerTiposDni() - ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerPedidosCliente($idCliente) {
        try {
            $sql = "SELECT
                        v.Id                            AS IdVenta,
                        v.NumerodeVenta,
                        fc.FechaDeEmision               AS Fecha,
                        fc.MontoTotal,
                        ep.Nombre                       AS EstadoPago,
                        tp.Nombre                       AS TipoPago,
                        pe.Estado                       AS EstadoPedido,
                        pe.Responsable,
                        e.CodigoEntrega,
                        ee.Nombre                       AS EstadoEntrega,
                        GROUP_CONCAT(
                            p.NombredelProducto
                            ORDER BY p.NombredelProducto
                            SEPARATOR ', '
                        )                               AS Productos
                    FROM Venta v
                    JOIN FacturaCliente  fc  ON fc.Id  = v.IdFacturaCliente
                    JOIN EstadoDePago    ep  ON ep.Id  = fc.IdEstadoDePago
                    JOIN TipoDePago      tp  ON tp.Id  = fc.IdTipoDePago
                    JOIN Carrito         ca  ON ca.Id  = v.IdCarrito
                    JOIN ProductoCarrito pc2 ON pc2.IdCarrito = ca.Id
                    JOIN Producto        p   ON p.Id   = pc2.IdProducto
                    LEFT JOIN Pedido     pe  ON pe.IdVenta = v.Id AND pe.FechaBorrado IS NULL
                    LEFT JOIN Entrega    e   ON e.IdVenta  = v.Id AND e.FechaBorrado  IS NULL
                    LEFT JOIN EstadosDeEntrega ee ON ee.Id = e.IdEstadosDeEntrega
                    WHERE ca.IdCliente = ?
                      AND v.FechaBorrado IS NULL
                    GROUP BY v.Id
                    ORDER BY fc.FechaDeEmision DESC";

            $result = Db::query($sql, [$idCliente]);
            return $result->fetchAll();

        } catch (Exception $e) {
            error_log('ClienteModel::obtenerPedidosCliente() - ' . $e->getMessage());
            return [];
        }
    }
}