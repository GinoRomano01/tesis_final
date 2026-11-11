<?php

class AtributoModel extends Model {

    private array $config = [
        'categoria' => [
            'tabla'              => 'Categoria',
            'columna_producto'   => 'IdCategoria',
            'tiene_descripcion'  => false,
            'junction'           => null,
            'junction_col'       => null,
            'auto_vincular_todos'=> false,
        ],
        'tipoproducto' => [
            'tabla'              => 'TipodeProducto',
            'columna_producto'   => 'IdTipodeProducto',
            'tiene_descripcion'  => false,
            'junction'           => null,
            'junction_col'       => null,
            'auto_vincular_todos'=> false,
        ],
        'diseno' => [
            'tabla'              => 'TipodeDiseño',
            'columna_producto'   => 'IdTipodeDiseño',
            'tiene_descripcion'  => false,
            'junction'           => 'TipodeProductoDiseño',
            'junction_col'       => 'IdTipodeDiseño',
            'auto_vincular_todos'=> true, 
        ],
        'acabado' => [
            'tabla'              => 'TipodeAcabado',
            'columna_producto'   => 'IdTipodeAcabado',
            'tiene_descripcion'  => false,
            'junction'           => 'TipodeProductoAcabado',
            'junction_col'       => 'IdTipodeAcabado',
            'auto_vincular_todos'=> true,
        ],
        'herraje' => [
            'tabla'              => 'TipodeHerraje',
            'columna_producto'   => 'IdTipodeHerraje',
            'tiene_descripcion'  => true,
            'junction'           => 'TipodeProductoHerraje',
            'junction_col'       => 'IdTipodeHerraje',
            'auto_vincular_todos'=> false, 
        ],
        'almacenamiento' => [
            'tabla'              => 'TipodeAlmacenamiento',
            'columna_producto'   => 'IdTipodeAlmacenamiento',
            'tiene_descripcion'  => true,
            'junction'           => 'TipodeProductoAlmacenamiento',
            'junction_col'       => 'IdTipodeAlmacenamiento',
            'auto_vincular_todos'=> false,
        ],
    ];

    public function getTabs(): array {
        return [
            'categoria'      => 'Categoría',
            'tipoproducto'   => 'Tipo de producto',
            'diseno'         => 'Diseño',
            'acabado'        => 'Acabado',
            'herraje'        => 'Herraje',
            'almacenamiento' => 'Almacenamiento',
        ];
    }

    public function tieneDescripcion(string $tab): bool {
        return $this->config[$tab]['tiene_descripcion'] ?? false;
    }

    public function tieneChecklist(string $tab): bool {
        $cfg = $this->config[$tab] ?? null;
        return $cfg && !empty($cfg['junction']) && !$cfg['auto_vincular_todos'];
    }

