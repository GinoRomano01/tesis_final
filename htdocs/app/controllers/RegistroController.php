<?php

class RegistroController extends Controller {

    private $model;

    public function __construct() {
        parent::__construct();
        $this->model = new RegistroModel();
        $this->title = 'Registro - San Plácido';
    }

    public function cliente() {
        unset($_SESSION['cliente_data']);
        unset($_SESSION['usuario_data']);

        $tiposDni       = $this->model->obtenerTiposDni();
        $localidades    = $this->model->obtenerLocalidades();
        $tiposDomicilio = $this->model->obtenerTiposDomicilio();

        $this->render('registrocliente', [
            'tiposDni'       => $tiposDni,
            'localidades'    => $localidades,
            'tiposDomicilio' => $tiposDomicilio
        ]);
    }

    public function guardar_cliente() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $required = ['dni', 'tipodni', 'nombre', 'apellido', 'localidad', 'tipo_domicilio'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'error' => "Campo $field es requerido"]);
                exit;
            }
        }

        try {
            if ($this->model->dniExiste($_POST['dni'])) {
                echo json_encode(['success' => false, 'error' => 'DNI ya registrado']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al verificar DNI']);
            exit;
        }

        $_SESSION['cliente_data'] = [
            'DNI'             => trim($_POST['dni']),
            'Nombre'          => mb_strtoupper(trim($_POST['nombre']),   'UTF-8'),
            'Apellido'        => mb_strtoupper(trim($_POST['apellido']), 'UTF-8'),
            'Telefono'        => $_POST['telefono']     ?? null,
            'IdLocalidad'     => $_POST['localidad'],
            'IdTipodeDni'     => $_POST['tipodni'],
            'IdTipoDomicilio' => $_POST['tipo_domicilio'],
            'Calle'           => $_POST['calle']        ?? null,
            'Numero'          => $_POST['numero']       ?? null,
            'Country'         => $_POST['country']      ?? null,
            'Departamento'    => $_POST['departamento'] ?? null,
            'Barrio'          => $_POST['barrio']       ?? null,
            'Piso'            => $_POST['piso']         ?? null,
            'numeroPiso'      => $_POST['numero_piso']  ?? null,
            'CodigoPostal'    => $_POST['codigo_postal'] ?? null,
        ];

        echo json_encode(['success' => true]);
        exit;
    }

    public function usuario() {
        if (!isset($_SESSION['cliente_data'])) {
            Toast::new('Por favor completá primero los datos del cliente', 'warning');
            Redirect::to('registro/cliente');
            return;
        }

        $this->render('registrousuario', [
            'cliente' => $_SESSION['cliente_data']
        ]);
    }

    public function guardar_usuario() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $required = ['nombre_usuario', 'correo', 'password', 'confirmar'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'error' => "Campo $field es requerido"]);
                exit;
            }
        }

        if ($_POST['password'] !== $_POST['confirmar']) {
            echo json_encode(['success' => false, 'error' => 'Las contraseñas no coinciden']);
            exit;
        }

        try {
            if ($this->model->correoExiste($_POST['correo'])) {
                echo json_encode(['success' => false, 'error' => 'Correo ya registrado']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al verificar correo']);
            exit;
        }

        $_SESSION['usuario_data'] = [
            'NombredeUsuario'  => $_POST['nombre_usuario'],
            'CorreoElectronico'=> $_POST['correo'],
            'password'         => $_POST['password']
        ];

        try {
            $codigo    = Mailer::generarCodigoVerificacion();
            $mailer    = new Mailer();
            $resultado = $mailer->enviarCodigoVerificacion($_POST['correo'], $codigo);

            if ($resultado['success']) {
                $_SESSION['codigo_verificacion'] = $codigo;
                $_SESSION['codigo_timestamp']    = time();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al enviar el correo: ' . $resultado['error']]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al enviar código de verificación']);
        }
        exit;
    }

    public function confirmar() {
        if (!isset($_SESSION['cliente_data']) || !isset($_SESSION['usuario_data'])) {
            Toast::new('Sesión expirada, comenzá de nuevo', 'warning');
            Redirect::to('registro/cliente');
            return;
        }
        $this->render('confirmarcorreo');
    }

    public function verificar_codigo() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $codigoIngresado = $_POST['codigo']                    ?? '';
        $codigoGuardado  = $_SESSION['codigo_verificacion']    ?? null;
        $timestamp       = $_SESSION['codigo_timestamp']       ?? 0;

        if ((time() - $timestamp) > 900) {
            echo json_encode(['success' => false, 'error' => 'Código expirado']);
            exit;
        }

        if ($codigoIngresado !== $codigoGuardado) {
            echo json_encode(['success' => false, 'error' => 'Código incorrecto']);
            exit;
        }

        $cliente = $_SESSION['cliente_data'];
        $usuario = $_SESSION['usuario_data'];

        $usuario['Contraseña'] = password_hash($usuario['password'], PASSWORD_BCRYPT);
        unset($usuario['password']);

        try {
            $resultado = $this->model->registrarClienteYUsuario($cliente, $usuario);

            if ($resultado) {
                unset(
                    $_SESSION['cliente_data'],
                    $_SESSION['usuario_data'],
                    $_SESSION['codigo_verificacion'],
                    $_SESSION['codigo_timestamp']
                );
                echo json_encode(['success' => true, 'mensaje' => '¡Registro exitoso! Ya podés iniciar sesión']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al registrar: ' . $e->getMessage()]);
        }
        exit;
    }

    public function verificar_dni() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['existe' => false]);
            exit;
        }

        $dni = $_POST['dni'] ?? '';
        if (empty($dni) || !ctype_digit($dni)) {
            echo json_encode(['existe' => false, 'error' => 'DNI inválido']);
            exit;
        }

        try {
            echo json_encode(['existe' => $this->model->dniExiste($dni)]);
        } catch (Exception $e) {
            echo json_encode(['existe' => false, 'error' => 'Error al verificar']);
        }
        exit;
    }

    public function verificar_correo() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['existe' => false]);
            exit;
        }

        $correo = $_POST['correo'] ?? '';
        if (empty($correo)) {
            echo json_encode(['existe' => false, 'error' => 'Correo vacío']);
            exit;
        }

        try {
            echo json_encode(['existe' => $this->model->correoExiste($correo)]);
        } catch (Exception $e) {
            echo json_encode(['existe' => false, 'error' => 'Error al verificar']);
        }
        exit;
    }

    public function reenviar_codigo() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        if (!isset($_SESSION['usuario_data']['CorreoElectronico'])) {
            echo json_encode(['success' => false, 'error' => 'Sesión inválida']);
            exit;
        }

        $correo = $_SESSION['usuario_data']['CorreoElectronico'];

        try {
            $codigo    = Mailer::generarCodigoVerificacion();
            $mailer    = new Mailer();
            $resultado = $mailer->enviarCodigoVerificacion($correo, $codigo);

            if ($resultado['success']) {
                $_SESSION['codigo_verificacion'] = $codigo;
                $_SESSION['codigo_timestamp']    = time();
                echo json_encode(['success' => true, 'mensaje' => 'Código reenviado']);
            } else {
                echo json_encode(['success' => false, 'error' => $resultado['error']]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al reenviar código']);
        }
        exit;
    }
}