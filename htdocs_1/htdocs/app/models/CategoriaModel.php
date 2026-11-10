<?php

class CategoriaModel extends Model {
    
    protected $table = 'Categoria';
    protected $fillable = ['Nombre'];
    
    /**
     * @return array
     */
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
    
    /**
     * @param int $id
     * @return array|false
     */
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
}