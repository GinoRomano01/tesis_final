<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['cliente'], $data['usuario'])) {
    echo json_encode(['success' => false]);
    exit;
}

$_SESSION['cliente_data'] = $data['cliente'];
$_SESSION['usuario_data'] = $data['usuario'];

echo json_encode(['success' => true]);


?>

<!--
<script>
    if (localStorage.getItem('cliente')) {
        localStorage.removeItem('cliente');
        console.log('Item "cliente" eliminado del localStorage');
    }
</script>

-->