    public function listar(string $tab, string $buscar = ''): array {
        $cfg    = $this->config[$tab];
        $params = [];
        $where  = 'WHERE a.FechaBorrado IS NULL';

        if (!empty($buscar)) {
            $where   .= ' AND a.Nombre LIKE ?';
            $params[] = '%' . $buscar . '%';
        }

        $selectDesc = $cfg['tiene_descripcion'] ? 'a.Descripcion,' : '';

        $rows = Db::query("
            SELECT a.Id, a.Nombre, $selectDesc
                (SELECT COUNT(*) FROM Producto p
                 WHERE p.{$cfg['columna_producto']} = a.Id AND p.FechaBorrado IS NULL) AS CantidadProductos
            FROM {$cfg['tabla']} a
            $where
            ORDER BY a.Nombre ASC
        ", $params)->fetchAll();

        if ($this->tieneChecklist($tab)) {
            foreach ($rows as &$r) {
                $r['TiposVinculados'] = array_map('intval', Db::query("
                    SELECT IdTipodeProducto FROM {$cfg['junction']} WHERE {$cfg['junction_col']} = ?
                ", [$r['Id']])->fetchAll(\PDO::FETCH_COLUMN));
            }
            unset($r);
        }

        return $rows;
    }

    public function obtenerTodosTiposProducto(): array {
        return Db::query("
            SELECT Id, Nombre FROM TipodeProducto WHERE FechaBorrado IS NULL ORDER BY Nombre
        ")->fetchAll();
    }

    public function nombreExiste(string $tab, string $nombre, int $idExcluir = 0): bool {
        $cfg = $this->config[$tab];
        $row = Db::query("
            SELECT Id FROM {$cfg['tabla']}
            WHERE LOWER(TRIM(Nombre)) = LOWER(TRIM(?)) AND FechaBorrado IS NULL AND Id != ?
            LIMIT 1
        ", [$nombre, $idExcluir])->fetch();
        return (bool)$row;
    }

    public function crear(string $tab, string $nombre, ?string $descripcion, array $tiposVinculados = []): int|false {
        $cfg = $this->config[$tab];
        try {
            if ($cfg['tiene_descripcion']) {
                Db::query("INSERT INTO {$cfg['tabla']} (Nombre, Descripcion) VALUES (?, ?)",
                    [trim($nombre), trim((string)$descripcion) ?: null]);
            } else {
                Db::query("INSERT INTO {$cfg['tabla']} (Nombre) VALUES (?)", [trim($nombre)]);
            }
            $nuevoId = (int)Db::getInstance()->getConnection()->lastInsertId();

            if ($tab === 'tipoproducto') {

                Db::query("
                    INSERT IGNORE INTO TipodeProductoDiseño (IdTipodeProducto, IdTipodeDiseño)
                    SELECT ?, Id FROM TipodeDiseño WHERE FechaBorrado IS NULL
                ", [$nuevoId]);
                Db::query("
                    INSERT IGNORE INTO TipodeProductoAcabado (IdTipodeProducto, IdTipodeAcabado)
                    SELECT ?, Id FROM TipodeAcabado WHERE FechaBorrado IS NULL
                ", [$nuevoId]);
            } elseif (!empty($cfg['junction'])) {
                if ($cfg['auto_vincular_todos']) {
                    Db::query("
                        INSERT IGNORE INTO {$cfg['junction']} (IdTipodeProducto, {$cfg['junction_col']})
                        SELECT Id, ? FROM TipodeProducto WHERE FechaBorrado IS NULL
                    ", [$nuevoId]);
                } else {
                    $this->_reemplazarVinculos($cfg, $nuevoId, $tiposVinculados);
                }
            }

            return $nuevoId;
        } catch (Exception $e) {
            error_log("AtributoModel::crear($tab) - " . $e->getMessage());
            return false;
        }
    }

    public function editar(string $tab, int $id, string $nombre, ?string $descripcion, array $tiposVinculados = []): bool {
        $cfg = $this->config[$tab];
        try {
            if ($cfg['tiene_descripcion']) {
                Db::query("UPDATE {$cfg['tabla']} SET Nombre = ?, Descripcion = ? WHERE Id = ?",
                    [trim($nombre), trim((string)$descripcion) ?: null, $id]);
            } else {
                Db::query("UPDATE {$cfg['tabla']} SET Nombre = ? WHERE Id = ?", [trim($nombre), $id]);
            }

            if ($this->tieneChecklist($tab)) {
                $this->_reemplazarVinculos($cfg, $id, $tiposVinculados);
            }

            return true;
        } catch (Exception $e) {
            error_log("AtributoModel::editar($tab) - " . $e->getMessage());
            return false;
        }
    }

    private function _reemplazarVinculos(array $cfg, int $id, array $tiposVinculados): void {
        Db::query("DELETE FROM {$cfg['junction']} WHERE {$cfg['junction_col']} = ?", [$id]);
        foreach ($tiposVinculados as $idTipo) {
            $idTipo = (int)$idTipo;
            if ($idTipo <= 0) continue;
            Db::query("
                INSERT IGNORE INTO {$cfg['junction']} (IdTipodeProducto, {$cfg['junction_col']}) VALUES (?, ?)
            ", [$idTipo, $id]);
        }
    }

    public function contarProductosActivos(string $tab, int $id): int {
        $cfg = $this->config[$tab];
        return (int)Db::query("
            SELECT COUNT(*) FROM Producto WHERE {$cfg['columna_producto']} = ? AND FechaBorrado IS NULL
        ", [$id])->fetchColumn();
    }

    public function darDeBaja(string $tab, int $id): bool {
        $cfg = $this->config[$tab];
        try {
            Db::query("UPDATE {$cfg['tabla']} SET FechaBorrado = NOW() WHERE Id = ?", [$id]);
            return true;
        } catch (Exception $e) {
            error_log("AtributoModel::darDeBaja($tab) - " . $e->getMessage());
            return false;
        }
    }
}
