<?php

class ConfiguradorController extends Controller {

    private CatalogoModel $model;

    public function __construct() {
        parent::__construct();
        $this->model = new CatalogoModel();
    }

    public function index(): void {
        $data = [
            'tipos'     => $this->model->getTiposProducto(),
            'disenos'   => $this->model->getDiseños(),
            'acabados'  => $this->model->getAcabados(),
            'herrajes'  => $this->model->getHerrajes(),
            'almacenes' => $this->model->getAlmacenamientos(),
            'maderas'   => $this->model->getMaderas(),
        ];
        $this->render('index', $data);
    }                                                             
       
    public function buscar(): void {
        $filtros = [
            'tipo'    => $this->post('tipo')    ?: ($_GET['tipo']    ?? ''),
            'diseño'  => $this->post('diseno')  ?: ($_GET['diseno']  ?? ''),
            'acabado' => $this->post('acabado') ?: ($_GET['acabado'] ?? ''),
            'herraje' => $this->post('herraje') ?: ($_GET['herraje'] ?? ''),
            'almacen' => $this->post('almacen') ?: ($_GET['almacen'] ?? ''),
            'madera'  => $this->post('madera')  ?: ($_GET['madera']  ?? ''),
            'color'   => $this->post('color')   ?: ($_GET['color']   ?? ''),
        ];

        $sinFiltros = empty($filtros['tipo']) && empty($filtros['diseño']) && empty($filtros['acabado'])
            && empty($filtros['herraje']) && empty($filtros['almacen']) && empty($filtros['madera'])
            && empty($filtros['color']);

        $productos = $sinFiltros
            ? $this->model->obtenerUnoAleatorioPorTipo()
            : $this->model->obtenerProductos($filtros, 40, 0);

        header('Content-Type: text/html; charset=utf-8');
        foreach ($productos as $p) {
            $this->renderCard($p);
        }
        if (empty($productos)) {
            echo '<div class="cfg-vacio"><span>🔍</span><p>No encontramos muebles con esa combinación. Probá sacar algún filtro.</p></div>';
        }
    }
                                                             
    private const ACABADOS_CON_COLOR = ['Barnizado', 'Poliuretano brillante', 'Poliuretano mate'];

    public function colores(): void {
        $idAcabado = (int)($_GET['idAcabado'] ?? 0);
        header('Content-Type: application/json; charset=utf-8');

        if ($idAcabado <= 0) { echo json_encode(['colores' => []]); return; }

        $acabado = Db::query("SELECT Nombre FROM TipodeAcabado WHERE Id = ? LIMIT 1", [$idAcabado])->fetch();
        $nombre  = $acabado['Nombre'] ?? '';

        if (!in_array($nombre, self::ACABADOS_CON_COLOR, true)) {
            echo json_encode(['colores' => [], 'nombreAcabado' => $nombre]);
            return;
        }

        echo json_encode([
            'nombreAcabado' => $nombre,
            'colores'       => $this->model->getColoresAcabado(),
        ]);
    }

    private function renderCard(array $p): void {
        ?>
        <div class="prod-card">
            <div class="pc-img">
                <?php if (!empty($p['URLImagen'])): ?>
                    <img src="<?= htmlspecialchars($p['URLImagen']) ?>"
                         alt="<?= htmlspecialchars($p['NombredelProducto']) ?>" loading="lazy">
                <?php else: ?>
                    <div class="pc-ph">🪑</div>
                <?php endif; ?>
                <?php if (!empty($p['NombreCategoria'])): ?>
                    <span class="pc-cat"><?= htmlspecialchars($p['NombreCategoria']) ?></span>
                <?php endif; ?>
            </div>
            <div class="pc-body">
                <div class="pc-nombre"><?= htmlspecialchars($p['NombredelProducto']) ?></div>
                <?php if (!empty($p['NombreTipo'])): ?>
                    <div class="pc-tipo"><?= htmlspecialchars($p['NombreTipo']) ?></div>
                <?php endif; ?>
                <div class="pc-precio">$<?= number_format($p['PrecioVenta'] ?? 0, 2, ',', '.') ?></div>
                <div class="pc-btns">
                    <a href="<?= URL ?>productocliente/index/<?= $p['Id'] ?>" class="btn-ver">
                        <i class="fas fa-eye"></i> Ver
                    </a>
                    <button type="button" class="btn-cart" onclick="agregarCarrito(<?= $p['Id'] ?>, this)">
                        <i class="fas fa-cart-plus"></i> Agregar
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
}
