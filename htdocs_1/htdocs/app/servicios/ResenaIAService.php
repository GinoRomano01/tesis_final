<?php
class ResenaIAService {

    public static function analizar(string $contenido): array {
        $filtro = FiltroOfensivo::analizar($contenido);

        $resp = GroqClient::chat(PromptsResena::analisisCompleto($contenido), true);

        if (!$resp['ok'] || !is_array($resp['data'])) {
            $flags = ['analisis_fallido'];
            if ($filtro['ofensivo']) $flags[] = 'ofensivo';

            return [
                'sentimiento'       => 'neutro',
                'score_sentimiento' => 0,
                'score_toxicidad'   => $filtro['ofensivo'] ? 0.85 : 0.5,
                'categorias'        => [],
                'flags'             => $flags,
                'resumen_corto'     => 'Análisis automático no disponible.',
                'modelo_usado'      => GROQ_MODEL,
                'tokens'            => 0,
                'error'             => $resp['error'],
                'palabras_filtro'   => $filtro['palabras'],
            ];
        }

        $d = $resp['data'];
        $flags = is_array($d['flags'] ?? null) ? $d['flags'] : [];
        $tox   = (float)($d['score_toxicidad'] ?? 0);

        if ($filtro['ofensivo']) {
            if (!in_array('ofensivo', $flags, true)) {
                $flags[] = 'ofensivo';
            }
            $tox = max($tox, 0.80);
        }

        return [
            'sentimiento'       => $d['sentimiento']        ?? 'neutro',
            'score_sentimiento' => (float)($d['score_sentimiento'] ?? 0),
            'score_toxicidad'   => $tox,
            'categorias'        => is_array($d['categorias'] ?? null) ? $d['categorias'] : [],
            'flags'             => $flags,
            'resumen_corto'     => mb_substr((string)($d['resumen_corto'] ?? ''), 0, 200),
            'modelo_usado'      => GROQ_MODEL,
            'tokens'            => $resp['tokens'],
            'error'             => null,
            'palabras_filtro'   => $filtro['palabras'],
        ];
    }

    public static function embellecer(string $contenido): array {
        $resp = GroqClient::chat(PromptsResena::embellecer($contenido), false);
        if (!$resp['ok'] || !is_string($resp['data'])) {
            return ['ok'=>false, 'texto'=>$contenido, 'error'=>$resp['error']];
        }
        return ['ok'=>true, 'texto'=>trim($resp['data']), 'error'=>null];
    }

    public static function decidirEstado(array $analisis): string {
        $flags = $analisis['flags'] ?? [];

        if (in_array('ofensivo', $flags, true)) {
            return 'en_revision';
        }
        if (in_array('spam', $flags, true)) {
            return 'rechazada';
        }
        if (in_array('analisis_fallido', $flags, true)) {
            return 'en_revision';
        }

        $tox = (float)($analisis['score_toxicidad'] ?? 0.5);
        if ($tox <= RESENA_AUTO_APROBAR_TOXICIDAD_MAX) return 'aprobada';
        if ($tox >= RESENA_AUTO_RECHAZAR_TOXICIDAD_MIN) return 'rechazada';
        return 'en_revision';
    }
}