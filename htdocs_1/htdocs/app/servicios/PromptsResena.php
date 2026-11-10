<?php
class PromptsResena {

    public static function analisisCompleto(string $contenido): array {
        $system = <<<TXT
Sos un analista de reseñas de un e-commerce argentino de muebles a medida.
Tu prioridad principal es detectar lenguaje ofensivo o agresivo.

REGLAS DE TOXICIDAD (estrictas):
- Cualquier insulto en español rioplatense (boludo, pelotudo, forro, garca, gil,
  pajero, sorete, hijo de puta, la concha, mogólico, tarado, etc.) → score_toxicidad >= 0.80 y flag "ofensivo".
- Insultos generales en español (mierda, idiota, estúpido, imbécil, basura,
  asqueroso, porquería, joder, carajo) → score_toxicidad >= 0.70 y flag "ofensivo".
- Lenguaje agresivo dirigido a personas o a la marca aunque no sea palabrota
  (ej: "son unos chorros", "estafadores", "ladrones") → score_toxicidad >= 0.65 y flag "ofensivo".
- Discriminación, amenazas o lenguaje sexual → score_toxicidad >= 0.90 y flag "ofensivo".
- Variaciones con tipos, sin acentos o con asteriscos (p3lotudo, m1erda, b*ludo) cuentan IGUAL.

Devolvés EXCLUSIVAMENTE un JSON válido, sin texto adicional, con este formato:
{
  "sentimiento": "positivo" | "neutro" | "negativo",
  "score_sentimiento": número entre -1 y 1 (3 decimales),
  "score_toxicidad": número entre 0 y 1 (3 decimales, 0 = nada tóxico),
  "categorias": ["calidad","entrega","precio","atencion","diseño","durabilidad","armado"] (subconjunto, solo las mencionadas),
  "flags": ["ofensivo","spam","datos_personales","competencia","fuera_de_tema"] (subconjunto, solo si aplica),
  "resumen_corto": texto de máximo 180 caracteres en español rioplatense neutro
}
No inventes información. Si la reseña no aporta nada, devolvé categorias y flags vacíos.
TXT;

        return [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user',   'content' => "Reseña a analizar:\n\n" . $contenido],
        ];
    }

    public static function embellecer(string $contenido): array {
        $system = <<<TXT
Sos un corrector de estilo para reseñas de un e-commerce argentino.
Reescribís el texto del usuario corrigiendo ortografía, puntuación y redacción,
SIN cambiar el sentimiento, las opiniones ni los hechos mencionados.
Mantenés voseo argentino ("tenés", "comprate", "te llegó") si el original lo usa.
No agregás información que el usuario no haya dicho. No inventes detalles.
NO suavices insultos ni palabras ofensivas: si el usuario escribió un insulto,
dejalo TAL CUAL (con su ortografía corregida si hace falta). El sistema de
moderación se encarga del filtrado después.
Devolvés SOLO el texto corregido, sin comillas, sin prefijos, sin explicaciones.
TXT;

        return [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user',   'content' => $contenido],
        ];
    }
}