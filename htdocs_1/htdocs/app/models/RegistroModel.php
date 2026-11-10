<?php

class RegistroModel extends Model {

    protected $table = 'Clientes';
    protected $fillable = [];

    public function obtenerTiposDni() {
        try {
            $result = Db::query("SELECT Id, Nombre FROM TipodeDni");
            return $result->fetchAll();
        } catch (Exception $e) {
            error_log('Error obtenerTiposDni: ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerLocalidades() {
        try {
            $result = Db::query("SELECT Id, Nombre FROM Localidad ORDER BY Nombre");
            return $result->fetchAll();
        } catch (Exception $e) {
            error_log('Error obtenerLocalidades: ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerTiposDomicilio() {
        try {
            $result = Db::query("SELECT Id, Nombre FROM TipoDomicilio");
            return $result->fetchAll();
        } catch (Exception $e) {
            error_log('Error obtenerTiposDomicilio: ' . $e->getMessage());
            return [];
        }
    }

    public function dniExiste($dni) {
        try {
            $result = Db::query(
                "SELECT COUNT(*) as total FROM Clientes WHERE DNI = ? AND FechaBorrado IS NULL",
                [$dni]
            );
            return $result->fetch()['total'] > 0;
        } catch (Exception $e) {
            error_log('Error dniExiste: ' . $e->getMessage());
            return false;
        }
    }

    public function correoExiste($correo) {
        try {
            $result = Db::query(
                "SELECT COUNT(*) as total FROM Usuario WHERE CorreoElectronico = ? AND FechaBorrado IS NULL",
                [$correo]
            );
            return $result->fetch()['total'] > 0;
        } catch (Exception $e) {
            error_log('Error correoExiste: ' . $e->getMessage());
            return false;
        }
    }

    public function guardarDomicilio($datos) {
        $sql = "INSERT INTO Domicilio
                (Calle, Numero, Country, Departamento, Barrio, Piso, numeroPiso, CodigoPostal, IdTipoDomicilio)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        Db::query($sql, [
            $datos['Calle']        ?? null,
            $datos['Numero']       ?? null,
            $datos['Country']      ?? null,
            $datos['Departamento'] ?? null,
            $datos['Barrio']       ?? null,
            $datos['Piso']         ?? null,
            $datos['numeroPiso']   ?? null,
            $datos['CodigoPostal'] ?? null,
            $datos['IdTipoDomicilio']
        ]);

        $instance = Db::getInstance();
        return $instance->getConnection()->lastInsertId();
    }

    public function guardarCliente($datos) {
        $sql = "INSERT INTO Clientes
                (DNI, Nombre, Apellido, Telefono, IdLocalidad, IdTipodeDni, IdDomicilio, IdTipodomicilio)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)";

        Db::query($sql, [
            $datos['DNI'],
            $datos['Nombre'],
            $datos['Apellido'],
            $datos['Telefono']      ?? null,
            $datos['IdLocalidad'],
            $datos['IdTipodeDni'],
            $datos['IdDomicilio'],
            $datos['IdTipoDomicilio']
        ]);

        $instance = Db::getInstance();
        return $instance->getConnection()->lastInsertId();
    }

    public function guardarUsuario($datos) {
        $sql = "INSERT INTO Usuario
                (NombredeUsuario, Contraseña, CorreoElectronico,
                 IdTipodeUsuario, IdTipodeRol, IdCliente, Confirmado)
                VALUES
                (?, ?, ?, 2, 2, ?, 0)";

        Db::query($sql, [
            $datos['NombredeUsuario'],
            $datos['Contraseña'],
            $datos['CorreoElectronico'],
            $datos['IdCliente']
        ]);

        $instance = Db::getInstance();
        return $instance->getConnection()->lastInsertId();
    }

    public function registrarClienteYUsuario($cliente, $usuario) {
        $instance = Db::getInstance();
        $pdo      = $instance->getConnection();

        try {
            $pdo->beginTransaction();

            $idDomicilio = $this->guardarDomicilio($cliente);

            $cliente['IdDomicilio'] = $idDomicilio;
            $idCliente = $this->guardarCliente($cliente);

            $usuario['IdCliente'] = $idCliente;
            $this->guardarUsuario($usuario);

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('❌ Error registro completo: ' . $e->getMessage());
            return false;
        }
    }
}