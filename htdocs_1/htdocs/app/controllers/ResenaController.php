<?php
class ResenaController extends Controller {

    public function index() {
        Redirect::to('catalogo');
    }

    public function guardar() {
        if (empty($_SESSION['usuario_id'])) {
            $this->redirectWithMessage('login', 'Tenés que iniciar sesión para dejar una reseña.', 'warning');
            return;
        }

        if (!$this->validatePost(['id_producto', 'puntuacion', 'contenido'])) {
            $this->redirectWithMessage('catalogo', 'Faltan datos en la reseña.', 'danger');
            return;
        }

        $idCliente   = (int) ($_SESSION['cliente_id'] ?? $_SESSION['usuario_id']);
        $idProducto  = (int) $this->post('id_producto');
        $puntuacion  = max(1, min(5, (int) $this->post('puntuacion')));
        $titulo      = $this->post('titulo');
        $contenidoOg = trim($_POST['contenido']);

        if (mb_strlen($contenidoOg) < 10) {
            $this->redirectWithMessage('productocliente/index/'.$idProducto,
                'La reseña es demasiado corta (mínimo 10 caracteres).', 'warning');
            return;
        }
        if (mb_strlen($contenidoOg) > RESENA_MAX_CARACTERES) {
            $contenidoOg = mb_substr($contenidoOg, 0, RESENA_MAX_CARACTERES);
        }

        $contenidoFinal = $contenidoOg;
        $fueEmbellecida = false;
        $emb = ResenaIAService::embellecer($contenidoOg);
        if ($emb['ok'] && !empty($emb['texto'])) {
            $contenidoFinal = $emb['texto'];
            $fueEmbellecida = true;
        }

        $analisis = ResenaIAService::analizar($contenidoFinal);

        $estado = ResenaIAService::decidirEstado($analisis);

        $resenaModel = new ResenaModel();
        $idResena = $resenaModel->crear([
            'IdCliente'         => $idCliente,
            'IdProducto'        => $idProducto,
            'Puntuacion'        => $puntuacion,
            'Titulo'            => $titulo,
            'ContenidoOriginal' => $contenidoOg,
            'ContenidoPublicado'=> $contenidoFinal,
            'Estado'            => $estado,
            'FueEmbellecida'    => $fueEmbellecida,
        ]);

        (new ResenaAnalisisModel())->guardar($idResena, $analisis);

        $hist = new ResenaHistorialModel();
        $hist->registrar($idResena, 'creada', $idCliente, ['embellecida' => $fueEmbellecida]);
        if ($fueEmbellecida) {
            $hist->registrar($idResena, 'embellecida', $idCliente, []);
        }
        $hist->registrar($idResena, 'analizada', null, [
            'sentimiento'   => $analisis['sentimiento'],
            'toxicidad'     => $analisis['score_toxicidad'],
            'flags'         => $analisis['flags'],
            'estado_final'  => $estado,
        ]);

        if ($estado === 'en_revision') {
            $this->_notificarModeradores($idResena, $idProducto, $puntuacion, $analisis);
        }

        $map = [
            'aprobada'    => ['¡Gracias! Tu reseña fue publicada.', 'success'],
            'en_revision' => ['Gracias por tu reseña. Está en revisión y se publicará pronto.', 'info'],
            'rechazada'   => ['Tu reseña no cumple con las normas de la comunidad y no fue publicada.', 'warning'],
        ];
        list($msg, $tipo) = $map[$estado] ?? ['Tu reseña fue recibida.', 'info'];

        $this->redirectWithMessage('productocliente/index/'.$idProducto, $msg, $tipo);
    }

    private function _notificarModeradores(int $idResena, int $idProducto, int $puntuacion, array $analisis): void {
        try {
            $flags = $analisis['flags'] ?? [];
            $motivo = !empty($flags) ? implode(', ', $flags) : 'sentimiento/puntuación fuera de rango';

            NotificacionModel::notificarARoles([1], [
                'Tipo'       => 'resena_pendiente',
                'Titulo'     => 'Reseña marcada para revisión',
                'Contenido'  => "Puntuación {$puntuacion}/5 — motivo: {$motivo}",
                'UrlDestino' => 'estadisticas/resenas',
                'Icono'      => 'fa-flag',
            ]);
        } catch (\Exception $e) {
            error_log('ResenaController::_notificarModeradores - reseña #' . $idResena . ' - ' . $e->getMessage());
        }
    }
}