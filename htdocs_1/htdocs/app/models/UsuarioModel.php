<?php

class UsuarioModel extends Model {
    
    protected $table = 'Usuario';
    protected $fillable = ['NombredeUsuario', 'Contraseña', 'CorreoElectronico'];
    
    public function buscarPorCorreo($correo) {
        try {
            $sql = "SELECT 
                        u.Id,
                        u.NombredeUsuario,
                        u.Contraseña,
                        u.CorreoElectronico,
                        u.IdCliente,
                        u.Confirmado,
                        u.IdTipodeUsuario,
                        u.IdTipodeRol,
                        c.Nombre,
                        c.Apellido,
                        c.DNI,
                        c.Telefono
                    FROM Usuario u
                    INNER JOIN Clientes c ON u.IdCliente = c.Id
                    WHERE u.CorreoElectronico = ? 
                    AND u.FechaBorrado IS NULL
                    AND c.FechaBorrado IS NULL";
            
            $result = Db::query($sql, [$correo]);
            return $result->fetch();
            
        } catch (Exception $e) {
            error_log('Error en UsuarioModel::buscarPorCorreo() - ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function buscarPorId($id) {
        try {
            $sql = "SELECT 
                        u.*,
                        c.Nombre,
                        c.Apellido,
                        c.DNI,
                        c.Telefono
                    FROM Usuario u
                    INNER JOIN Clientes c ON u.IdCliente = c.Id
                    WHERE u.Id = ? 
                    AND u.FechaBorrado IS NULL
                    AND c.FechaBorrado IS NULL";
            
            $result = Db::query($sql, [$id]);
            return $result->fetch();
            
        } catch (Exception $e) {
            error_log('Error en UsuarioModel::buscarPorId() - ' . $e->getMessage());
            return false;
        }
    }
    
    public function correoExiste($correo) {
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM Usuario 
                    WHERE CorreoElectronico = ? 
                    AND FechaBorrado IS NULL";
            
            $result = Db::query($sql, [$correo]);
            return $result->fetch()['total'] > 0;
            
        } catch (Exception $e) {
            error_log('Error en UsuarioModel::correoExiste() - ' . $e->getMessage());
            return false;
        }
    }

    public function actualizarPassword(int $idUsuario, string $hashNuevo): bool {
        try {
            Db::query(
                "UPDATE Usuario SET Contraseña = ? WHERE Id = ? AND FechaBorrado IS NULL",
                [$hashNuevo, $idUsuario]
            );
            return true;
        } catch (Exception $e) {
            error_log('UsuarioModel::actualizarPassword ERROR: ' . $e->getMessage());
            return false;
        }
    }
}