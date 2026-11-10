<?php

class NotificacionController extends Controller {

    public function index() {
        header('Content-Type: application/json');

        $items = NotificacionModel::ultimasParaActual(8);
        $noLeidas = NotificacionModel::contarNoLeidas();

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
        NotificacionModel::marcarTodasComoLeidas();
        echo json_encode(['ok' => true]);
        exit;
    }

    public function listado() {
        $pagina = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
        $porPagina = 20;

        $resultado = NotificacionModel::paginadoParaActual($pagina, $porPagina);

        $this->render('listado', [
            'title' => 'Notificaciones',
            'notificaciones' => $resultado['items'],
            'total' => $resultado['total'],
            'pagina' => $pagina,
            'porPagina' => $porPagina,
            'totalPaginas' => (int) ceil($resultado['total'] / $porPagina),
        ]);
    }
}