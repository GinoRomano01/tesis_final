-- =============================================================================
-- BASE DE DATOS: SanPlacido
-- =============================================================================

CREATE DATABASE IF NOT EXISTS sanplacido;
USE sanplacido;


-- -----------------------------------------------------------------------------
-- TABLAS DE CATÁLOGO / REFERENCIA
-- -----------------------------------------------------------------------------

CREATE TABLE `Pais` (
  `Id`     INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDomicilio` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDePedido` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeEntrega` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `EstadosDeEntrega` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(200) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeEncargoRemito` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `EstadoDePago` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `EntidadBancaria` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `AutorizacionDePago` (
  `Id`            INT(11) NOT NULL AUTO_INCREMENT,
  `TokendePago`   VARCHAR(255) DEFAULT NULL,
  `FechaBorrado`  DATETIME DEFAULT NULL,
  `PaymentIdMP`   VARCHAR(100) DEFAULT NULL COMMENT 'ID del pago en MercadoPago',
  `Status`        VARCHAR(30) DEFAULT NULL COMMENT 'approved, pending, rejected',
  `StatusDetail`  VARCHAR(100) DEFAULT NULL COMMENT 'motivo del rechazo/estado',
  `PaymentMethod` VARCHAR(50) DEFAULT NULL COMMENT 'visa, master, rapipago, etc.',
  `Cuotas`        INT(11) DEFAULT 1,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDePago` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `RazonSocial` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeAcabado` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeDni` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeUsuario` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(20) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeRol` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(20) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Localidad` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeProducto` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeMadera` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Categoria` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeMaterial` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeCorte` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeDiseño` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeAlmacenamiento` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `Descripcion`  VARCHAR(300) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `TipoDeHerraje` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `Descripcion`  VARCHAR(300) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- -----------------------------------------------------------------------------
-- TABLAS BACKUP (conservadas tal cual, solo renombradas con prefijo Bak)
-- -----------------------------------------------------------------------------

CREATE TABLE `BakInsumos` (
  `Id`               INT(11) NOT NULL DEFAULT 0,
  `PrecioUnitario`   DECIMAL(10,2) DEFAULT NULL,
  `Cantidad`         INT(11) DEFAULT NULL,
  `Descripcion`      VARCHAR(20) DEFAULT NULL,
  `IdTipoDeMaterial` INT(11) DEFAULT NULL,
  `IdTipoDeCorte`    INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `BakMaderas` (
  `Id`              INT(11) NOT NULL DEFAULT 0,
  `PrecioUnitario`  DECIMAL(10,2) DEFAULT NULL,
  `CantidadStock`   INT(11) DEFAULT NULL,
  `Alto`            DECIMAL(10,2) DEFAULT NULL,
  `Largo`           DECIMAL(10,2) DEFAULT NULL,
  `Ancho`           DECIMAL(10,2) DEFAULT NULL,
  `IdTipoDeMadera`  INT(11) DEFAULT NULL,
  `FechaBorrado`    DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `BakStock` (
  `Id`                      INT(11) NOT NULL DEFAULT 0,
  `Fecha`                   DATETIME DEFAULT NULL,
  `CantidadTotal`           INT(11) DEFAULT NULL,
  `MontoTotal`              DECIMAL(10,2) DEFAULT NULL,
  `IdMaderas`               INT(11) DEFAULT NULL,
  `IdInsumosDeCarpinteria`  INT(11) DEFAULT NULL,
  `FechaBorrado`            DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- -----------------------------------------------------------------------------
-- TABLAS PRINCIPALES
-- -----------------------------------------------------------------------------

CREATE TABLE `Domicilio` (
  `Id`              INT(11) NOT NULL AUTO_INCREMENT,
  `Calle`           VARCHAR(50) DEFAULT NULL,
  `Numero`          INT(11) DEFAULT NULL,
  `Country`         VARCHAR(200) DEFAULT NULL,
  `Departamento`    INT(11) DEFAULT NULL,
  `Barrio`          VARCHAR(200) DEFAULT NULL,
  `IdTipoDomicilio` INT(11) DEFAULT NULL,
  `Piso`            INT(11) DEFAULT NULL,
  `NumeroPiso`      INT(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDomicilio` (`IdTipoDomicilio`),
  CONSTRAINT `domicilio_ibfk_1` FOREIGN KEY (`IdTipoDomicilio`) REFERENCES `TipoDomicilio` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `InsumosDeCarpinteria` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `Descripcion`      VARCHAR(20) DEFAULT NULL,
  `IdTipoDeMaterial` INT(11) DEFAULT NULL,
  `IdTipoDeCorte`    INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDeMaterial` (`IdTipoDeMaterial`),
  KEY `IdTipoDeCorte` (`IdTipoDeCorte`),
  CONSTRAINT `insumos_ibfk_1` FOREIGN KEY (`IdTipoDeMaterial`) REFERENCES `TipoDeMaterial` (`Id`),
  CONSTRAINT `insumos_ibfk_2` FOREIGN KEY (`IdTipoDeCorte`) REFERENCES `TipoDeCorte` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Maderas` (
  `Id`             INT(11) NOT NULL AUTO_INCREMENT,
  `Alto`           DECIMAL(10,2) DEFAULT NULL,
  `Largo`          DECIMAL(10,2) DEFAULT NULL,
  `Ancho`          DECIMAL(10,2) DEFAULT NULL,
  `IdTipoDeMadera` INT(11) DEFAULT NULL,
  `FechaBorrado`   DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDeMadera` (`IdTipoDeMadera`),
  CONSTRAINT `maderas_ibfk_1` FOREIGN KEY (`IdTipoDeMadera`) REFERENCES `TipoDeMadera` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Stock` (
  `Id`             INT(11) NOT NULL AUTO_INCREMENT,
  `IdMaterial`     INT(11) NOT NULL COMMENT 'FK a Maderas.Id o InsumosDeCarpinteria.Id',
  `TipoMaterial`   TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=Madera, 2=Insumo',
  `Cantidad`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `PrecioUnitario` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `MontoTotal`     DECIMAL(10,2) GENERATED ALWAYS AS (`Cantidad` * `PrecioUnitario`) STORED COMMENT 'Calculado automáticamente',
  `FechaIngreso`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaBorrado`   DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `idx_stock_material` (`IdMaterial`, `TipoMaterial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Producto` (
  `Id`                     INT(11) NOT NULL AUTO_INCREMENT,
  `NombredelProducto`      VARCHAR(100) DEFAULT NULL,
  `Descripcion`            TEXT DEFAULT NULL,
  `URLImagen`              VARCHAR(500) DEFAULT NULL,
  `Ancho`                  DECIMAL(10,2) DEFAULT NULL,
  `Largo`                  DECIMAL(10,2) DEFAULT NULL,
  `Alto`                   DECIMAL(10,2) DEFAULT NULL,
  `CostoTotalMateriales`   DECIMAL(10,2) DEFAULT 0.00,
  `PorcentajeGanancia`     DECIMAL(5,2) NOT NULL DEFAULT 30.00 COMMENT '% ganancia sobre costo de materiales',
  `TiempoFabricacionHoras` DECIMAL(5,2) DEFAULT 0.00,
  `PrecioVenta`            DECIMAL(10,2) DEFAULT NULL,
  `IdCategoria`            INT(11) DEFAULT NULL,
  `IdTipoDeProducto`       INT(11) DEFAULT NULL,
  `IdTipoDeDiseño`         INT(11) DEFAULT NULL,
  `IdTipoDeAcabado`        INT(11) DEFAULT NULL,
  `IdTipoDeHerraje`        INT(11) DEFAULT NULL,
  `IdTipoDeAlmacenamiento` INT(11) DEFAULT NULL,
  `FechaCreacion`          DATETIME DEFAULT CURRENT_TIMESTAMP,
  `FechaBorrado`           DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdCategoria` (`IdCategoria`),
  KEY `IdTipoDeProducto` (`IdTipoDeProducto`),
  KEY `IdTipoDeDiseño` (`IdTipoDeDiseño`),
  KEY `IdTipoDeAcabado` (`IdTipoDeAcabado`),
  KEY `IdTipoDeHerraje` (`IdTipoDeHerraje`),
  KEY `IdTipoDeAlmacenamiento` (`IdTipoDeAlmacenamiento`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`IdCategoria`) REFERENCES `Categoria` (`Id`),
  CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`IdTipoDeProducto`) REFERENCES `TipoDeProducto` (`Id`),
  CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`IdTipoDeDiseño`) REFERENCES `TipoDeDiseño` (`Id`),
  CONSTRAINT `producto_ibfk_4` FOREIGN KEY (`IdTipoDeAcabado`) REFERENCES `TipoDeAcabado` (`Id`),
  CONSTRAINT `producto_ibfk_5` FOREIGN KEY (`IdTipoDeHerraje`) REFERENCES `TipoDeHerraje` (`Id`),
  CONSTRAINT `producto_ibfk_6` FOREIGN KEY (`IdTipoDeAlmacenamiento`) REFERENCES `TipoDeAlmacenamiento` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ProductoImagenes` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto`   INT(11) NOT NULL,
  `URLImagen`    VARCHAR(500) NOT NULL,
  `Orden`        TINYINT(4) NOT NULL DEFAULT 1,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `idx_producto_imagenes` (`IdProducto`),
  CONSTRAINT `productoimagenes_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ProductoMaderas` (
  `Id`                INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto`        INT(11) NOT NULL,
  `IdMadera`          INT(11) NOT NULL,
  `CantidadNecesaria` DECIMAL(10,2) NOT NULL,
  `CostoUnitario`     DECIMAL(10,2) DEFAULT NULL,
  `CostoTotal`        DECIMAL(10,2) DEFAULT NULL,
  `Observaciones`     VARCHAR(200) DEFAULT NULL,
  `FechaBorrado`      DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdProducto` (`IdProducto`),
  KEY `IdMadera` (`IdMadera`),
  CONSTRAINT `productomaderas_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`) ON DELETE CASCADE,
  CONSTRAINT `productomaderas_ibfk_2` FOREIGN KEY (`IdMadera`) REFERENCES `Maderas` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Clientes` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `DNI`              VARCHAR(300) DEFAULT NULL,
  `Nombre`           VARCHAR(30) DEFAULT NULL,
  `Apellido`         VARCHAR(30) DEFAULT NULL,
  `Telefono`         VARCHAR(20) DEFAULT NULL,
  `IdLocalidad`      INT(11) DEFAULT NULL,
  `IdTipoDeDni`      INT(11) DEFAULT NULL,
  `IdDomicilio`      INT(11) DEFAULT NULL,
  `IdTipoDomicilio`  INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdLocalidad` (`IdLocalidad`),
  KEY `IdDomicilio` (`IdDomicilio`),
  KEY `IdTipoDeDni` (`IdTipoDeDni`),
  KEY `IdTipoDomicilio` (`IdTipoDomicilio`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`IdLocalidad`) REFERENCES `Localidad` (`Id`),
  CONSTRAINT `clientes_ibfk_2` FOREIGN KEY (`IdDomicilio`) REFERENCES `Domicilio` (`Id`),
  CONSTRAINT `clientes_ibfk_3` FOREIGN KEY (`IdTipoDeDni`) REFERENCES `TipoDeDni` (`Id`),
  CONSTRAINT `clientes_ibfk_4` FOREIGN KEY (`IdTipoDomicilio`) REFERENCES `TipoDomicilio` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Usuario` (
  `Id`                INT(11) NOT NULL AUTO_INCREMENT,
  `NombreDeUsuario`   VARCHAR(40) DEFAULT NULL,
  `Contraseña`        VARCHAR(300) DEFAULT NULL,
  `CorreoElectronico` VARCHAR(50) DEFAULT NULL,
  `Restablecer`       INT(11) DEFAULT NULL,
  `Confirmado`        INT(11) DEFAULT NULL,
  `Token`             VARCHAR(700) DEFAULT NULL,
  `IdTipoDeUsuario`   INT(11) DEFAULT NULL,
  `IdTipoDeRol`       INT(11) DEFAULT NULL,
  `IdCliente`         INT(11) DEFAULT NULL,
  `FechaBorrado`      DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDeUsuario` (`IdTipoDeUsuario`),
  KEY `IdTipoDeRol` (`IdTipoDeRol`),
  KEY `IdCliente` (`IdCliente`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`IdTipoDeUsuario`) REFERENCES `TipoDeUsuario` (`Id`),
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`IdTipoDeRol`) REFERENCES `TipoDeRol` (`Id`),
  CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Carrito` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Cantidad`     INT(11) DEFAULT NULL,
  `IdCliente`    INT(11) DEFAULT NULL,
  `Estado`       TINYINT(4) NOT NULL DEFAULT 0 COMMENT '0=activo, 1=concretado',
  PRIMARY KEY (`Id`),
  KEY `IdCliente` (`IdCliente`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ProductoCarrito` (
  `Id`         INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto` INT(11) DEFAULT NULL,
  `IdCarrito`  INT(11) DEFAULT NULL,
  `Cantidad`   INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`Id`),
  KEY `IdProducto` (`IdProducto`),
  KEY `IdCarrito` (`IdCarrito`),
  CONSTRAINT `productocarrito_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`),
  CONSTRAINT `productocarrito_ibfk_2` FOREIGN KEY (`IdCarrito`) REFERENCES `Carrito` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Emisor` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `IdUsuario`    INT(11) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdUsuario` (`IdUsuario`),
  CONSTRAINT `emisor_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Caja` (
  `Id`            INT(11) NOT NULL AUTO_INCREMENT,
  `CantidadTotal` DECIMAL(10,2) DEFAULT NULL,
  `FechaDeCaja`   DATETIME DEFAULT NULL,
  `IdEmisor`      INT(11) DEFAULT NULL,
  `FechaBorrado`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdEmisor` (`IdEmisor`),
  CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `EstadoBancarios` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `NumeroCuenta` VARCHAR(30) DEFAULT NULL,
  `MontoTotal`   DECIMAL(10,2) DEFAULT NULL,
  `TotalNeto`    DECIMAL(10,2) DEFAULT NULL,
  `MontosAPagar` DECIMAL(10,2) DEFAULT NULL,
  `IdEmisor`     INT(11) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdEmisor` (`IdEmisor`),
  CONSTRAINT `estadobancarios_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `DetallesDeBalance` (
  `Id`                 INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto`         INT(11) DEFAULT NULL,
  `IdUsuario`          INT(11) DEFAULT NULL,
  `IdStock`            INT(11) DEFAULT NULL,
  `IdCaja`             INT(11) DEFAULT NULL,
  `IdEstadoBancarios`  INT(11) DEFAULT NULL,
  `IdEmisor`           INT(11) DEFAULT NULL,
  `FechaBorrado`       DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdProducto` (`IdProducto`),
  KEY `IdUsuario` (`IdUsuario`),
  KEY `IdStock` (`IdStock`),
  KEY `IdCaja` (`IdCaja`),
  KEY `IdEstadoBancarios` (`IdEstadoBancarios`),
  KEY `IdEmisor` (`IdEmisor`),
  CONSTRAINT `detallesdebalance_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_3` FOREIGN KEY (`IdStock`) REFERENCES `Stock` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_4` FOREIGN KEY (`IdCaja`) REFERENCES `Caja` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_5` FOREIGN KEY (`IdEstadoBancarios`) REFERENCES `EstadoBancarios` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_6` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Diseño` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`           VARCHAR(30) DEFAULT NULL,
  `FechaDeCreacion`  DATETIME DEFAULT NULL,
  `UrlDoc`           VARCHAR(650) DEFAULT NULL,
  `IdTipoDeDiseño`   INT(11) DEFAULT NULL,
  `IdUsuario`        INT(11) DEFAULT NULL,
  `IdEmisor`         INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDeDiseño` (`IdTipoDeDiseño`),
  KEY `IdUsuario` (`IdUsuario`),
  KEY `IdEmisor` (`IdEmisor`),
  CONSTRAINT `diseño_ibfk_1` FOREIGN KEY (`IdTipoDeDiseño`) REFERENCES `TipoDeDiseño` (`Id`),
  CONSTRAINT `diseño_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`),
  CONSTRAINT `diseño_ibfk_3` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `SoporteDeProduccion` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `Descripcion`      VARCHAR(30) DEFAULT NULL,
  `CargaDeTrabajo`   INT(11) DEFAULT NULL,
  `IdTipoDeProducto` INT(11) DEFAULT NULL,
  `IdDiseño`         INT(11) DEFAULT NULL,
  `IdEmisor`         INT(11) DEFAULT NULL,
  `IdTipoDeAcabado`  INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDeProducto` (`IdTipoDeProducto`),
  KEY `IdDiseño` (`IdDiseño`),
  KEY `IdEmisor` (`IdEmisor`),
  KEY `IdTipoDeAcabado` (`IdTipoDeAcabado`),
  CONSTRAINT `soporteproduccion_ibfk_1` FOREIGN KEY (`IdTipoDeProducto`) REFERENCES `TipoDeProducto` (`Id`),
  CONSTRAINT `soporteproduccion_ibfk_2` FOREIGN KEY (`IdDiseño`) REFERENCES `Diseño` (`Id`),
  CONSTRAINT `soporteproduccion_ibfk_3` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`),
  CONSTRAINT `soporteproduccion_ibfk_4` FOREIGN KEY (`IdTipoDeAcabado`) REFERENCES `TipoDeAcabado` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `DatosEmpresa` (
  `Id`                INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`            VARCHAR(30) DEFAULT NULL,
  `Apellido`          VARCHAR(30) DEFAULT NULL,
  `Telefono`          INT(11) DEFAULT NULL,
  `Calle`             VARCHAR(30) DEFAULT NULL,
  `Numero`            INT(11) DEFAULT NULL,
  `CorreoElectronico` VARCHAR(50) DEFAULT NULL,
  `IdRazonSocial`     INT(11) DEFAULT NULL,
  `IdLocalidad`       INT(11) DEFAULT NULL,
  `FechaBorrado`      DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdRazonSocial` (`IdRazonSocial`),
  KEY `IdLocalidad` (`IdLocalidad`),
  CONSTRAINT `datosempresa_ibfk_1` FOREIGN KEY (`IdRazonSocial`) REFERENCES `RazonSocial` (`Id`),
  CONSTRAINT `datosempresa_ibfk_2` FOREIGN KEY (`IdLocalidad`) REFERENCES `Localidad` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `FacturaCliente` (
  `Id`                    INT(11) NOT NULL AUTO_INCREMENT,
  `NumeroFactura`         INT(11) DEFAULT NULL,
  `FechaDeEmision`        DATETIME DEFAULT NULL,
  `SubTotal`              DECIMAL(10,2) DEFAULT NULL,
  `Impuestos`             DECIMAL(10,2) DEFAULT NULL,
  `MontoTotal`            DECIMAL(10,2) DEFAULT NULL,
  `Interes`               DECIMAL(10,2) DEFAULT NULL,
  `Cuotas`                INT(11) DEFAULT NULL,
  `MarcaTarjeta`          VARCHAR(20) DEFAULT NULL,
  `IdEmisor`              INT(11) DEFAULT NULL,
  `IdTipoDePago`          INT(11) DEFAULT NULL,
  `IdEstadoDePago`        INT(11) DEFAULT NULL,
  `IdEntidadBancaria`     INT(11) DEFAULT NULL,
  `IdDatosEmpresa`        INT(11) DEFAULT NULL,
  `IdAutorizacionDePago`  INT(11) DEFAULT NULL,
  `IdClientes`            INT(11) DEFAULT NULL,
  `FechaBorrado`          DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdEmisor` (`IdEmisor`),
  KEY `IdTipoDePago` (`IdTipoDePago`),
  KEY `IdEstadoDePago` (`IdEstadoDePago`),
  KEY `IdEntidadBancaria` (`IdEntidadBancaria`),
  KEY `IdDatosEmpresa` (`IdDatosEmpresa`),
  KEY `IdAutorizacionDePago` (`IdAutorizacionDePago`),
  KEY `IdClientes` (`IdClientes`),
  CONSTRAINT `facturacliente_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`),
  CONSTRAINT `facturacliente_ibfk_2` FOREIGN KEY (`IdTipoDePago`) REFERENCES `TipoDePago` (`Id`),
  CONSTRAINT `facturacliente_ibfk_3` FOREIGN KEY (`IdEstadoDePago`) REFERENCES `EstadoDePago` (`Id`),
  CONSTRAINT `facturacliente_ibfk_4` FOREIGN KEY (`IdEntidadBancaria`) REFERENCES `EntidadBancaria` (`Id`),
  CONSTRAINT `facturacliente_ibfk_5` FOREIGN KEY (`IdDatosEmpresa`) REFERENCES `DatosEmpresa` (`Id`),
  CONSTRAINT `facturacliente_ibfk_6` FOREIGN KEY (`IdAutorizacionDePago`) REFERENCES `AutorizacionDePago` (`Id`),
  CONSTRAINT `facturacliente_ibfk_7` FOREIGN KEY (`IdClientes`) REFERENCES `Clientes` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Venta` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `NumerodeVenta`    INT(11) DEFAULT NULL,
  `CantidadTotal`    INT(11) DEFAULT NULL,
  `IdCarrito`        INT(11) DEFAULT NULL,
  `IdFacturaCliente` INT(11) DEFAULT NULL,
  `IdEntrega`        INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdCarrito` (`IdCarrito`),
  KEY `IdFacturaCliente` (`IdFacturaCliente`),
  CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`IdCarrito`) REFERENCES `Carrito` (`Id`),
  CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`IdFacturaCliente`) REFERENCES `FacturaCliente` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `DetallesVenta` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `IdVenta`          INT(11) NOT NULL,
  `Ancho`            DECIMAL(10,2) DEFAULT NULL,
  `Alto`             DECIMAL(10,2) DEFAULT NULL,
  `Largo`            DECIMAL(10,2) DEFAULT NULL,
  `IdTipoDeProducto` INT(11) DEFAULT NULL,
  `IdTipoDeMadera`   INT(11) DEFAULT NULL,
  `IdTipoDeAcabado`  INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDeProducto` (`IdTipoDeProducto`),
  KEY `IdTipoDeMadera` (`IdTipoDeMadera`),
  KEY `IdTipoDeAcabado` (`IdTipoDeAcabado`),
  KEY `fk_detallesventa_venta` (`IdVenta`),
  CONSTRAINT `detallesventa_ibfk_1` FOREIGN KEY (`IdTipoDeProducto`) REFERENCES `TipoDeProducto` (`Id`),
  CONSTRAINT `detallesventa_ibfk_2` FOREIGN KEY (`IdTipoDeMadera`) REFERENCES `TipoDeMadera` (`Id`),
  CONSTRAINT `detallesventa_ibfk_3` FOREIGN KEY (`IdTipoDeAcabado`) REFERENCES `TipoDeAcabado` (`Id`),
  CONSTRAINT `fk_detallesventa_venta` FOREIGN KEY (`IdVenta`) REFERENCES `Venta` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Pedido` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Estado`       VARCHAR(30) DEFAULT NULL,
  `Responsable`  VARCHAR(50) DEFAULT NULL,
  `IdVenta`      INT(11) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdVenta` (`IdVenta`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`IdVenta`) REFERENCES `Venta` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Entrega` (
  `Id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `FechaDeEntrega`      DATETIME DEFAULT NULL,
  `IdTipoDeEntrega`     INT(11) DEFAULT NULL,
  `IdEstadosDeEntrega`  INT(11) DEFAULT NULL,
  `IdUsuario`           INT(11) DEFAULT NULL,
  `IdVenta`             INT(11) DEFAULT NULL,
  `CodigoEntrega`       VARCHAR(20) DEFAULT NULL,
  `Direccion`           VARCHAR(200) DEFAULT NULL,
  `CostoEnvio`          DECIMAL(10,2) DEFAULT 0.00,
  `FechaBorrado`        DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDeEntrega` (`IdTipoDeEntrega`),
  KEY `IdEstadosDeEntrega` (`IdEstadosDeEntrega`),
  KEY `IdUsuario` (`IdUsuario`),
  KEY `IdVenta` (`IdVenta`),
  CONSTRAINT `entrega_ibfk_1` FOREIGN KEY (`IdTipoDeEntrega`) REFERENCES `TipoDeEntrega` (`Id`),
  CONSTRAINT `entrega_ibfk_2` FOREIGN KEY (`IdEstadosDeEntrega`) REFERENCES `EstadosDeEntrega` (`Id`),
  CONSTRAINT `entrega_ibfk_3` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`),
  CONSTRAINT `entrega_ibfk_4` FOREIGN KEY (`IdVenta`) REFERENCES `Venta` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Proveedores` (
  `Id`                INT(11) NOT NULL AUTO_INCREMENT,
  `Cuit`              INT(11) DEFAULT NULL,
  `Nombre`            VARCHAR(30) DEFAULT NULL,
  `Apellido`          VARCHAR(30) DEFAULT NULL,
  `Telefono`          INT(11) DEFAULT NULL,
  `CorreoElectronico` VARCHAR(50) DEFAULT NULL,
  `Calle`             VARCHAR(30) DEFAULT NULL,
  `Numero`            INT(11) DEFAULT NULL,
  `IdRazonSocial`     INT(11) DEFAULT NULL,
  `IdLocalidad`       INT(11) DEFAULT NULL,
  `FechaBorrado`      DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdRazonSocial` (`IdRazonSocial`),
  KEY `IdLocalidad` (`IdLocalidad`),
  CONSTRAINT `proveedores_ibfk_1` FOREIGN KEY (`IdRazonSocial`) REFERENCES `RazonSocial` (`Id`),
  CONSTRAINT `proveedores_ibfk_2` FOREIGN KEY (`IdLocalidad`) REFERENCES `Localidad` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `DetallesProveedor` (
  `Id`                      INT(11) NOT NULL AUTO_INCREMENT,
  `IdMaderas`               INT(11) DEFAULT NULL,
  `IdInsumosDeCarpinteria`  INT(11) DEFAULT NULL,
  `FechaBorrado`            DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdMaderas` (`IdMaderas`),
  KEY `IdInsumosDeCarpinteria` (`IdInsumosDeCarpinteria`),
  CONSTRAINT `detallesproveedor_ibfk_1` FOREIGN KEY (`IdMaderas`) REFERENCES `Maderas` (`Id`),
  CONSTRAINT `detallesproveedor_ibfk_2` FOREIGN KEY (`IdInsumosDeCarpinteria`) REFERENCES `InsumosDeCarpinteria` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Remito` (
  `Id`                     INT(11) NOT NULL AUTO_INCREMENT,
  `NumeroDeRemito`         INT(11) DEFAULT NULL,
  `Cantidad`               INT(11) DEFAULT NULL,
  `PrecioUnitario`         DECIMAL(10,2) DEFAULT NULL,
  `Subtotal`               DECIMAL(10,2) DEFAULT NULL,
  `FechaDeEmision`         DATETIME DEFAULT NULL,
  `IdTipoDeEncargoRemito`  INT(11) DEFAULT NULL,
  `IdDetallesProveedor`    INT(11) DEFAULT NULL,
  `IdDatosEmpresa`         INT(11) DEFAULT NULL,
  `IdClientes`             INT(11) DEFAULT NULL,
  `IdEmisor`               INT(11) DEFAULT NULL,
  `IdProveedor`            INT(11) DEFAULT NULL,
  `FechaBorrado`           DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDeEncargoRemito` (`IdTipoDeEncargoRemito`),
  KEY `IdDetallesProveedor` (`IdDetallesProveedor`),
  KEY `IdDatosEmpresa` (`IdDatosEmpresa`),
  KEY `IdClientes` (`IdClientes`),
  KEY `IdEmisor` (`IdEmisor`),
  KEY `IdProveedor` (`IdProveedor`),
  CONSTRAINT `remito_ibfk_1` FOREIGN KEY (`IdTipoDeEncargoRemito`) REFERENCES `TipoDeEncargoRemito` (`Id`),
  CONSTRAINT `remito_ibfk_2` FOREIGN KEY (`IdDetallesProveedor`) REFERENCES `DetallesProveedor` (`Id`),
  CONSTRAINT `remito_ibfk_3` FOREIGN KEY (`IdDatosEmpresa`) REFERENCES `DatosEmpresa` (`Id`),
  CONSTRAINT `remito_ibfk_4` FOREIGN KEY (`IdClientes`) REFERENCES `Clientes` (`Id`),
  CONSTRAINT `remito_ibfk_5` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`),
  CONSTRAINT `remito_ibfk_6` FOREIGN KEY (`IdProveedor`) REFERENCES `Proveedores` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `PedidosCliente` (
  `Id`             INT(11) NOT NULL AUTO_INCREMENT,
  `IdClientes`     INT(11) DEFAULT NULL,
  `IdTipoDePedido` INT(11) DEFAULT NULL,
  `IdVenta`        INT(11) DEFAULT NULL,
  `FechaBorrado`   DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdClientes` (`IdClientes`),
  KEY `IdTipoDePedido` (`IdTipoDePedido`),
  KEY `IdVenta` (`IdVenta`),
  CONSTRAINT `pedidoscliente_ibfk_1` FOREIGN KEY (`IdClientes`) REFERENCES `Clientes` (`Id`),
  CONSTRAINT `pedidoscliente_ibfk_2` FOREIGN KEY (`IdTipoDePedido`) REFERENCES `TipoDePedido` (`Id`),
  CONSTRAINT `pedidoscliente_ibfk_3` FOREIGN KEY (`IdVenta`) REFERENCES `Venta` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ProductoInsumos` (
  `Id`                     INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto`             INT(11) NOT NULL,
  `IdInsumoCarpinteria`    INT(11) NOT NULL,
  `CantidadNecesaria`      DECIMAL(10,2) NOT NULL,
  `CostoUnitario`          DECIMAL(10,2) DEFAULT NULL,
  `CostoTotal`             DECIMAL(10,2) DEFAULT NULL,
  `Observaciones`          VARCHAR(200) DEFAULT NULL,
  `FechaBorrado`           DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdProducto` (`IdProducto`),
  KEY `IdInsumoCarpinteria` (`IdInsumoCarpinteria`),
  CONSTRAINT `productoinsumos_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`) ON DELETE CASCADE,
  CONSTRAINT `productoinsumos_ibfk_2` FOREIGN KEY (`IdInsumoCarpinteria`) REFERENCES `InsumosDeCarpinteria` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- -----------------------------------------------------------------------------
-- VIEWs
-- -----------------------------------------------------------------------------

CREATE VIEW `VistaCostoProducto` AS
SELECT
  p.Id                                                              AS IdProducto,
  p.NombredelProducto                                               AS NombredelProducto,
  p.URLImagen                                                       AS URLImagen,
  (SELECT COUNT(0) FROM ProductoImagenes pi2
   WHERE pi2.IdProducto = p.Id AND pi2.FechaBorrado IS NULL)        AS CantidadImagenesExtra,
  COALESCE(SUM(pm.CostoTotal), 0)                                   AS CostoMaderas,
  COALESCE(SUM(pins.CostoTotal), 0)                                 AS CostoInsumos,
  COALESCE(SUM(pm.CostoTotal), 0) + COALESCE(SUM(pins.CostoTotal), 0) AS CostoTotal,
  p.PrecioVenta                                                     AS PrecioVenta,
  p.PrecioVenta - (COALESCE(SUM(pm.CostoTotal), 0) + COALESCE(SUM(pins.CostoTotal), 0)) AS Ganancia,
  ROUND(
    (p.PrecioVenta - (COALESCE(SUM(pm.CostoTotal), 0) + COALESCE(SUM(pins.CostoTotal), 0)))
    / NULLIF(p.PrecioVenta, 0) * 100, 2
  )                                                                 AS MargenGanancia
FROM Producto p
LEFT JOIN ProductoMaderas pm   ON p.Id = pm.IdProducto  AND pm.FechaBorrado  IS NULL
LEFT JOIN ProductoInsumos pins ON p.Id = pins.IdProducto AND pins.FechaBorrado IS NULL
WHERE p.FechaBorrado IS NULL
GROUP BY p.Id;


-- =============================================================================
-- STORED PROCEDURES
-- =============================================================================

DELIMITER $$

CREATE PROCEDURE `ActualizarCostoProducto`(IN p_IdProducto INT)
BEGIN
  DECLARE v_CostoTotal DECIMAL(10,2);

  SELECT
    COALESCE(SUM(pm.CostoTotal), 0) + COALESCE(SUM(pi.CostoTotal), 0)
  INTO v_CostoTotal
  FROM Producto p
  LEFT JOIN ProductoMaderas  pm ON p.Id = pm.IdProducto  AND pm.FechaBorrado  IS NULL
  LEFT JOIN ProductoInsumos  pi ON p.Id = pi.IdProducto  AND pi.FechaBorrado  IS NULL
  WHERE p.Id = p_IdProducto;

  UPDATE Producto
  SET CostoTotalMateriales = v_CostoTotal
  WHERE Id = p_IdProducto;
END$$


CREATE PROCEDURE `sp_SincronizarPedidos`()
BEGIN
  INSERT INTO Pedido (Estado, Responsable, IdVenta)
  SELECT
    'Pendiente',
    '',
    v.Id
  FROM Venta v
  JOIN FacturaCliente fc ON fc.Id = v.IdFacturaCliente
  JOIN EstadoDePago   ep ON ep.Id = fc.IdEstadoDePago
                        AND ep.Nombre = 'Aprobado'
  LEFT JOIN Pedido    p  ON p.IdVenta = v.Id
                        AND p.FechaBorrado IS NULL
  WHERE v.FechaBorrado IS NULL
    AND p.Id IS NULL;
END$$

DELIMITER ;

CREATE TABLE `notificaciones` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `IdUsuario` int(11) DEFAULT NULL COMMENT 'FK usuario.Id — notificación para un empleado/admin puntual',
  `IdCliente` int(11) DEFAULT NULL COMMENT 'FK clientes.Id — notificación para un cliente puntual',
  `Tipo` varchar(40) NOT NULL COMMENT 'venta_nueva|resena_pendiente|stock_bajo|entrega_actualizada|pago_rechazado|sistema',
  `Titulo` varchar(150) NOT NULL,
  `Contenido` varchar(500) DEFAULT NULL,
  `UrlDestino` varchar(300) DEFAULT NULL COMMENT 'Ruta interna a la que redirige al hacer clic (ej: venta/detalle/12)',
  `Icono` varchar(40) NOT NULL DEFAULT 'fa-bell' COMMENT 'Clase de ícono FontAwesome',
  `Leida` tinyint(1) NOT NULL DEFAULT 0,
  `FechaLeida` datetime DEFAULT NULL,
  `FechaCreacion` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaBorrado` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `idx_notif_usuario` (`IdUsuario`,`Leida`),
  KEY `idx_notif_cliente` (`IdCliente`,`Leida`),
  KEY `idx_notif_fecha` (`FechaCreacion`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`Id`) ON DELETE CASCADE,
  CONSTRAINT `notificaciones_ibfk_2` FOREIGN KEY (`IdCliente`) REFERENCES `clientes` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;