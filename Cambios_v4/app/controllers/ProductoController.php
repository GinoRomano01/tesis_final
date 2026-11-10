<?php

class ProductoController extends Controller {

    private $model;
    private $dirImagenes;

    public function __construct() {
        parent::__construct();
        $this->model       = new ProductoModel();
        $this->title       = 'Productos - Panel Admin';
        $this->dirImagenes = ROOT . 'templates' . DS . 'assets' . DS . 'imagenes' . DS . 'productos' . DS;
    }
    private function verificarAdmin(): void {
        if (!isset($_SESSION['usuario_id'])) {
            Toast::new('Debes iniciar sesión primero', 'warning');
            Redirect::to('login'); exit;
        }
        if (($_SESSION['tipo_usuario'] ?? 2) == 2) {
            Toast::new('No tenés permisos para acceder al panel administrativo', 'danger');
            Redirect::to(''); exit;
        }
    }
    public function index(): void {
        $this->verificarAdmin();
        checkPermiso('puedeVerProductos');
        $productos = $this->model->obtenerTodos();
        foreach ($productos as &$p) {
            if (!empty($p['URLImagen'])) {
                $p['URLImagen'] = IMG_PRODUCTOS . basename($p['URLImagen']);
            }
        }
        unset($p);
        $this->render('index', [
            'productos'            => $productos,
            'creaProductosDirecto' => creaProductosDirecto(),
            'puedeAprobarProductos' => puedeAprobarProductos(),
            'pendientesCount'      => $this->model->contarPendientes(),
        ]);
    }
    public function atributosPorTipo($idTipoProducto = null): void {
        $this->verificarAdmin();
        header('Content-Type: application/json');

        $idTipoProducto = (int)$idTipoProducto;
        if ($idTipoProducto <= 0) {
            echo json_encode(['diseños' => [], 'acabados' => [], 'herrajes' => [], 'almacenamientos' => []]);
            exit;
        }

        echo json_encode($this->model->obtenerAtributosPorTipo($idTipoProducto));
        exit;
    }

