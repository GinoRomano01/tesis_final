<?php
class ResenaHistorialModel extends Model {

    protected $table = 'ResenaHistorial';

    public function registrar(int $idResena, string $accion, ?int $idUsuario = null, array $detalle = []): void {
        Db::query(
            "INSERT INTO ResenaHistorial (IdResena, Accion, IdUsuario, Detalle)
             VALUES (?, ?, ?, ?)",
            [$idResena, $accion, $idUsuario, json_encode($detalle, JSON_UNESCAPED_UNICODE)]
        );
    }

    public function porResena(int $idResena): array {
        return Db::query(
            "SELECT * FROM ResenaHistorial WHERE IdResena = ? ORDER BY Fecha ASC",
            [$idResena]
        )->fetchAll();
    }
}