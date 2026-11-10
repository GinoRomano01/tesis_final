<?php

class ProductoclienteController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->title = 'Producto — San Plácido';
    }

    private function verificarLogin() {
        if (!isset($_SESSION['cliente_id']) && !isset($_SESSION['usuario_id'])) {
            Toast::new('Iniciá sesión para ver los productos', 'warning');
            Redirect::to('login');
            exit;
        }
    }

    private function normalizarUrl(?string $url): string {
        if (empty($url)) return '';
        return IMG_PRODUCTOS . basename($url);
    }

    public function index($id = null) {
        $this->verificarLogin();

        if (!$id || !is_numeric($id)) {
            Redirect::to('catalogo');
            exit;
        }

        $model    = new ProductoclienteModel();
        $producto = $model->getProducto((int)$id);

        if (!$producto) {
            Toast::new('Producto no encontrado', 'danger');
            Redirect::to('catalogo');
            exit;
        }

        $producto['URLImagen'] = $this->normalizarUrl($producto['URLImagen']);

        $imagenes = $model->getImagenes((int)$id);
        foreach ($imagenes as &$img) {
            $img['URLImagen'] = $this->normalizarUrl($img['URLImagen']);
        }
        unset($img);

        $relacionados = $model->getRelacionados((int)$id, (int)($producto['IdCategoria'] ?? 0));
        foreach ($relacionados as &$rel) {
            $rel['URLImagen'] = $this->normalizarUrl($rel['URLImagen']);
        }
        unset($rel);

        $caracteristicas = $model->getCaracteristicas((int)$id);
        $maderas         = $model->getMaderasProducto((int)$id);

        $resenaModel    = new ResenaModel();
        $resenas        = $resenaModel->listarPorProducto((int)$producto['Id']);
        $resumenResenas = $resenaModel->resumenPorProducto((int)$producto['Id']);

        $this->title = htmlspecialchars($producto['NombredelProducto']) . ' — San Plácido';

        $this->render('producto', compact(
            'producto', 'imagenes', 'caracteristicas', 'maderas', 'relacionados',
            'resenas', 'resumenResenas'
        ));
    }

}