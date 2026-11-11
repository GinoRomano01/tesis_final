<?php

class NotificacionController extends Controller {

    private function ctx(): ?string {
        $ctx = $_GET['ctx'] ?? null;
        return in_array($ctx, ['cliente', 'admin'], true) ? $ctx : null;
    }
    public function index() {
        header('Content-Type: application/json');

        $items = NotificacionModel::ultimasParaActual(8, $this->ctx());
        $noLeidas = NotificacionModel::contarNoLeidas($this->ctx());

        $items = array_map(function ($n) {
            $n['FechaTexto'] = timeAgo($n['FechaCreacion']);
            return $n;
        }, $items);

        echo json_encode([
            'ok' => true,
            'no_leidas' => $noLeidas,
            'notificaciones' => $items,
        ]);
        exit;
    }

    public function marcarleidas() {
        header('Content-Type: application/json');
        NotificacionModel::marcarTodasComoLeidas($this->ctx());
        echo json_encode(['ok' => true]);
        exit;
    }

    public function listado() {
        $ctx   = $this->ctx();
        $actor = NotificacionModel::actorActual($ctx);
        if (!$actor) {
            Toast::new('Debés iniciar sesión', 'warning');
            Redirect::to('login');
            exit;
        }

        $pagina = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
        $porPagina = 20;

        $resultado = NotificacionModel::paginadoParaActual($pagina, $porPagina, $ctx);

        $vista = $actor['campo'] === 'IdCliente' ? 'listado_cliente' : 'listado';

        $this->render($vista, [
            'title' => 'Notificaciones',
            'notificaciones' => $resultado['items'],
            'total' => $resultado['total'],
            'pagina' => $pagina,
            'porPagina' => $porPagina,
            'totalPaginas' => (int) ceil($resultado['total'] / $porPagina),
        ]);
    }
}