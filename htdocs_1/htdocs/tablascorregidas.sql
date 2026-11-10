-- =============================================================================
-- BASE DE DATOS: SanPlacido
-- Versión corregida con comentarios de cambios
-- =============================================================================
-- RESUMEN DE CAMBIOS APLICADOS:
--
--  [CRÍTICO-1] EstadosdeEntrega: corregido typo "Nomnre" → "Nombre"
--  [CRÍTICO-2] Eliminada referencia circular Venta ↔ Entrega:
--              Se eliminó Venta.Identrega. La relación ya existe
--              desde Entrega.IdVenta, por lo que Identrega era redundante
--              y causaba imposibilidad de inserción.
--  [CRÍTICO-3] Venta.Identrega no tenía FOREIGN KEY declarada (además
--              del problema circular). Resuelto al eliminar la columna.
--  [IMPORTANTE-4] DetallesVenta: agregado IdVenta INT con FK a Venta(Id)
--              para conectar los detalles con su venta correspondiente.
--  [IMPORTANTE-5] Clientes: eliminada FK redundante IdTipodomicilio
--              ya que Domicilio ya referencia a TipoDomicilio internamente.
--  [IMPORTANTE-6] Stock: agregado CHECK para garantizar que un registro
--              pertenezca a Madera O a Insumo, nunca a ninguno ni a ambos.
--  [IMPORTANTE-7] ProductoCarrito: agregada columna Cantidad INT NOT NULL
--              para representar cuántas unidades de cada producto hay.
--  [MENOR-8]   Proveedores.Telefono y DatosEmpresa.Telefono cambiados
--              de INT a VARCHAR(20) para soportar prefijos, 0800, etc.
--  [MENOR-9]   SoportedeProducción: corregido typo "Descripcón" → "Descripcion"
--  [MENOR-10]  Carrito.Cantidad: marcado como columna calculable/derivada
--              (comentario aclaratorio; la columna se mantiene para cache).
-- =============================================================================

CREATE DATABASE IF NOT EXISTS SanPlacido;
USE SanPlacido;


-- -----------------------------------------------------------------------------
-- TABLAS DE CATÁLOGO / REFERENCIA
-- Sin cambios estructurales, se mantienen igual.
-- -----------------------------------------------------------------------------

CREATE TABLE Pais(
    Id     INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre VARCHAR(30)
);

