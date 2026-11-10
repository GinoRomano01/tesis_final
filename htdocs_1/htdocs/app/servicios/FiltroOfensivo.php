<?php
class FiltroOfensivo {

    private static $lista = [
        'boludo', 'boluda', 'pelotudo', 'pelotuda', 'forro', 'forra',
        'garca', 'gil', 'gila', 'pajero', 'pajera', 'sorete',
        'mogolico', 'mogólico', 'tarado', 'tarada', 'choto', 'chota',
        'pendejo', 'pendeja', 'chanta', 'trolo', 'trola',
        'la concha', 'concha de', 'hijo de puta', 'hija de puta', 'hdp',
        'la puta madre', 'reputa', 'put0',

        'mierda', 'mierdas', 'idiota', 'idiotas', 'estupido', 'estúpido',
        'estupida', 'estúpida', 'imbecil', 'imbécil', 'imbeciles',
        'basura', 'asqueroso', 'asquerosa', 'porqueria', 'porquería',
        'joder', 'jodete', 'carajo', 'cagada', 'cagado',

        'chorros', 'chorro', 'estafadores', 'estafador', 'ladrones', 'ladron',
        'ladrón', 'rateros',

        'puto', 'puta', 'putos', 'putas', 'maricon', 'maricón',
    ];

    /**
     * @return array 
     */
    public static function analizar(string $texto): array {
        $normalizado = self::normalizar($texto);
        $encontradas = [];

        foreach (self::$lista as $palabra) {
            $patron = self::construirPatron($palabra);
            if (preg_match($patron, $normalizado)) {
                $encontradas[] = $palabra;
            }
        }

        return [
            'ofensivo' => !empty($encontradas),
            'palabras' => array_unique($encontradas),
        ];
    }

    private static function normalizar(string $texto): string {
        $texto = mb_strtolower($texto, 'UTF-8');

        $acentos = [
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u',
            'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
            'ä'=>'a','ë'=>'e','ï'=>'i','ö'=>'o','ü'=>'u',
            'ñ'=>'n',
        ];
        $texto = strtr($texto, $acentos);

        $leet = ['4'=>'a','3'=>'e','1'=>'i','0'=>'o','$'=>'s','@'=>'a','5'=>'s','7'=>'t'];
        $texto = strtr($texto, $leet);

        $texto = preg_replace('/[\*\.\-_]/', '', $texto);

        return $texto;
    }

    private static function construirPatron(string $palabra): string {
        $palabra = self::normalizar($palabra);
        $partes  = [];
        $len     = mb_strlen($palabra);
        for ($i = 0; $i < $len; $i++) {
            $c = mb_substr($palabra, $i, 1);
            $partes[] = preg_quote($c, '/') . '+';
        }
        $cuerpo = implode('\s*', $partes);
        return '/\b' . $cuerpo . '\b/iu';
    }
}