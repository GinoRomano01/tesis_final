<?php
session_start();
require_once 'app/config/core_config.php';
require_once CLASSES . 'Db.php';
require_once CLASSES . 'Model.php';
require_once MODELS . 'RegistroModel.php';

echo '<h1>🧪 Test Vista Directa</h1>';

// Obtener datos
$model = new RegistroModel();
$tiposDni = $model->obtenerTiposDni();
$localidades = $model->obtenerLocalidades();
$tiposDomicilio = $model->obtenerTiposDomicilio();

echo '<h2>Datos obtenidos del modelo:</h2>';
echo 'TiposDNI: ' . count($tiposDni) . '<br>';
echo 'Localidades: ' . count($localidades) . '<br>';
echo 'TiposDomicilio: ' . count($tiposDomicilio) . '<br>';

// Simular extract()
$data = [
    'tiposDni' => $tiposDni,
    'localidades' => $localidades,
    'tiposDomicilio' => $tiposDomicilio
];

extract($data);

echo '<h2>Después de extract():</h2>';
echo 'isset($tiposDni): ' . (isset($tiposDni) ? 'SÍ' : 'NO') . '<br>';
echo 'isset($localidades): ' . (isset($localidades) ? 'SÍ' : 'NO') . '<br>';
echo 'isset($tiposDomicilio): ' . (isset($tiposDomicilio) ? 'SÍ' : 'NO') . '<br>';

if (isset($tiposDni)) {
    echo '<h2>Select de Tipos DNI:</h2>';
    echo '<select>';
    echo '<option value="">Seleccione...</option>';
    foreach ($tiposDni as $t) {
        echo '<option value="' . $t['Id'] . '">' . htmlspecialchars($t['Nombre']) . '</option>';
    }
    echo '</select>';
}
?>