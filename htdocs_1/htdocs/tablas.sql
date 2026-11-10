CREATE DATABASE IF NOT EXISTS SanPlacido;
USE SanPlacido;

-- 2. TABLAS DE CATÁLOGO / REFERENCIA

CREATE TABLE Pais(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30)
);

CREATE TABLE TipoDomicilio(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodePedido(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeEntrega(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE EstadosdeEntrega(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nomnre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeEncargoRemito(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE EstadodePago(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE EntidadBancaria(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE AutorizaciondePago(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    TokendePago INT,
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodePago(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE RazonSocial(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeAcabado(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeDni(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeUsuario(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(20),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeRol(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(20),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE Localidad(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeProducto(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeMadera(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE Categoria(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeMaterial(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeCorte(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeDiseño(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeAlmacenamiento(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    Descripcion VARCHAR(300),
    FechaBorrado DATETIME NULL DEFAULT NULL
);
CREATE TABLE TipodeHerraje(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30),
    Descripcion VARCHAR(300),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

-- 3. TABLAS PRINCIPALES (CON RELACIONES)

CREATE TABLE Domicilio(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Calle VARCHAR(50), 
    Numero INT,
    Country varchar(200),
    Departamento int, 
    Barrio varchar(200),
    IdTipoDomicilio INT,
    
    Piso INT,
    numeroPiso int,
    
    FOREIGN KEY (IdTipoDomicilio) REFERENCES TipoDomicilio(Id)
   
);



CREATE TABLE InsumosdeCarpinteria(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    PrecioUniatrio DECIMAL(10,2),
    Cantidad INT,
    Descripcion VARCHAR(20), 
    IdTipodeMaterial INT,
    IdTipodeCorte INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeMaterial) REFERENCES TipodeMaterial(Id),
    FOREIGN KEY (IdTipodeCorte) REFERENCES TipodeCorte(Id)
);

CREATE TABLE Maderas (
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    CantidadStock INT,
    Alto DECIMAL(10,2),
    Largo DECIMAL(10,2),
    Ancho DECIMAL(10,2),
    IdTipodeMadera INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeMadera) REFERENCES TipodeMadera(Id)
);

CREATE TABLE Stock(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Fecha DATETIME,
    CantitdadTotal INT,
    MontoTotal DECIMAL(10,2),
    IdMaderas INT,
    IdInsumosdeCarpinteria INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdMaderas) REFERENCES Maderas(Id),
    FOREIGN KEY (IdInsumosdeCarpinteria) REFERENCES InsumosdeCarpinteria(Id)
);

CREATE TABLE Producto (
    Id                      INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NombredelProducto       VARCHAR(100),
    Descripcion             TEXT,
    URLImagen               VARCHAR(500),           

    
    Ancho                   DECIMAL(10,2),
    Largo                   DECIMAL(10,2),
    Alto                    DECIMAL(10,2),

    
    CostoTotal              DECIMAL(10,2) DEFAULT 0,

    
    PrecioVenta             DECIMAL(10,2),

    
    IdCategoria             INT,
    IdTipodeProducto        INT,
    IdTipodeDiseño          INT,
    IdTipodeAcabado         INT,
    IdTipodeHerraje         INT NULL,               
    IdTipodeAlmacenamiento  INT NULL,               

    FechaCreacion           DATETIME DEFAULT NOW(),
    FechaBorrado            DATETIME NULL DEFAULT NULL,

    FOREIGN KEY (IdCategoria)            REFERENCES Categoria(Id),
    FOREIGN KEY (IdTipodeProducto)       REFERENCES TipodeProducto(Id),
    FOREIGN KEY (`IdTipodeDiseño`)       REFERENCES TipodeDiseño(Id),
    FOREIGN KEY (IdTipodeAcabado)        REFERENCES TipodeAcabado(Id),
    FOREIGN KEY (IdTipodeHerraje)        REFERENCES TipodeHerraje(Id),
    FOREIGN KEY (IdTipodeAlmacenamiento) REFERENCES TipodeAlmacenamiento(Id)
);


CREATE TABLE ProductoImagenes (
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdProducto   INT NOT NULL,
    URLImagen    VARCHAR(500) NOT NULL,
    Orden        TINYINT NOT NULL DEFAULT 1,        -- 1, 2 ó 3
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdProducto) REFERENCES Producto(Id) ON DELETE CASCADE
);

CREATE INDEX idx_producto_imagenes ON ProductoImagenes (IdProducto);


CREATE TABLE ProductoMaderas (
    Id               INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdProducto       INT NOT NULL,
    IdMadera         INT NOT NULL,
    CantidadNecesaria DECIMAL(10,2) NOT NULL,
    CostoUnitario    DECIMAL(10,2),
    CostoTotal       DECIMAL(10,2),
    Observaciones    VARCHAR(200),
    FechaBorrado     DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdProducto) REFERENCES Producto(Id) ON DELETE CASCADE,
    FOREIGN KEY (IdMadera)   REFERENCES Maderas(Id)
);




CREATE TABLE Clientes (
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
    DNI VARCHAR(300),
    Nombre VARCHAR(30),
    Apellido VARCHAR(30),
    Telefono VARCHAR(20),
    
    IdLocalidad INT,
    IdTipodeDni INT,
    IdDomicilio INT,
    IdTipodomicilio int,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdLocalidad) REFERENCES Localidad(Id),
    FOREIGN KEY (IdDomicilio) REFERENCES Domicilio(Id),
    FOREIGN KEY (IdTipodeDni) REFERENCES TipodeDni(Id),
    FOREIGN KEY (IdtipoDomicilio) REFERENCES tipoDomicilio(Id)
);

CREATE TABLE Usuario(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
    NombredeUsuario VARCHAR(40),
    Contraseña VARCHAR(300),
    CorreoElectronico VARCHAR(50),
    Restablecer INT NULL,
    Confirmado INT NULL,
    Token VARCHAR(700) NULL,
    IdTipodeUsuario INT,
    IdTipodeRol INT,
    IdCliente INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeUsuario) REFERENCES TipodeUsuario (Id),
    FOREIGN KEY (IdTipodeRol) REFERENCES TipodeRol (Id),
    FOREIGN KEY (IdCliente) REFERENCES Clientes (Id)
);

CREATE TABLE Carrito (
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Cantidad INT,
    IdCliente INT,
    FOREIGN KEY (IdCliente) REFERENCES clientes(Id)
);
CREATE TABLE ProductoCarrito(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdProducto int,
    IdCarrito int,
    FOREIGN KEY (IdProducto) REFERENCES Producto(Id),
    FOREIGN KEY (IdCarrito) REFERENCES Carrito(Id)
);

CREATE TABLE Emisor(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
    Nombre VARCHAR(30),
    IdUsuario INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(Id)
);

CREATE TABLE Caja(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    CantidadTotal DECIMAL(10,2),
    FechadeCaja DATETIME,
    IdEmisor INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id)
);

CREATE TABLE EstadoBancarios(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NumeroCuenta VARCHAR(30),
    MontoTotal DECIMAL(10,2),
    TotalNeto DECIMAL(10,2),
    MontosaPagar DECIMAL(10,2),
    IdEmisor INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id)
);

CREATE TABLE DetallesdeBalance(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdProducto INT,
    IdUsuario INT,
    IdStock INT,
    IdCaja INT,
    IdEstadoBancarios INT,
    IdEmisor INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdProducto) REFERENCES Producto(Id),
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(Id),
    FOREIGN KEY (IdStock) REFERENCES Stock(Id),
    FOREIGN KEY (IdCaja) REFERENCES Caja(Id),
    FOREIGN KEY (IdEstadoBancarios) REFERENCES EstadoBancarios(Id),
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id)
);

CREATE TABLE Diseño(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30), 
    FechadeCreación DATETIME,
    UrlDoc VARCHAR(650),
    IdTipodeDiseño INT,
    IdUsuario INT,
    IdEmisor INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeDiseño) REFERENCES TipodeDiseño(Id),
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(Id),
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id)
);

CREATE TABLE SoportedeProducción(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Descripcón VARCHAR(30), 
    CargadeTrabajo INT,
    IdTipodeProducto INT,
    IdDiseño INT,
    IdEmisor INT,
    IdTipodeAcabado INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeProducto) REFERENCES TipodeProducto(Id),
    FOREIGN KEY (IdDiseño) REFERENCES Diseño(Id),
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id),
    FOREIGN KEY (IdTipodeAcabado) REFERENCES TipodeAcabado(Id)
);

CREATE TABLE DatosEmpresa(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30), 
    Apellido VARCHAR(30), 
    Telefono INT,
    Calle VARCHAR(30), 
    Numero INT,
    CorreoElectronico VARCHAR(50), 
    IdRazonSocial INT,
    IdLocalidad INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdRazonSocial) REFERENCES RazonSocial(Id),
    FOREIGN KEY (IdLocalidad) REFERENCES Localidad(Id)
);

CREATE TABLE FacturaCliente(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NumeroFactura INT,
    FechadeEmision DATETIME,
    SubTotal DECIMAL(10,2),
    Impuestos DECIMAL(10,2),
    MontoTotal DECIMAL(10,2),
    Interes DECIMAL(10,2),
    Cuotas INT,
    IdEmisor INT,
    IdTipodePago INT,
    IdEstadodePago INT,
    IdEntidadBancaria INT,
    IdDatosEmpresa INT,
    IdAutorizaciondePago INT,
    IdClientes INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id),
    FOREIGN KEY (IdTipodePago) REFERENCES TipodePago(Id),
    FOREIGN KEY (IdEstadodePago) REFERENCES EstadodePago(Id),
    FOREIGN KEY (IdEntidadBancaria) REFERENCES EntidadBancaria(Id),
    FOREIGN KEY (IdDatosEmpresa) REFERENCES DatosEmpresa(Id),
    FOREIGN KEY (IdAutorizaciondePago) REFERENCES AutorizaciondePago(Id),
    FOREIGN KEY (IdClientes) REFERENCES Clientes(Id)
);

CREATE TABLE DetallesVenta(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Ancho DECIMAL(10,2),
    Alto DECIMAL(10,2),
    Largo DECIMAL(10,2),
    IdTipodeProducto INT,
    IdTipodeMadera INT,
    IdTipodeAcabado INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeProducto) REFERENCES TipodeProducto(Id),
    FOREIGN KEY (IdTipodeMadera) REFERENCES TipodeMadera(Id),
    FOREIGN KEY (IdTipodeAcabado) REFERENCES TipodeAcabado(Id)
);

CREATE TABLE Venta(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NumerodeVenta INT,
    CantidadTotal INT,
    IdCarrito INT,
    IdFacturaCliente INT,
    Identrega int,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    
    FOREIGN KEY (IdCarrito) REFERENCES Carrito(Id),
    FOREIGN KEY (IdFacturaCliente) REFERENCES FacturaCliente(Id)
    
);

CREATE TABLE Pedido(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Estado VARCHAR(30),
    Responsable VARCHAR(50),
    IdVenta INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    
    FOREIGN KEY (IdVenta) REFERENCES Venta(Id)
);
CREATE TABLE Entrega(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    FechadeEntrega DATETIME,
    IdTipodeEntrega INT,
    IdEstadosdeEntrega INT,
    IdUsuario INT,
    IdVenta INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeEntrega) REFERENCES TipodeEntrega(Id),
    FOREIGN KEY (IdEstadosdeEntrega) REFERENCES EstadosdeEntrega(Id),
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(Id),
    FOREIGN KEY (IdVenta) REFERENCES Venta(Id)
);
CREATE TABLE Proveedores(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    cuit INT,
    Nombre VARCHAR(30), 
    Apellido VARCHAR(30), 
    Telefono INT,
    CorreoElectronico VARCHAR(50), 
    Calle VARCHAR(30), 
    Numero INT,
    IdRazonSocial INT,
    IdLocalidad INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdRazonSocial) REFERENCES RazonSocial(Id),
    FOREIGN KEY (IdLocalidad) REFERENCES Localidad(Id)
);