    public function guardarPrecioPorHora(): void {
        $this->verificarAdmin();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
            exit;
        }
        if (!puedeEditarPrecioHora()) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'No tenés permisos para modificar la tarifa.']);
            exit;
        }

        $valor = (float)str_replace(',', '.', $_POST['PrecioPorHora'] ?? '');
        if ($valor < 0) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'error' => 'El valor no puede ser negativo.']);
            exit;
        }

        $idUsuario = (int)($_SESSION['usuario_id'] ?? 0);
        $guardado  = $this->model->actualizarPrecioPorHora($valor, $idUsuario);
        if (!$guardado) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'No se pudo guardar la tarifa.']);
            exit;
        }

        $actualizados = $this->model->recalcularTodosLosProductos();

        echo json_encode([
            'ok'            => true,
            'precioPorHora' => $valor,
            'actualizados'  => $actualizados,
        ]);
        exit;
    }
    public function crear(): void {
        $this->verificarAdmin();
        checkPermiso('puedeVerProductos');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarCrear(); return;
        }
        $this->render('crear', $this->getDatosFormulario());
    }

    private function procesarCrear(): void {
        if (!$this->validatePost(['NombredelProducto'])) {
            Toast::new('Completá los campos obligatorios', 'warning');
            Redirect::to('producto/crear'); return;
        }

        $datos = $this->recogerDatosPost();                                                                   
                                                                       
        if (creaProductosDirecto()) {
            $datos['EstadoAprobacion'] = 'Aprobado';
            $datos['IdUsuarioSugirio'] = null;
        } else {
            $datos['EstadoAprobacion'] = 'Pendiente';
            $datos['IdUsuarioSugirio'] = (int)($_SESSION['usuario_id'] ?? 0);
        }

        $urlPrincipal = $this->subirImagen('imagen_principal');
        if (!$urlPrincipal) {
            Toast::new('La imagen principal es obligatoria', 'warning');
            Redirect::to('producto/crear'); return;
        }
        $datos['URLImagen'] = $urlPrincipal;

        $nuevoId = $this->model->crear($datos);
        if (!$nuevoId) {
            Toast::new('Error al crear el producto', 'danger');
            Redirect::to('producto/crear'); return;
        }
                
        for ($i = 1; $i <= 3; $i++) {
            $url = $this->subirImagen("imagen_extra_{$i}");
            if ($url) $this->model->agregarImagenExtra($nuevoId, $url, $i);
        }
        
        $this->_guardarMaderas($nuevoId);        
        $this->_guardarInsumos($nuevoId);                                                        
        $this->_guardarPrecioPorHoraSiCorresponde();                                                                
        $this->model->recalcularCostoMateriales($nuevoId);

        if (creaProductosDirecto()) {
            Toast::new('Producto creado correctamente', 'success');
        } else {
            Toast::new('Producto sugerido. Queda pendiente de aprobación.', 'success');
        }
        Redirect::to('producto');
    }                                                                                                                                                      
    public function editar($id = null): void {
        $this->verificarAdmin();
        checkPermiso('puedeVerProductos');
        if (!$id || !is_numeric($id)) {
            Toast::new('Producto no válido', 'warning'); Redirect::to('producto'); return;
        }
        $producto = $this->model->obtenerPorId((int)$id);
        if (!$producto) {
            Toast::new('Producto no encontrado', 'warning'); Redirect::to('producto'); return;
        }
        if (!empty($producto['URLImagen'])) {
            $producto['URLImagen'] = IMG_PRODUCTOS . basename($producto['URLImagen']);
        }
        if (!empty($producto['imagenes_extra'])) {
            foreach ($producto['imagenes_extra'] as &$img) {
                if (!empty($img['URLImagen'])) {
                    $img['URLImagen'] = IMG_PRODUCTOS . basename($img['URLImagen']);
                }
            }
            unset($img);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarEditar((int)$id, $producto); return;
        }
        $this->render('editar', array_merge($this->getDatosFormulario(), ['producto' => $producto]));
    }

    private function procesarEditar($id, $productoActual): void {
        $datos = $this->recogerDatosPost();
        $url   = $this->subirImagen('imagen_principal');
        $datos['URLImagen'] = $url ?: ($productoActual['URLImagen'] ?? '');                                                              
        $estadoActual = $productoActual['EstadoAprobacion'] ?? 'Aprobado';
        if (creaProductosDirecto()) {
            $datos['EstadoAprobacion'] = 'Aprobado';
        } elseif ($estadoActual !== 'Aprobado') {
            $datos['EstadoAprobacion'] = 'Pendiente';
            $datos['MotivoRechazo']    = null;
            $datos['IdUsuarioAprobo']  = null;
            $datos['FechaAprobacion']  = null;
        }

        if (!$this->model->actualizar($id, $datos)) {
            Toast::new('Error al actualizar el producto', 'danger');
            Redirect::to("producto/editar/{$id}"); return;
        }                        
        $hayNuevas = false;
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($_FILES["imagen_extra_{$i}"]['name'])) { $hayNuevas = true; break; }
        }
        if ($hayNuevas) {
            $this->model->eliminarImagenesExtra($id);
            for ($i = 1; $i <= 3; $i++) {
                $urlE = $this->subirImagen("imagen_extra_{$i}");
                if ($urlE) $this->model->agregarImagenExtra($id, $urlE, $i);
            }
        }
                                      
        $this->model->eliminarMaderasProducto($id);
        $this->_guardarMaderas($id);                                     
        $this->model->eliminarInsumosProducto($id);
        $this->_guardarInsumos($id);                                                                                                                     
        $this->_guardarPrecioPorHoraSiCorresponde();                                                          
        $this->model->recalcularCostoMateriales($id);

        Toast::new('Producto actualizado correctamente', 'success');
        Redirect::to('producto');
    }
    public function eliminar($id = null): void {
        $this->verificarAdmin();
        checkPermiso('puedeEliminar');
        if (!$id || !is_numeric($id)) {
            Toast::new('Producto no válido', 'warning'); Redirect::to('producto'); return;
        }
        $this->model->eliminar((int)$id)
            ? Toast::new('Producto eliminado correctamente', 'success')
            : Toast::new('Error al eliminar el producto', 'danger');
        Redirect::to('producto');
    }
    public function pendientes(): void {
        $this->verificarAdmin();
        checkPermiso('puedeAprobarProductos');

        $buscar    = trim($_GET['buscar'] ?? '');
        $productos = $this->model->obtenerPendientes($buscar);
        foreach ($productos as &$p) {
            if (!empty($p['URLImagen'])) {
                $p['URLImagen'] = IMG_PRODUCTOS . basename($p['URLImagen']);
            }
        }
        unset($p);

        $this->render('pendientes', ['productos' => $productos, 'buscar' => $buscar]);
    }
    public function aprobar($id = null): void {
        $this->verificarAdmin();
        checkPermiso('puedeAprobarProductos');

        if (!$id || !is_numeric($id)) {
            Toast::new('Producto no válido', 'warning');
            Redirect::to('producto/pendientes'); return;
        }

        $idUsuario = (int)($_SESSION['usuario_id'] ?? 0);
        $this->model->aprobarProducto((int)$id, $idUsuario);

        Toast::new('Producto aprobado — ya está visible en el catálogo.', 'success');
        Redirect::to('producto/pendientes');
    }
    public function rechazar($id = null): void {
        $this->verificarAdmin();
        checkPermiso('puedeAprobarProductos');

        if (!$id || !is_numeric($id)) {
            Toast::new('Producto no válido', 'warning');
            Redirect::to('producto/pendientes'); return;
        }

        $motivo    = trim($_POST['MotivoRechazo'] ?? '');
        $idUsuario = (int)($_SESSION['usuario_id'] ?? 0);
        $this->model->rechazarProducto((int)$id, $idUsuario, $motivo);

        Toast::new('Producto rechazado.', 'success');
        Redirect::to('producto/pendientes');
    }
    public function actualizarporcentaje($id = null): void {
        $this->verificarAdmin();
        header('Content-Type: application/json');

        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'ID inválido']);
            exit;
        }
        if (!puedeEditarPrecioHora()) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'No tenés permisos para modificar el margen.']);
            exit;
        }

        $porcentaje = (float)str_replace(',', '.', $_POST['PorcentajeGanancia'] ?? '0');
        if ($porcentaje < 0) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'error' => 'El porcentaje no puede ser negativo.']);
            exit;
        }

        $this->model->actualizarPorcentaje((int)$id, $porcentaje);
        $producto = $this->model->obtenerPorId((int)$id);

        echo json_encode([
            'ok'          => true,
            'porcentaje'  => $porcentaje,
            'precioVenta' => (float)($producto['PrecioVenta'] ?? 0),
        ]);
        exit;
    }
    private function _guardarMaderas(int $idProducto): void {
        $ids   = $_POST['madera_id']       ?? [];
        $cant  = $_POST['madera_cantidad']  ?? [];
        $costo = $_POST['madera_costo']     ?? [];
        $obs   = $_POST['madera_obs']       ?? [];
        foreach ($ids as $k => $idMadera) {
            if (!$idMadera) continue;
            $this->model->guardarMaderaProducto($idProducto, [
                'IdMadera'          => $idMadera,
                'CantidadNecesaria' => str_replace(',', '.', $cant[$k]  ?? '0'),
                'CostoUnitario'     => str_replace(',', '.', $costo[$k] ?? '0'),
                'Observaciones'     => $obs[$k] ?? null,
            ]);
        }
    }  
    private function _guardarPrecioPorHoraSiCorresponde(): void {
        if (!isset($_POST['PrecioPorHora'])) return;
        if (!puedeEditarPrecioHora()) return;

        $valor = (float)str_replace(',', '.', $_POST['PrecioPorHora']);
        if ($valor < 0) return;

        $idUsuario = (int)($_SESSION['usuario_id'] ?? 0);
        $this->model->actualizarPrecioPorHora($valor, $idUsuario);
    }
    private function _guardarInsumos(int $idProducto): void {
        $ids   = $_POST['insumo_id']       ?? [];
        $cant  = $_POST['insumo_cantidad']  ?? [];
        $costo = $_POST['insumo_costo']     ?? [];
        $obs   = $_POST['insumo_obs']       ?? [];
        foreach ($ids as $k => $idInsumo) {
            if (!$idInsumo) continue;
            $this->model->guardarInsumoProducto($idProducto, [
                'IdInsumoCarpinteria'=> $idInsumo,
                'CantidadNecesaria'  => str_replace(',', '.', $cant[$k]  ?? '0'),
                'CostoUnitario'      => str_replace(',', '.', $costo[$k] ?? '0'),
                'Observaciones'      => $obs[$k] ?? null,
            ]);
        }
    }
    private function recogerDatosPost(): array {
        return [
            'NombredelProducto'      => $this->post('NombredelProducto'),
            'Descripcion'            => $this->post('Descripcion'),
            'Ancho'                  => $this->post('Ancho')  !== '' ? $this->post('Ancho')  : null,
            'Largo'                  => $this->post('Largo')  !== '' ? $this->post('Largo')  : null,
            'Alto'                   => $this->post('Alto')   !== '' ? $this->post('Alto')   : null,
            'TiempoFabricacionHoras' => $this->post('TiempoFabricacionHoras') ?: 0,                                                                     
            'PrecioVenta'            => $this->post('PrecioVenta') ?: 0,
            'PorcentajeGanancia'     => str_replace(',', '.', $this->post('PorcentajeGanancia') ?: '30'),
            'IdCategoria'            => $this->post('IdCategoria')            ?: null,
            'IdTipodeProducto'       => $this->post('IdTipodeProducto')       ?: null,
            'IdTipodeDiseño'         => $this->post('IdTipodeDiseño')         ?: null,
            'IdTipodeAcabado'        => $this->post('IdTipodeAcabado')        ?: null,
            'IdTipodeHerraje'        => $this->post('IdTipodeHerraje')        ?: null,
            'IdTipodeAlmacenamiento' => $this->post('IdTipodeAlmacenamiento') ?: null,
        ];
    }

    private function subirImagen($inputName): ?string {
        if (!isset($_FILES[$inputName]) ||
            $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK ||
            empty($_FILES[$inputName]['name'])) {
            return null;
        }
        $file     = $_FILES[$inputName];
        $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, $allowed)) {
            Toast::new("El archivo '{$inputName}' no es una imagen válida.", 'warning');
            return null;
        }
        if ($file['size'] > 5 * 1024 * 1024) {
            Toast::new("La imagen supera el máximo de 5 MB.", 'warning');
            return null;
        }
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = uniqid('prod_', true) . '.' . $ext;
        if (!is_dir($this->dirImagenes)) mkdir($this->dirImagenes, 0755, true);
        if (!move_uploaded_file($file['tmp_name'], $this->dirImagenes . $name)) {
            Toast::new("No se pudo guardar la imagen.", 'danger');
            return null;
        }
        return IMG_PRODUCTOS . $name;
    }
    private function getDatosFormulario(): array {
        return [
            'categorias'  => $this->model->obtenerCategorias(),
            'tiposProducto'  => $this->model->obtenerTiposProducto(),
            'tiposDiseño'    => $this->model->obtenerTiposDiseño(),
            'tiposAcabado'  => $this->model->obtenerTiposAcabado(),
            'tiposHerraje'  => $this->model->obtenerTiposHerraje(),
            'tiposAlmacenamiento' => $this->model->obtenerTiposAlmacenamiento(),                                                               
            'precioPorHora' => $this->model->obtenerPrecioPorHora(),
            'puedeEditarPrecioHora' => puedeEditarPrecioHora(),                                                                                                                                 
            'creaProductosDirecto'  => creaProductosDirecto(),                                                                               
            'maderas'  => $this->model->obtenerMaderas(),
            'insumos' => $this->model->obtenerInsumos(),
        ];
    }
}