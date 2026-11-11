<?php

class CarritoController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->title = 'Mi Carrito — San Plácido';
    }

    private function verificarLogin() {
        if (!isset($_SESSION['cliente_id']) && !isset($_SESSION['usuario_id'])) {
            if ($this->esAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'mensaje' => 'Debés iniciar sesión', 'redirect' => URL . 'login']);
                exit;
            }
            Toast::new('Iniciá sesión para acceder al carrito', 'warning');
            Redirect::to('login');
            exit;
        }
    }

    private function esAjax(): bool {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }

    private function getClienteId(): int {
        return (int)($_SESSION['cliente_id'] ?? $_SESSION['usuario_id'] ?? 0);
    }

    private function normalizarImagenes(array $items): array {
        foreach ($items as &$item) {
            if (!empty($item['URLImagen'])) {
                $item['URLImagen'] = IMG_PRODUCTOS . basename($item['URLImagen']);
            }
        }
        unset($item);
        return $items;
    }

    public function index() {
        $this->verificarLogin();

        $model     = new CarritoModel();
        $clienteId = $this->getClienteId();

        $items    = $this->normalizarImagenes($model->getItemsCarrito($clienteId));
        $subtotal = array_sum(array_map(fn($i) => $i['PrecioVenta'] * $i['Cantidad'], $items));
        $total    = $subtotal;

        $this->render('carrito', compact('items', 'subtotal', 'total'));
    }

    public function agregar() {
        header('Content-Type: application/json');
        $this->verificarLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido']);
            exit;
        }

        $idProducto = (int)($_POST['id_producto'] ?? 0);
        $cantidad   = max(1, (int)($_POST['cantidad'] ?? 1));

        if ($idProducto <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Producto inválido']);
            exit;
        }

        $model     = new CarritoModel();
        $clienteId = $this->getClienteId();
        $carrito   = $model->obtenerOCrearCarrito($clienteId);
        $carritoId = $carrito['Id'];

        $model->agregarItem($carritoId, $idProducto, $cantidad);
        EstadisticasModel::registrarEvento('add_carrito', 'carrito', $idProducto, 'producto', ['cantidad' => $cantidad]);

        $totalItems = $model->contarItems($carritoId);
        $_SESSION['carrito_items'] = $totalItems;
        $_SESSION['carrito_id']    = $carritoId;

        echo json_encode([
            'ok'          => true,
            'mensaje'     => 'Producto agregado al carrito',
            'total_items' => $totalItems,
        ]);
        exit;
    }

    public function datos() {
        header('Content-Type: application/json');
        $this->verificarLogin();

        $model     = new CarritoModel();
        $clienteId = $this->getClienteId();
        $items     = $this->normalizarImagenes($model->getItemsCarrito($clienteId));
        $subtotal  = array_sum(array_map(fn($i) => $i['PrecioVenta'] * $i['Cantidad'], $items));

        echo json_encode([
            'ok'       => true,
            'items'    => $items,
            'subtotal' => $subtotal,
            'total'    => $subtotal,
            'cantidad' => count($items),
        ]);
        exit;
    }

    public function actualizar() {
        header('Content-Type: application/json');
        $this->verificarLogin();

        $idProducto = (int)($_POST['id_producto'] ?? 0);
        $cantidad   = (int)($_POST['cantidad']    ?? 1);

        if ($idProducto <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Producto inválido']);
            exit;
        }

        $model     = new CarritoModel();
        $clienteId = $this->getClienteId();
        $carrito   = $model->obtenerOCrearCarrito($clienteId);

        if ($cantidad <= 0) {
            $model->eliminarItem($carrito['Id'], $idProducto);
        } else {
            $model->actualizarCantidad($carrito['Id'], $idProducto, $cantidad);
        }

        $items      = $this->normalizarImagenes($model->getItemsCarrito($clienteId));
        $subtotal   = array_sum(array_map(fn($i) => $i['PrecioVenta'] * $i['Cantidad'], $items));
        $totalItems = $model->contarItems($carrito['Id']);

        $_SESSION['carrito_items'] = $totalItems;

        echo json_encode([
            'ok'          => true,
            'items'       => $items,
            'subtotal'    => $subtotal,
            'total_items' => $totalItems,
        ]);
        exit;
    }

    public function eliminar() {
        header('Content-Type: application/json');
        $this->verificarLogin();

        $idProducto = (int)($_POST['id_producto'] ?? 0);

        $model     = new CarritoModel();
        $clienteId = $this->getClienteId();
        $carrito   = $model->obtenerOCrearCarrito($clienteId);

        $model->eliminarItem($carrito['Id'], $idProducto);

        $items      = $this->normalizarImagenes($model->getItemsCarrito($clienteId));
        $subtotal   = array_sum(array_map(fn($i) => $i['PrecioVenta'] * $i['Cantidad'], $items));
        $totalItems = $model->contarItems($carrito['Id']);

        $_SESSION['carrito_items'] = $totalItems;

        echo json_encode([
            'ok'          => true,
            'items'       => $items,
            'subtotal'    => $subtotal,
            'total_items' => $totalItems,
        ]);
        exit;
    }

    public function vaciar() {
        header('Content-Type: application/json');
        $this->verificarLogin();

        $model     = new CarritoModel();
        $clienteId = $this->getClienteId();
        $carrito   = $model->obtenerOCrearCarrito($clienteId);

        $model->vaciarCarrito($carrito['Id']);

        $_SESSION['carrito_items'] = 0;

        echo json_encode(['ok' => true, 'items' => [], 'subtotal' => 0, 'total_items' => 0]);
        exit;
    }
}