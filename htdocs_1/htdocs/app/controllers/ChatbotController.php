<?php

class ChatbotController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    private static function buildSystem(): string {
        $dias = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
        $hoy  = $dias[(int)date('w')] . ' ' . date('d/m/Y');

        return "Hoy es {$hoy}. Sos el asistente virtual de San Plácido, una mueblería-carpintería de e-commerce que fabrica muebles a pedido. Respondés consultas de clientes de forma amable, breve y clara. Solo respondés sobre lo que sabés del negocio. Si la consulta es muy específica, personal o compleja, indicás que el cliente se contacte por WhatsApp.

INFORMACIÓN DEL NEGOCIO:
- Nombre: San Plácido
- Rubro: Mueblería y carpintería. Muebles fabricados a pedido.
- Canal de venta: aplicación web con catálogo, carrito de compras y sección de compras donde el cliente puede ver su historial de compras y facturas.

PROCESO DE COMPRA:
- El cliente navega el catálogo, elige productos y los agrega al carrito.
- Desde el carrito puede iniciar la compra.
- Al finalizar la compra se genera un código de envío único.

MÉTODOS DE PAGO:
- Tarjeta de débito o crédito (procesado de forma nativa en la app, validado por MercadoPago).
- Pago en efectivo mediante el panel de MercadoPago (cupón de pago).
- También se puede pagar directamente desde la app.
- Todos los pagos se validan y aprueban a través de MercadoPago.

ENVÍOS Y RETIRO:
- Si el cliente es de Córdoba Capital: tiene disponible envío a domicilio.
- Si el cliente es de otra zona: debe retirar el pedido en sucursal.
- El código de envío sirve para el envío, retiro en sucursal o consultas sobre el pedido.

PERÍODO DE CANCELACIÓN Y PEDIDOS:
- Luego de cada compra hay un período de cancelación de 3 días (72 horas).
- Después de ese período, el producto pasa a estado de pedido y se comienza a elaborar.
- Una vez en producción, se elabora y se entrega al cliente.

CANCELACIONES Y DEVOLUCIONES:
- Para cancelar o solicitar devolución, el cliente debe contactarse con la empresa.
- La empresa emite una nota de crédito o gestiona la cancelación del pago con tarjeta.
- No se hacen cancelaciones automáticas desde la app.

PROMOCIONES Y DESCUENTOS:
- Por el momento no hay promociones ni descuentos activos.

CONTACTO:
- En la página hay una sección Nosotros con descripción del local.
- WhatsApp: +54 3543 579974

HORARIOS:
- Lun a Vie: 8:00 a 18:00
- Sábados: 9:00 a 13:00
- Domingos: Cerrado

REGLAS — SEGUÍ ESTO AL PIE DE LA LETRA:
- Solo respondés sobre muebles de madera, carpintería, el proceso de compra, pagos, envíos y el negocio San Plácido.
- Si te preguntan por cualquier otra cosa (comida, tecnología, otras tiendas, temas generales, etc.), respondés ÚNICAMENTE: 'Solo puedo ayudarte con consultas sobre San Plácido y nuestros muebles. Para otra consulta, contactanos por WhatsApp: https://wa.me/543543579974'
- NUNCA confirmes que vendemos algo que no sean muebles o trabajos de carpintería.
- NUNCA inventes productos, precios, stock ni información que no esté en este texto.
- Si la consulta requiere atención personalizada, derivá a WhatsApp: https://wa.me/543543579974
- Respondé en español, máximo 4 oraciones, sin markdown.

EJEMPLO DE RESPUESTA CORRECTA ante pregunta fuera de tema:
Usuario: '¿Venden panchos?'
Asistente: 'Solo puedo ayudarte con consultas sobre San Plácido y nuestros muebles de madera. Para otra consulta, contactanos por WhatsApp: https://wa.me/543543579974'

Usuario: '¿Cuánto sale un iPhone?'
Asistente: 'Solo puedo ayudarte con consultas sobre San Plácido y nuestros muebles de madera. Para otra consulta, contactanos por WhatsApp: https://wa.me/543543579974'";
    }

    public function mensaje(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonError('Método no permitido', 405);
        }

        $body = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->jsonError('JSON inválido', 400);
        }

        $messages = $body['messages'] ?? [];
        if (empty($messages) || !is_array($messages)) {
            $this->jsonError('Sin mensajes', 400);
        }

        $clean = [];
        foreach ($messages as $m) {
            $role    = $m['role']    ?? '';
            $content = $m['content'] ?? '';
            if (!in_array($role, ['user', 'assistant'], true)) continue;
            if (!is_string($content) || trim($content) === '')  continue;
            $clean[] = [
                'role'    => $role,
                'content' => substr(trim($content), 0, 2000),
            ];
        }

        if (empty($clean)) {
            $this->jsonError('Mensajes inválidos', 400);
        }

        $clean = array_slice($clean, -10);

        $messages_payload = array_merge(
            [['role' => 'system', 'content' => self::buildSystem()]],
            $clean
        );

        $payload = json_encode([
            'model'       => 'llama-3.3-70b-versatile',
            'messages'    => $messages_payload,
            'max_tokens'  => 400,
            'temperature' => 0.7,
        ]);

        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . GROQ_API_KEY,
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            error_log('Chatbot cURL error: ' . $curlErr);
            $this->jsonError('Error de conexión', 502);
        }

        $data  = json_decode($response, true);
        $reply = $data['choices'][0]['message']['content'] ?? null;

        if ($httpCode !== 200 || !$reply) {
            $this->jsonOk(['reply' =>
                'DEBUG — HTTP: ' . $httpCode .
                ' | Resp: ' . substr($response, 0, 300)
            ]);
        }

        $this->jsonOk(['reply' => trim($reply)]);
    }

    private function jsonOk(array $data): void {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    private function jsonError(string $msg, int $code = 400): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $msg]);
        exit;
    }
}