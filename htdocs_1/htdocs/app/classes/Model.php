<?php

class Model {
    
    protected $table = '';
    
    protected $fillable = [];
    
    /**
     * @return array 
     */
    public static function all() {
        $model = new static();
        $result = Db::query("SELECT * FROM {$model->table} ORDER BY created_at DESC");
        return $result->fetchAll();
    }
    
    /**
     * @param int $id 
     * @return array|false
     */
    public static function find($id) {
        $model = new static();
        $result = Db::query("SELECT * FROM {$model->table} WHERE id = ?", [$id]);
        return $result->fetch();
    }
    
    /**
     * @param array $data 
     * @return bool 
     */
    public static function create($data) {
        $model = new static();
        
        $filteredData = array_intersect_key($data, array_flip($model->fillable));
        
        $fields = implode(', ', array_keys($filteredData));
        $placeholders = implode(', ', array_fill(0, count($filteredData), '?'));
        
        $sql = "INSERT INTO {$model->table} ({$fields}, created_at) VALUES ({$placeholders}, NOW())";
        
        Db::query($sql, array_values($filteredData));
        return true;
    }
    
    /**
     * @param int $id 
     * @param array $data 
     * @return bool 
     */
    public static function update($id, $data) {
        $model = new static();
        
        $filteredData = array_intersect_key($data, array_flip($model->fillable));
        
        $setClause = implode(' = ?, ', array_keys($filteredData)) . ' = ?';
        $sql = "UPDATE {$model->table} SET {$setClause} WHERE id = ?";
        
        $values = array_values($filteredData);
        $values[] = $id;
        
        Db::query($sql, $values);
        return true;
    }
    
    /**
     * @param int $id 
     * @return bool 
     */
    public static function delete($id) {
        $model = new static();
        Db::query("DELETE FROM {$model->table} WHERE id = ?", [$id]);
        return true;
    }
}
?>
