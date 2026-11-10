<?php
class GroqClient {

    public static function chat(array $mensajes, bool $forzarJson = false, string $modelo = null): array {

        $payload = [
            'model'       => $modelo ?: GROQ_MODEL,
            'messages'    => $mensajes,
            'temperature' => 0.2,
        ];

        if ($forzarJson) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        $ch = curl_init(GROQ_API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => GROK_TIMEOUT,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . GROQ_API_KEY,
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);

        $raw       = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($raw === false || $curlError) {
            return ['ok'=>false,'data'=>null,'raw'=>$raw,'tokens'=>0,'error'=>'cURL: '.$curlError];
        }
        if ($httpCode < 200 || $httpCode >= 300) {
            return ['ok'=>false,'data'=>null,'raw'=>$raw,'tokens'=>0,'error'=>'HTTP '.$httpCode.' — '.$raw];
        }

        $json = json_decode($raw, true);
        if (!is_array($json) || empty($json['choices'][0]['message']['content'])) {
            return ['ok'=>false,'data'=>null,'raw'=>$raw,'tokens'=>0,'error'=>'Respuesta inválida'];
        }

        $content = $json['choices'][0]['message']['content'];
        $tokens  = $json['usage']['total_tokens'] ?? 0;
        $data    = $content;

        if ($forzarJson) {
            $parsed = json_decode($content, true);
            if (!is_array($parsed)) {
                $limpio = preg_replace('/^```(?:json)?|```$/m', '', trim($content));
                $parsed = json_decode(trim($limpio), true);
                if (!is_array($parsed)) {
                    return ['ok'=>false,'data'=>null,'raw'=>$raw,'tokens'=>$tokens,'error'=>'JSON inválido'];
                }
            }
            $data = $parsed;
        }

        return ['ok'=>true,'data'=>$data,'raw'=>$raw,'tokens'=>$tokens,'error'=>null];
    }
}