CREATE TABLE DetallesProveedor(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdMaderas INT,
    IdInsumosdeCarpinteria INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdMaderas) REFERENCES Maderas(Id),
    FOREIGN KEY (IdInsumosdeCarpinteria) REFERENCES InsumosdeCarpinteria(Id)
);

CREATE TABLE Remito(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NumerodeRemito INT,
    Cantidad INT,
    PrecioUnitario DECIMAL(10,2),
    Subtotal DECIMAL(10,2),
    FechadeEmision DATETIME,
    IdTipodeEncargoRemito INT,
    IdDetallesProveedor INT,
    IdDatosEmpresa INT,
    IdClientes INT,
    IdEmisor INT,
    IdProveedor INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeEncargoRemito) REFERENCES TipodeEncargoRemito(Id),
    FOREIGN KEY (IdDetallesProveedor) REFERENCES DetallesProveedor(Id),
    FOREIGN KEY (IdDatosEmpresa) REFERENCES DatosEmpresa(Id),
    FOREIGN KEY (IdClientes) REFERENCES Clientes(Id),
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id),
    FOREIGN KEY (IdProveedor) REFERENCES Proveedores(Id)
);



CREATE TABLE PedidosCliente(
    Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdCLientes INT,
    IdTipodePedido INT,
    IdVenta INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdClientes) REFERENCES Clientes(Id),
    FOREIGN KEY (IdTipodePedido) REFERENCES TipodePedido(Id),
    FOREIGN KEY (IdVenta) REFERENCES Venta(Id)
);