CREATE TABLE TipoDomicilio(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodePedido(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeEntrega(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

-- [CRÍTICO-1] Corregido typo: "Nomnre" → "Nombre"
-- El typo original hacía que el campo fuera inutilizable en consultas normales.
CREATE TABLE EstadosdeEntrega(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),   -- CAMBIO: era "Nomnre"
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeEncargoRemito(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE EstadodePago(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE EntidadBancaria(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE AutorizaciondePago(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    TokendePago  INT,
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodePago(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE RazonSocial(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeAcabado(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeDni(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeUsuario(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(20),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeRol(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(20),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE Localidad(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeProducto(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeMadera(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE Categoria(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeMaterial(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeCorte(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeDiseño(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeAlmacenamiento(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    Descripcion  VARCHAR(300),
    FechaBorrado DATETIME NULL DEFAULT NULL
);

CREATE TABLE TipodeHerraje(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    Descripcion  VARCHAR(300),
    FechaBorrado DATETIME NULL DEFAULT NULL
);


-- -----------------------------------------------------------------------------
-- TABLAS PRINCIPALES
-- -----------------------------------------------------------------------------

CREATE TABLE Domicilio(
    Id              INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Calle           VARCHAR(50),
    Numero          INT,
    Country         VARCHAR(200),
    Departamento    INT,
    Barrio          VARCHAR(200),
    IdTipoDomicilio INT,
    Piso            INT,
    NumeroPiso      INT,
    FOREIGN KEY (IdTipoDomicilio) REFERENCES TipoDomicilio(Id)
);

CREATE TABLE InsumosdeCarpinteria(
    Id              INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    PrecioUnitario  DECIMAL(10,2),
    Cantidad        INT,
    Descripcion     VARCHAR(20),
    IdTipodeMaterial INT,
    IdTipodeCorte   INT,
    FechaBorrado    DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeMaterial) REFERENCES TipodeMaterial(Id),
    FOREIGN KEY (IdTipodeCorte)    REFERENCES TipodeCorte(Id)
);

CREATE TABLE Maderas(
    Id             INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    CantidadStock  INT,
    Alto           DECIMAL(10,2),
    Largo          DECIMAL(10,2),
    Ancho          DECIMAL(10,2),
    IdTipodeMadera INT,
    FechaBorrado   DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeMadera) REFERENCES TipodeMadera(Id)
);

-- [IMPORTANTE-6] Agregado CHECK para garantizar que cada registro de Stock
-- pertenezca exactamente a una Madera O a un Insumo (nunca a ninguno, nunca
-- a ambos a la vez). Sin esta restricción, era posible insertar filas vacías
-- o ambiguas que rompían la integridad del inventario.
CREATE TABLE Stock(
    Id                       INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Fecha                    DATETIME,
    CantidadTotal            INT,
    MontoTotal               DECIMAL(10,2),
    IdMaderas                INT NULL,
    IdInsumosdeCarpinteria   INT NULL,
    FechaBorrado             DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdMaderas)              REFERENCES Maderas(Id),
    FOREIGN KEY (IdInsumosdeCarpinteria) REFERENCES InsumosdeCarpinteria(Id),
    -- CAMBIO: exactamente uno de los dos debe tener valor
    CONSTRAINT chk_stock_origen CHECK (
        (IdMaderas IS NOT NULL AND IdInsumosdeCarpinteria IS NULL) OR
        (IdMaderas IS NULL     AND IdInsumosdeCarpinteria IS NOT NULL)
    )
);

CREATE TABLE Producto(
    Id                     INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NombredelProducto      VARCHAR(100),
    Descripcion            TEXT,
    URLImagen              VARCHAR(500),
    Ancho                  DECIMAL(10,2),
    Largo                  DECIMAL(10,2),
    Alto                   DECIMAL(10,2),
    CostoTotal             DECIMAL(10,2) DEFAULT 0,
    PrecioVenta            DECIMAL(10,2),
    IdCategoria            INT,
    IdTipodeProducto       INT,
    IdTipodeDiseño         INT,
    IdTipodeAcabado        INT,
    IdTipodeHerraje        INT NULL,
    IdTipodeAlmacenamiento INT NULL,
    FechaCreacion          DATETIME DEFAULT NOW(),
    FechaBorrado           DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdCategoria)            REFERENCES Categoria(Id),
    FOREIGN KEY (IdTipodeProducto)       REFERENCES TipodeProducto(Id),
    FOREIGN KEY (`IdTipodeDiseño`)       REFERENCES TipodeDiseño(Id),
    FOREIGN KEY (IdTipodeAcabado)        REFERENCES TipodeAcabado(Id),
    FOREIGN KEY (IdTipodeHerraje)        REFERENCES TipodeHerraje(Id),
    FOREIGN KEY (IdTipodeAlmacenamiento) REFERENCES TipodeAlmacenamiento(Id)
);

CREATE TABLE ProductoImagenes(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdProducto   INT NOT NULL,
    URLImagen    VARCHAR(500) NOT NULL,
    Orden        TINYINT NOT NULL DEFAULT 1,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdProducto) REFERENCES Producto(Id) ON DELETE CASCADE
);

CREATE INDEX idx_producto_imagenes ON ProductoImagenes (IdProducto);

CREATE TABLE ProductoMaderas(
    Id                INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdProducto        INT NOT NULL,
    IdMadera          INT NOT NULL,
    CantidadNecesaria DECIMAL(10,2) NOT NULL,
    CostoUnitario     DECIMAL(10,2),
    CostoTotal        DECIMAL(10,2),
    Observaciones     VARCHAR(200),
    FechaBorrado      DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdProducto) REFERENCES Producto(Id) ON DELETE CASCADE,
    FOREIGN KEY (IdMadera)   REFERENCES Maderas(Id)
);

-- [IMPORTANTE-5] Eliminada la columna IdTipodomicilio de Clientes.
-- Era redundante porque Domicilio ya tiene su propio IdTipoDomicilio.
-- Mantener ambas generaba inconsistencias: si se actualizaba una no se
-- actualizaba la otra automáticamente.
CREATE TABLE Clientes(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    DNI          VARCHAR(300),
    Nombre       VARCHAR(30),
    Apellido     VARCHAR(30),
    Telefono     VARCHAR(20),
    IdLocalidad  INT,
    IdTipodeDni  INT,
    IdDomicilio  INT,
    -- CAMBIO: eliminado IdTipodomicilio (redundante con Domicilio.IdTipoDomicilio)
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdLocalidad) REFERENCES Localidad(Id),
    FOREIGN KEY (IdDomicilio) REFERENCES Domicilio(Id),
    FOREIGN KEY (IdTipodeDni) REFERENCES TipodeDni(Id)
);

CREATE TABLE Usuario(
    Id                  INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NombredeUsuario     VARCHAR(40),
    Contraseña          VARCHAR(300),
    CorreoElectronico   VARCHAR(50),
    Restablecer         INT NULL,
    Confirmado          INT NULL,
    Token               VARCHAR(700) NULL,
    IdTipodeUsuario     INT,
    IdTipodeRol         INT,
    IdCliente           INT,
    FechaBorrado        DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeUsuario) REFERENCES TipodeUsuario(Id),
    FOREIGN KEY (IdTipodeRol)     REFERENCES TipodeRol(Id),
    FOREIGN KEY (IdCliente)       REFERENCES Clientes(Id)
);

-- [MENOR-10] Carrito.Cantidad puede usarse como caché del total de items.
-- Debe mantenerse sincronizado con la suma de ProductoCarrito.Cantidad
-- mediante lógica de aplicación o triggers.
CREATE TABLE Carrito(
    Id        INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Cantidad  INT,  -- NOTA: valor derivado; sincronizar con ProductoCarrito.Cantidad
    IdCliente INT,
    FOREIGN KEY (IdCliente) REFERENCES Clientes(Id)
);

-- [IMPORTANTE-7] Agregada columna Cantidad INT NOT NULL DEFAULT 1.
-- Sin ella, si un cliente quería 3 unidades del mismo producto necesitaba
-- 3 filas idénticas, lo cual era ineficiente e incorrecto.
CREATE TABLE ProductoCarrito(
    Id         INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdProducto INT,
    IdCarrito  INT,
    Cantidad   INT NOT NULL DEFAULT 1,   -- CAMBIO: nuevo campo de cantidad
    FOREIGN KEY (IdProducto) REFERENCES Producto(Id),
    FOREIGN KEY (IdCarrito)  REFERENCES Carrito(Id)
);

CREATE TABLE Emisor(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre       VARCHAR(30),
    IdUsuario    INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(Id)
);

CREATE TABLE Caja(
    Id            INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    CantidadTotal DECIMAL(10,2),
    FechadeCaja   DATETIME,
    IdEmisor      INT,
    FechaBorrado  DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id)
);

CREATE TABLE EstadoBancarios(
    Id            INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NumeroCuenta  VARCHAR(30),
    MontoTotal    DECIMAL(10,2),
    TotalNeto     DECIMAL(10,2),
    MontosaPagar  DECIMAL(10,2),
    IdEmisor      INT,
    FechaBorrado  DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdEmisor) REFERENCES Emisor(Id)
);

CREATE TABLE DetallesdeBalance(
    Id                  INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdProducto          INT,
    IdUsuario           INT,
    IdStock             INT,
    IdCaja              INT,
    IdEstadoBancarios   INT,
    IdEmisor            INT,
    FechaBorrado        DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdProducto)        REFERENCES Producto(Id),
    FOREIGN KEY (IdUsuario)         REFERENCES Usuario(Id),
    FOREIGN KEY (IdStock)           REFERENCES Stock(Id),
    FOREIGN KEY (IdCaja)            REFERENCES Caja(Id),
    FOREIGN KEY (IdEstadoBancarios) REFERENCES EstadoBancarios(Id),
    FOREIGN KEY (IdEmisor)          REFERENCES Emisor(Id)
);

CREATE TABLE Diseño(
    Id              INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre          VARCHAR(30),
    FechadeCreacion DATETIME,
    UrlDoc          VARCHAR(650),
    IdTipodeDiseño  INT,
    IdUsuario       INT,
    IdEmisor        INT,
    FechaBorrado    DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeDiseño) REFERENCES TipodeDiseño(Id),
    FOREIGN KEY (IdUsuario)      REFERENCES Usuario(Id),
    FOREIGN KEY (IdEmisor)       REFERENCES Emisor(Id)
);

-- [MENOR-9] Corregido typo: "Descripcón" → "Descripcion"
CREATE TABLE SoportedeProduccion(
    Id               INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Descripcion      VARCHAR(300),   -- CAMBIO: era "Descripcón" (typo) y VARCHAR(30) muy corto
    CargadeTrabajo   INT,
    IdTipodeProducto INT,
    IdDiseño         INT,
    IdEmisor         INT,
    IdTipodeAcabado  INT,
    FechaBorrado     DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeProducto) REFERENCES TipodeProducto(Id),
    FOREIGN KEY (IdDiseño)         REFERENCES Diseño(Id),
    FOREIGN KEY (IdEmisor)         REFERENCES Emisor(Id),
    FOREIGN KEY (IdTipodeAcabado)  REFERENCES TipodeAcabado(Id)
);

CREATE TABLE DatosEmpresa(
    Id                INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre            VARCHAR(30),
    Apellido          VARCHAR(30),
    -- [MENOR-8] Cambiado de INT a VARCHAR(20) para soportar prefijos (+54, 0800, etc.)
    Telefono          VARCHAR(20),   -- CAMBIO: era INT
    Calle             VARCHAR(30),
    Numero            INT,
    CorreoElectronico VARCHAR(50),
    IdRazonSocial     INT,
    IdLocalidad       INT,
    FechaBorrado      DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdRazonSocial) REFERENCES RazonSocial(Id),
    FOREIGN KEY (IdLocalidad)   REFERENCES Localidad(Id)
);

CREATE TABLE FacturaCliente(
    Id                   INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NumeroFactura        INT,
    FechadeEmision       DATETIME,
    SubTotal             DECIMAL(10,2),
    Impuestos            DECIMAL(10,2),
    MontoTotal           DECIMAL(10,2),
    Interes              DECIMAL(10,2),
    Cuotas               INT,
    IdEmisor             INT,
    IdTipodePago         INT,
    IdEstadodePago       INT,
    IdEntidadBancaria    INT,
    IdDatosEmpresa       INT,
    IdAutorizaciondePago INT,
    IdClientes           INT,
    FechaBorrado         DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdEmisor)             REFERENCES Emisor(Id),
    FOREIGN KEY (IdTipodePago)         REFERENCES TipodePago(Id),
    FOREIGN KEY (IdEstadodePago)       REFERENCES EstadodePago(Id),
    FOREIGN KEY (IdEntidadBancaria)    REFERENCES EntidadBancaria(Id),
    FOREIGN KEY (IdDatosEmpresa)       REFERENCES DatosEmpresa(Id),
    FOREIGN KEY (IdAutorizaciondePago) REFERENCES AutorizaciondePago(Id),
    FOREIGN KEY (IdClientes)           REFERENCES Clientes(Id)
);

-- [IMPORTANTE-4] Agregado IdVenta INT con FK a Venta(Id).
-- La tabla existía pero era una isla: no tenía ninguna conexión con Venta
-- ni con ninguna otra tabla que la referenciara. Sin este FK los detalles
-- de una venta no podían asociarse a la venta correspondiente.
CREATE TABLE DetallesVenta(
    Id               INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdVenta          INT NOT NULL,   -- CAMBIO: nuevo campo que conecta con Venta
    Ancho            DECIMAL(10,2),
    Alto             DECIMAL(10,2),
    Largo            DECIMAL(10,2),
    IdTipodeProducto INT,
    IdTipodeMadera   INT,
    IdTipodeAcabado  INT,
    FechaBorrado     DATETIME NULL DEFAULT NULL,
    -- FK declarada aquí abajo, después de que Venta esté creada
    FOREIGN KEY (IdTipodeProducto) REFERENCES TipodeProducto(Id),
    FOREIGN KEY (IdTipodeMadera)   REFERENCES TipodeMadera(Id),
    FOREIGN KEY (IdTipodeAcabado)  REFERENCES TipodeAcabado(Id)
    -- NOTA: FK (IdVenta) → Venta(Id) se agrega con ALTER TABLE más abajo
    --       porque Venta aún no fue creada en este punto.
);

-- [CRÍTICO-2] [CRÍTICO-3] Eliminada columna Identrega de Venta.
-- Problema original: Entrega tenía FK → Venta(Id), y Venta.Identrega
-- apuntaba de vuelta a Entrega(Id). Esto creaba una referencia circular
-- que hacía imposible insertar registros en ninguna de las dos tablas
-- (cualquiera que se insertara primero necesitaba que la otra ya existiera).
-- Además, Identrega no tenía FOREIGN KEY declarada.
-- Solución: la relación se mantiene únicamente desde Entrega.IdVenta,
-- que es suficiente para navegar de una Venta a su Entrega.
CREATE TABLE Venta(
    Id              INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NumerodeVenta   INT,
    CantidadTotal   INT,
    IdCarrito       INT,
    IdFacturaCliente INT,
    -- CAMBIO: eliminado "Identrega INT" que causaba referencia circular
    --         y carecía de FOREIGN KEY declarada.
    FechaBorrado    DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdCarrito)        REFERENCES Carrito(Id),
    FOREIGN KEY (IdFacturaCliente) REFERENCES FacturaCliente(Id)
);

-- Ahora que Venta existe, se puede agregar la FK pendiente de DetallesVenta
ALTER TABLE DetallesVenta
    ADD CONSTRAINT fk_detallesventa_venta
    FOREIGN KEY (IdVenta) REFERENCES Venta(Id);

CREATE TABLE Pedido(
    Id           INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Estado       VARCHAR(30),
    Responsable  VARCHAR(50),
    IdVenta      INT,
    FechaBorrado DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdVenta) REFERENCES Venta(Id)
);

-- Entrega ya no necesita que Venta tenga Identrega.
-- La relación es unidireccional: desde Entrega hacia Venta.
CREATE TABLE Entrega(
    Id                  INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    FechadeEntrega      DATETIME,
    IdTipodeEntrega     INT,
    IdEstadosdeEntrega  INT,
    IdUsuario           INT,
    IdVenta             INT,   -- este FK es suficiente para vincular Entrega con Venta
    FechaBorrado        DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeEntrega)    REFERENCES TipodeEntrega(Id),
    FOREIGN KEY (IdEstadosdeEntrega) REFERENCES EstadosdeEntrega(Id),
    FOREIGN KEY (IdUsuario)          REFERENCES Usuario(Id),
    FOREIGN KEY (IdVenta)            REFERENCES Venta(Id)
);

-- [MENOR-8] Telefono cambiado de INT a VARCHAR(20)
-- INT trunca números que empiezan con 0 o que tienen prefijo internacional.
CREATE TABLE Proveedores(
    Id                INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Cuit              INT,
    Nombre            VARCHAR(30),
    Apellido          VARCHAR(30),
    Telefono          VARCHAR(20),   -- CAMBIO: era INT
    CorreoElectronico VARCHAR(50),
    Calle             VARCHAR(30),
    Numero            INT,
    IdRazonSocial     INT,
    IdLocalidad       INT,
    FechaBorrado      DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdRazonSocial) REFERENCES RazonSocial(Id),
    FOREIGN KEY (IdLocalidad)   REFERENCES Localidad(Id)
);

CREATE TABLE DetallesProveedor(
    Id                     INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdMaderas              INT,
    IdInsumosdeCarpinteria INT,
    FechaBorrado           DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdMaderas)              REFERENCES Maderas(Id),
    FOREIGN KEY (IdInsumosdeCarpinteria) REFERENCES InsumosdeCarpinteria(Id)
);

