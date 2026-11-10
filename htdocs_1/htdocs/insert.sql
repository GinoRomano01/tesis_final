USE SanPlacido;
-- 1️⃣ Insertar un domicilio
INSERT INTO Domicilio (Calle, Numero, Country, Departamento, Barrio, IdTipoDomicilio, Piso, numeroPiso)
VALUES ('Belgrano', 742, NULL, NULL, 'Nueva Córdoba', 1, NULL, NULL);

-- Guardar el ID del domicilio recién insertado
SET @id_domicilio := LAST_INSERT_ID();

-- 2️⃣ Insertar un cliente vinculado a ese domicilio
INSERT INTO Clientes (DNI, Nombre, Apellido, Telefono, IdLocalidad, IdTipodeDni, IdDomicilio, IdTipodomicilio)
VALUES ('46000999', 'Lucía', 'Fernández', '3516001234', 1, 1, @id_domicilio, 1);

-- Guardar el ID del cliente recién insertado
SET @id_cliente := LAST_INSERT_ID();

-- 3️⃣ Insertar un usuario vinculado al cliente (tipo_usuario = 3)
INSERT INTO Usuario (
    NombredeUsuario, Contraseña, CorreoElectronico, Restablecer, Confirmado,
    Token, IdTipodeUsuario, IdTipodeRol, IdCliente
)
VALUES (
    'gino_admin',
    -- Contraseña encriptada: password_hash('admin246', PASSWORD_DEFAULT)
    '$2y$10$NJqMee14zZe0szZVFk4Yl.rwKzyBRUmvd6.yu1Oy.Wf1Ch7TWr41i',
    'lucia.fernandez@example.com',
    NULL,
    1,
    NULL,
    3,  -- tipo de usuario (por ejemplo, administrador)
    1,  -- rol básico o admin, según tu tabla TipodeRol
    @id_cliente
);
