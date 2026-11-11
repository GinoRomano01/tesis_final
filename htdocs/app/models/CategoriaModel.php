<?php

class CategoriaModel extends Model {
    
    protected $table = 'Categoria';
    protected $fillable = ['Nombre'];

    public function obtenerTodas() {
        try {
            $sql = "SELECT Id, Nombre 
                    FROM Categoria 
                    WHERE FechaBorrado IS NULL 
                    ORDER BY Nombre ASC";
            
            $result = Db::query($sql);
            return $result->fetchAll();
            
        } catch (Exception $e) {
            error_log('Error en CategoriaModel::obtenerTodas() - ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT Id, Nombre
                    FROM Categoria 
                    WHERE Id = ? AND FechaBorrado IS NULL";
            
            $result = Db::query($sql, [$id]);
            return $result->fetch();
            
        } catch (Exception $e) {
            error_log('Error en CategoriaModel::obtenerPorId() - ' . $e->getMessage());
            return false;
        }
    }

    public function listarConConteo(string $buscar = ''): array {
        $params = [];
        $where  = 'WHERE c.FechaBorrado IS NULL';

        if (!empty($buscar)) {
            $where   .= ' AND c.Nombre LIKE ?';
            $params[] = '%' . $buscar . '%';
        }

        return Db::query("
            SELECT
                c.Id, c.Nombre,
                (SELECT COUNT(*) FROM Producto p
                 WHERE p.IdCategoria = c.Id AND p.FechaBorrado IS NULL) AS CantidadProductos
            FROM Categoria c
            $where
            ORDER BY c.Nombre ASC
        ", $params)->fetchAll();
    }

    public function nombreExiste(string $nombre, int $idExcluir = 0): bool {
        $row = Db::query("
            SELECT Id FROM Categoria
            WHERE LOWER(TRIM(Nombre)) = LOWER(TRIM(?))
              AND FechaBorrado IS NULL
              AND Id != ?
            LIMIT 1
        ", [$nombre, $idExcluir])->fetch();
        return (bool)$row;
    }

    public function crear(string $nombre): int|false {
        try {
            Db::query("INSERT INTO Categoria (Nombre) VALUES (?)", [trim($nombre)]);
            return (int)Db::getInstance()->getConnection()->lastInsertId();
        } catch (Exception $e) {
            error_log('CategoriaModel::crear() - ' . $e->getMessage());
            return false;
        }
    }

    public function editar(int $id, string $nombre): bool {
        try {
            Db::query("UPDATE Categoria SET Nombre = ? WHERE Id = ?", [trim($nombre), $id]);
            return true;
        } catch (Exception $e) {
            error_log('CategoriaModel::editar() - ' . $e->getMessage());
            return false;
        }
    }

    public function contarProductosActivos(int $id): int {
        return (int)Db::query("
            SELECT COUNT(*) FROM Producto WHERE IdCategoria = ? AND FechaBorrado IS NULL
        ", [$id])->fetchColumn();
    }

    public function darDeBaja(int $id): bool {
        try {
            Db::query("UPDATE Categoria SET FechaBorrado = NOW() WHERE Id = ?", [$id]);
            return true;
        } catch (Exception $e) {
            error_log('CategoriaModel::darDeBaja() - ' . $e->getMessage());
            return false;
        }
    }
}