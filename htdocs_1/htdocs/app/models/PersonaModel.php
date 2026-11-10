<?php
require_once __DIR__ . '/../classes/Db.php';

class PersonaModel extends Model{
    private $conn;

    public function __construct() {
        $db = new Db();
        $this->conn = $db->getConnection(); 
    }

    public function obtenerUsuarios() {
        $sql = "SELECT u.*, tu.Nombre AS TipoUsuario, tr.Nombre AS TipoRol 
                FROM Usuario u
                LEFT JOIN TipodeUsuario tu ON u.IdTipodeUsuario = tu.Id
                LEFT JOIN TipodeRol tr ON u.IdTipodeRol = tr.Id
                WHERE u.FechaBorrado IS NULL";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerUsuarioPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM Usuario WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function agregarUsuario($nombreUsuario, $contraseña, $correo, $idTipoUsuario, $idTipoRol, $idCliente = null) {
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO Usuario (NombredeUsuario, Contraseña, CorreoElectronico, IdTipodeUsuario, IdTipodeRol, IdCliente) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $nombreUsuario, $hash, $correo, $idTipoUsuario, $idTipoRol, $idCliente);
        return $stmt->execute();
    }

    public function editarUsuario($id, $nombreUsuario, $correo, $idTipoUsuario, $idTipoRol) {
        $stmt = $this->conn->prepare("UPDATE Usuario SET NombredeUsuario=?, CorreoElectronico=?, IdTipodeUsuario=?, IdTipodeRol=? WHERE Id=?");
        $stmt->bind_param("ssiii", $nombreUsuario, $correo, $idTipoUsuario, $idTipoRol, $id);
        return $stmt->execute();
    }

    public function eliminarUsuario($id) {
        $stmt = $this->conn->prepare("UPDATE Usuario SET FechaBorrado = NOW() WHERE Id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerTiposDeUsuario() {
        $sql = "SELECT Id, Nombre FROM TipodeUsuario";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTiposDeRol() {
        $sql = "SELECT Id, Nombre FROM TipodeRol";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