CREATE TABLE Remito(
    Id                    INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NumerodeRemito        INT,
    Cantidad              INT,
    PrecioUnitario        DECIMAL(10,2),
    Subtotal              DECIMAL(10,2),
    FechadeEmision        DATETIME,
    IdTipodeEncargoRemito INT,
    IdDetallesProveedor   INT,
    IdDatosEmpresa        INT,
    IdClientes            INT,
    IdEmisor              INT,
    IdProveedor           INT,
    FechaBorrado          DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdTipodeEncargoRemito) REFERENCES TipodeEncargoRemito(Id),
    FOREIGN KEY (IdDetallesProveedor)   REFERENCES DetallesProveedor(Id),
    FOREIGN KEY (IdDatosEmpresa)        REFERENCES DatosEmpresa(Id),
    FOREIGN KEY (IdClientes)            REFERENCES Clientes(Id),
    FOREIGN KEY (IdEmisor)              REFERENCES Emisor(Id),
    FOREIGN KEY (IdProveedor)           REFERENCES Proveedores(Id)
);

CREATE TABLE PedidosCliente(
    Id             INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    IdClientes     INT,
    IdTipodePedido INT,
    IdVenta        INT,
    FechaBorrado   DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (IdClientes)     REFERENCES Clientes(Id),
    FOREIGN KEY (IdTipodePedido) REFERENCES TipodePedido(Id),
    FOREIGN KEY (IdVenta)        REFERENCES Venta(Id)
);