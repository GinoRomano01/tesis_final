<?php
class ResenaAnalisisModel extends Model {

    protected $table = 'ResenaAnalisisIA';

    public function guardar(int $idResena, array $analisis): int {
        $sql = "INSERT INTO ResenaAnalisisIA
                (IdResena, Sentimiento, ScoreSentimiento, ScoreToxicidad,
                 Categorias, Flags, ResumenCorto, ModeloUsado, TokensConsumidos)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        Db::query($sql, [
            $idResena,
            $analisis['sentimiento'],
            $analisis['score_sentimiento'],
            $analisis['score_toxicidad'],
            json_encode($analisis['categorias'] ?? [], JSON_UNESCAPED_UNICODE),
            json_encode($analisis['flags']      ?? [], JSON_UNESCAPED_UNICODE),
            $analisis['resumen_corto'] ?? null,
            $analisis['modelo_usado'] ?? GROQ_MODEL,
            (int)($analisis['tokens'] ?? 0),
        ]);
        return (int) Db::getInstance()->getConnection()->lastInsertId();
    }

    public function metricas(?string $desde = null, ?string $hasta = null): array {
        $where  = "WHERE 1=1";
        $params = [];
        if ($desde) { $where .= " AND a.FechaAnalisis >= ?"; $params[] = $desde . ' 00:00:00'; }
        if ($hasta) { $where .= " AND a.FechaAnalisis <= ?"; $params[] = $hasta . ' 23:59:59'; }

        $dist = Db::query(
            "SELECT Sentimiento, COUNT(*) AS n
             FROM ResenaAnalisisIA a $where
             GROUP BY Sentimiento", $params
        )->fetchAll();

        $sent = ['positivo'=>0,'neutro'=>0,'negativo'=>0];
        foreach ($dist as $r) { $sent[$r['Sentimiento']] = (int)$r['n']; }
        $totalSent = array_sum($sent);

        $prom = Db::query(
            "SELECT AVG(ScoreSentimiento) AS s, AVG(ScoreToxicidad) AS t, SUM(TokensConsumidos) AS tk
             FROM ResenaAnalisisIA a $where", $params
        )->fetch();

        $cats = Db::query(
            "SELECT Categorias FROM ResenaAnalisisIA a $where", $params
        )->fetchAll();
        $catCount = [];
        foreach ($cats as $row) {
            $arr = json_decode($row['Categorias'] ?? '[]', true) ?: [];
            foreach ($arr as $c) { $catCount[$c] = ($catCount[$c] ?? 0) + 1; }
        }
        arsort($catCount);

        $evo = Db::query(
            "SELECT DATE(FechaAnalisis) AS dia, Sentimiento, COUNT(*) AS n
             FROM ResenaAnalisisIA
             WHERE FechaAnalisis >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(FechaAnalisis), Sentimiento
             ORDER BY dia ASC"
        )->fetchAll();

        return [
            'total'              => $totalSent,
            'sentimiento'        => $sent,
            'promedio_sent'      => round((float)($prom['s'] ?? 0), 3),
            'promedio_toxicidad' => round((float)($prom['t'] ?? 0), 3),
            'tokens_total'       => (int)($prom['tk'] ?? 0),
            'categorias'         => $catCount,
            'evolucion'          => $evo,
        ];
    }
}