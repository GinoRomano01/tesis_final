<?php

class HomeController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->title = 'Inicio - San Plácido';
    }
    
    public function index() {
        $categoriaController = new CategoriaController();
        $categorias = $categoriaController->mostrarCategorias();
        $homeProductoModel = new HomeProductoModel();
        $productosNuevos   = $homeProductoModel->obtenerMasNuevos(5);
        $productosVendidos = $homeProductoModel->obtenerMasVendidos(5);
        
        foreach ($productosNuevos as &$p) {
            $p['URLImagen'] = !empty($p['URLImagen'])
                ? IMG_PRODUCTOS . basename($p['URLImagen'])
                : null;
        }
        foreach ($productosVendidos as &$p) {
            $p['URLImagen'] = !empty($p['URLImagen'])
                ? IMG_PRODUCTOS . basename($p['URLImagen'])
                : null;
        }
        unset($p);

        $this->render('index', [
            'categorias'        => $categorias,
            'productosNuevos'   => $productosNuevos,
            'productosVendidos' => $productosVendidos,
        ]);
    }
}