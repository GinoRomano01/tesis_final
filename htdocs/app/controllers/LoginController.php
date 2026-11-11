<?php

class LoginController extends Controller {
    
    private $model;
    
    public function __construct() {
        parent::__construct();
        $this->model = new UsuarioModel();
        $this->title = 'Iniciar Sesión - San Plácido';
    }
    
    public function index() {
        if (isset($_SESSION['cliente_id'])) {
            $tipoUsuario = $_SESSION['tipo_usuario'] ?? 2;
            if ($tipoUsuario == 2) {
                Redirect::to('');
            } else {
                Redirect::to('admin/LobbyAdmin');
            }
            return;
        }
        
        if (!defined('VIEWS')) {
            define('VIEWS', TEMPLATES . 'views' . DS);
        }
        
        $title     = $this->title;
        $site_name = get_sitename();
        
        require_once VIEWS . 'cliente' . DS . 'login.php';
    }
    
    public function procesar() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }
        
        $correo   = $_POST['correo']   ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($correo) || empty($password)) {
            echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios']);
            exit;
        }
        
        try {
            $usuario = $this->model->buscarPorCorreo($correo);
            
            if (!$usuario) {
                echo json_encode(['success' => false, 'error' => 'Credenciales incorrectas']);
                exit;
            }
            
            if (!password_verify($password, $usuario['Contraseña'])) {
                echo json_encode(['success' => false, 'error' => 'Credenciales incorrectas']);
                exit;
            }
            
            $_SESSION['cliente_id']      = $usuario['IdCliente'];
            $_SESSION['usuario_id']      = $usuario['Id'];
            $_SESSION['nombre_usuario']  = $usuario['NombredeUsuario'];
            $_SESSION['correo']          = $usuario['CorreoElectronico'];
            $_SESSION['nombre_completo'] = $usuario['Nombre'] . ' ' . $usuario['Apellido'];
            $_SESSION['tipo_usuario']    = $usuario['IdTipodeUsuario'];
            $_SESSION['tipo_rol']        = $usuario['IdTipodeRol'];
            
            $redirect = ($usuario['IdTipodeUsuario'] == 2) ? URL : URL . 'admin/LobbyAdmin';
            
            echo json_encode([
                'success' => true,
                'mensaje' => 'Inicio de sesión exitoso',
                'redirect' => $redirect
            ]);
            exit;
            
        } catch (Exception $e) {
            error_log('Error en login: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error'   => 'Error al procesar el login. Por favor, intentá de nuevo.'
            ]);
            exit;
        }
    }
    
    public function logout() {
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        
        session_destroy();
        session_start();
        Toast::new('Has cerrado sesión correctamente', 'success');
        Redirect::to('');
    }

    public function recuperar() {
        if (!defined('VIEWS')) {
            define('VIEWS', TEMPLATES . 'views' . DS);
        }
        require_once VIEWS . 'cliente' . DS . 'recuperar.php';
    }
    
    public function enviar_codigo() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $body   = json_decode(file_get_contents('php://input'), true);
        $correo = trim($body['correo'] ?? '');

        if (!$correo || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'error' => 'Correo electrónico inválido.']);
            exit;
        }

        $ultimoEnvio = $_SESSION['recuperar_ultimo_envio'] ?? 0;
        if (time() - $ultimoEnvio < 60) {
            echo json_encode(['ok' => false, 'error' => 'Esperá un minuto antes de solicitar otro código.']);
            exit;
        }

        $usuario = $this->model->buscarPorCorreo($correo);
        if (!$usuario) {
            $_SESSION['recuperar_ultimo_envio'] = time();
            echo json_encode(['ok' => true]);
            exit;
        }

        $codigo = Mailer::generarCodigoVerificacion();

        $_SESSION['recuperar_codigo']       = password_hash($codigo, PASSWORD_DEFAULT);
        $_SESSION['recuperar_correo']       = $correo;
        $_SESSION['recuperar_expira']       = time() + 900;
        $_SESSION['recuperar_intentos']     = 0;
        $_SESSION['recuperar_ultimo_envio'] = time();

        $mailer    = new Mailer();
        $resultado = $mailer->enviarCodigoVerificacion($correo, $codigo);

        if (!$resultado['success']) {
            error_log('Mailer error recuperar: ' . $resultado['error']);
            echo json_encode(['ok' => false, 'error' => 'Error al enviar el correo. Intentá más tarde.']);
            exit;
        }

        echo json_encode(['ok' => true]);
        exit;
    }

    public function verificar_codigo() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        $body   = json_decode(file_get_contents('php://input'), true);
        $correo = trim($body['correo'] ?? '');
        $codigo = trim($body['codigo'] ?? '');

        if (empty($_SESSION['recuperar_codigo']) || empty($_SESSION['recuperar_correo'])) {
            echo json_encode(['ok' => false, 'error' => 'Sesión expirada. Solicitá un nuevo código.']);
            exit;
        }

        if ($_SESSION['recuperar_correo'] !== $correo) {
            echo json_encode(['ok' => false, 'error' => 'Error de sesión. Recargá la página.']);
            exit;
        }

        if (time() > ($_SESSION['recuperar_expira'] ?? 0)) {
            unset($_SESSION['recuperar_codigo'], $_SESSION['recuperar_correo'],
                  $_SESSION['recuperar_expira'], $_SESSION['recuperar_intentos']);
            echo json_encode(['ok' => false, 'error' => 'El código expiró. Solicitá uno nuevo.']);
            exit;
        }

        $_SESSION['recuperar_intentos'] = ($_SESSION['recuperar_intentos'] ?? 0) + 1;
        if ($_SESSION['recuperar_intentos'] > 5) {
            unset($_SESSION['recuperar_codigo'], $_SESSION['recuperar_correo'],
                  $_SESSION['recuperar_expira'], $_SESSION['recuperar_intentos']);
            echo json_encode(['ok' => false, 'error' => 'Demasiados intentos. Solicitá un nuevo código.']);
            exit;
        }

        if (!password_verify($codigo, $_SESSION['recuperar_codigo'])) {
            $restantes = 5 - $_SESSION['recuperar_intentos'];
            echo json_encode([
                'ok'    => false,
                'error' => 'Código incorrecto.' . ($restantes > 0 ? " Te quedan $restantes intentos." : '')
            ]);
            exit;
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['recuperar_token']  = $token;
        $_SESSION['recuperar_valido'] = true;
        unset($_SESSION['recuperar_codigo'], $_SESSION['recuperar_intentos']);

        echo json_encode(['ok' => true, 'token' => $token]);
        exit;
    }

    public function cambiar_password() {
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_level()) ob_clean();

        $body     = json_decode(file_get_contents('php://input'), true);
        $token    = trim($body['token']    ?? '');
        $password = trim($body['password'] ?? '');

        if (empty($_SESSION['recuperar_token'])  ||
            empty($_SESSION['recuperar_correo']) ||
            empty($_SESSION['recuperar_valido'])) {
            echo json_encode(['ok' => false, 'error' => 'Sesión inválida. Comenzá el proceso nuevamente.']);
            exit;
        }

        if ($_SESSION['recuperar_token'] !== $token) {
            echo json_encode(['ok' => false, 'error' => 'Token inválido.']);
            exit;
        }

        if (time() > ($_SESSION['recuperar_expira'] ?? 0)) {
            unset($_SESSION['recuperar_token'], $_SESSION['recuperar_correo'],
                  $_SESSION['recuperar_valido'], $_SESSION['recuperar_expira']);
            echo json_encode(['ok' => false, 'error' => 'La sesión expiró. Comenzá nuevamente.']);
            exit;
        }

        if (strlen($password) < 8) {
            echo json_encode(['ok' => false, 'error' => 'La contraseña debe tener al menos 8 caracteres.']);
            exit;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            echo json_encode(['ok' => false, 'error' => 'La contraseña debe tener al menos una mayúscula.']);
            exit;
        }
        if (!preg_match('/[0-9]/', $password)) {
            echo json_encode(['ok' => false, 'error' => 'La contraseña debe tener al menos un número.']);
            exit;
        }

        $correo  = $_SESSION['recuperar_correo'];
        $usuario = $this->model->buscarPorCorreo($correo);

        if (!$usuario) {
            echo json_encode(['ok' => false, 'error' => 'Usuario no encontrado.']);
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ok   = $this->model->actualizarPassword($usuario['Id'], $hash);

        if (!$ok) {
            echo json_encode(['ok' => false, 'error' => 'Error al guardar la contraseña. Intentá nuevamente.']);
            exit;
        }

        unset(
            $_SESSION['recuperar_token'],
            $_SESSION['recuperar_correo'],
            $_SESSION['recuperar_valido'],
            $_SESSION['recuperar_expira'],
            $_SESSION['recuperar_ultimo_envio']
        );

        echo json_encode(['ok' => true]);
        exit;
    }

    public function recuperar_password() {
        Redirect::to('login/recuperar');
    }
}