<?php
class UsuarioadminController extends Controller {

    private UsuarioAdminModel $model;

    public function __construct() {
        parent::__construct();
        $this->verificarAdmin();

        if (!puedeVerUsuarios()) {
            Toast::new('Sin permisos para gestionar usuarios.', 'danger');
            Redirect::to('admin/LobbyAdmin');
            exit;
        }
        $this->title = 'Usuarios — San Plácido Admin';
        $this->model = new UsuarioAdminModel();
    }

    private function verificarAdmin(): void {
        if (!isset($_SESSION['usuario_id'])) {
            Toast::new('Debés iniciar sesión', 'warning');
            Redirect::to('login');
            exit;
        }
        if (($_SESSION['tipo_usuario'] ?? 2) == 2) {
            Toast::new('Sin permisos', 'danger');
            Redirect::to('');
            exit;
        }
    }

    public function index(): void {
        $buscar        = trim($_GET['buscar'] ?? '');
        $idTipoUsuario = (int)($_GET['tipo']  ?? 0);
        $mostrarBajas  = isset($_GET['bajas']);

        $usuarios = $mostrarBajas
            ? $this->listarConBajas($buscar, $idTipoUsuario)
            : $this->model->listarUsuarios($buscar, $idTipoUsuario);

        $tiposUsuario = $this->model->getTiposUsuario();
        $tiposRol     = $this->model->getTiposRol();
        $localidades  = $this->model->getLocalidades();
        $tiposDni     = $this->model->getTiposDni();
        $conteos      = $this->model->contarPorTipo();

        $this->render('index', compact(
            'usuarios', 'tiposUsuario', 'tiposRol', 'localidades', 'tiposDni',
            'buscar', 'idTipoUsuario', 'conteos', 'mostrarBajas'
        ));
    }

    private function listarConBajas(string $buscar, int $idTipo): array {
        $params = [];
        $where  = 'WHERE u.FechaBorrado IS NOT NULL';
        if (!empty($buscar)) {
            $where   .= ' AND (c.Nombre LIKE ? OR c.Apellido LIKE ? OR u.CorreoElectronico LIKE ?)';
            $like     = "%$buscar%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        if ($idTipo > 0) {
            $where   .= ' AND u.IdTipodeUsuario = ?';
            $params[] = $idTipo;
        }
        return Db::query("
            SELECT u.Id, u.NombredeUsuario, u.CorreoElectronico, u.Confirmado,
                   u.IdTipodeUsuario, u.IdTipodeRol, u.IdCliente, u.FechaBorrado,
                   tu.Nombre AS TipoUsuarioNombre, tr.Nombre AS TipoRolNombre,
                   c.Nombre AS ClienteNombre, c.Apellido AS ClienteApellido,
                   c.DNI, c.Telefono, c.IdLocalidad, l.Nombre AS LocalidadNombre
            FROM Usuario u
            LEFT JOIN Clientes      c  ON c.Id  = u.IdCliente
            LEFT JOIN TipodeUsuario tu ON tu.Id = u.IdTipodeUsuario
            LEFT JOIN TipodeRol     tr ON tr.Id = u.IdTipodeRol
            LEFT JOIN Localidad     l  ON l.Id  = c.IdLocalidad
            $where ORDER BY u.FechaBorrado DESC
        ", $params)->fetchAll();
    }

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('usuarioadmin');
            exit;
        }

        $id     = (int)($_POST['Id'] ?? 0);
        $correo = trim($_POST['CorreoElectronico'] ?? '');

        if ($this->model->correoExiste($correo, $id)) {
            Toast::new('Ese correo ya está registrado en otro usuario', 'warning');
            Redirect::to('usuarioadmin');
            exit;
        }

        $nombre   = trim($_POST['Nombre']   ?? '');
        $apellido = trim($_POST['Apellido'] ?? '');

        if (empty($nombre) || empty($apellido) || empty($correo)) {
            Toast::new('Nombre, apellido y correo son obligatorios', 'warning');
            Redirect::to('usuarioadmin');
            exit;
        }

        $data = [
            'Nombre'            => mb_strtoupper($nombre,   'UTF-8'),
            'Apellido'          => mb_strtoupper($apellido, 'UTF-8'),
            'Telefono'          => $_POST['Telefono']        ?? null,
            'DNI'               => $_POST['DNI']             ?? null,
            'IdLocalidad'       => $_POST['IdLocalidad']     ?? null,
            'NombredeUsuario'   => trim($_POST['NombredeUsuario']   ?? ''),
            'CorreoElectronico' => $correo,
            'Contrasena'        => $_POST['Contrasena']      ?? '',
            'IdTipodeUsuario'   => $_POST['IdTipodeUsuario'] ?? 2,
            'IdTipodeRol'       => $_POST['IdTipodeRol']     ?? 2,
            'CodigoPostal'      => trim($_POST['CodigoPostal'] ?? '') ?: null,
        ];

        if ($id > 0) {
            $this->model->editarUsuario($id, $data);
            Toast::new('Usuario actualizado correctamente', 'success');
        } else {
            if (empty(trim($data['Contrasena']))) {
                Toast::new('La contraseña es obligatoria al crear un usuario', 'warning');
                Redirect::to('usuarioadmin');
                exit;
            }
            $nuevo = $this->model->crearUsuario($data);
            if (!$nuevo) {
                Toast::new('Error al crear el usuario', 'danger');
                Redirect::to('usuarioadmin');
                exit;
            }

            $this->_notificarUsuarioNuevo($nuevo, $data);

            Toast::new('Usuario creado correctamente', 'success');
        }

        Redirect::to('usuarioadmin');
        exit;
    }

    private function _notificarUsuarioNuevo(int $idUsuario, array $data): void {
        try {
            $roles = [1 => 'Gerente', 2 => 'Cliente', 3 => 'Repartidor', 4 => 'Vendedor', 5 => 'Carpintero'];
            $rol   = $roles[(int)$data['IdTipodeRol']] ?? 'Empleado';

            NotificacionModel::notificarARoles([1], [
                'Tipo'       => 'usuario_nuevo',
                'Titulo'     => 'Nuevo usuario creado',
                'Contenido'  => $data['Nombre'] . ' ' . $data['Apellido'] . ' — Rol: ' . $rol,
                'UrlDestino' => 'usuarioadmin',
                'Icono'      => 'fa-user-plus',
            ]);
        } catch (\Exception $e) {
            error_log('UsuarioadminController::_notificarUsuarioNuevo - usuario #' . $idUsuario . ' - ' . $e->getMessage());
        }
    }

    public function baja($id = null): void {
        $id = (int)$id;
        if ($id <= 0 || $id === (int)($_SESSION['usuario_id'] ?? 0)) {
            Toast::new('No podés dar de baja este usuario', 'warning');
            Redirect::to('usuarioadmin');
            exit;
        }
        $this->model->darDeBaja($id);
        Toast::new('Usuario dado de baja', 'success');
        Redirect::to('usuarioadmin');
        exit;
    }

    public function checkcorreo(): void {
        header('Content-Type: application/json');
        $correo = trim($_POST['correo'] ?? '');
        $id     = (int)($_POST['id']    ?? 0);
        echo json_encode(['existe' => $this->model->correoExiste($correo, $id)]);
        exit;
    }

    public function restaurar($id = null): void {
        checkPermiso('puedeRestaurarUsuarios');
        $id = (int)$id;
        if ($id <= 0) {
            Redirect::to('usuarioadmin');
            exit;
        }
        $this->model->restaurar($id);
        Toast::new('Usuario restaurado', 'success');
        Redirect::to('usuarioadmin?bajas');
        exit;
    }
}