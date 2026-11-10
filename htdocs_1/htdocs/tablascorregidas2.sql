-- =============================================================================
-- BASE DE DATOS: SanPlacido
-- Script actualizado - solo CREATE TABLES
-- =============================================================================

CREATE DATABASE IF NOT EXISTS sanplacido;
USE sanplacido;


-- -----------------------------------------------------------------------------
-- TABLAS DE CATÁLOGO / REFERENCIA (sin dependencias)
-- -----------------------------------------------------------------------------

CREATE TABLE `pais` (
  `Id`     INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodomicilio` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodepedido` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodeentrega` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `estadosdeentrega` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(200) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `tipodeencargoremito` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `estadodepago` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `entidadbancaria` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `autorizaciondepago` (
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

CREATE TABLE `tipodepago` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `razonsocial` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodeacabado` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodedni` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodeusuario` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(20) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipoderol` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(20) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `localidad` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodeproducto` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodemadera` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categoria` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodematerial` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodecorte` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodediseño` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodealmacenamiento` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `Descripcion`  VARCHAR(300) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipodeherraje` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `Descripcion`  VARCHAR(300) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------------------------
-- TABLAS PRINCIPALES
-- -----------------------------------------------------------------------------

CREATE TABLE `domicilio` (
  `Id`              INT(11) NOT NULL AUTO_INCREMENT,
  `Calle`           VARCHAR(50) DEFAULT NULL,
  `Numero`          INT(11) DEFAULT NULL,
  `Country`         VARCHAR(200) DEFAULT NULL,
  `Departamento`    INT(11) DEFAULT NULL,
  `Barrio`          VARCHAR(200) DEFAULT NULL,
  `IdTipoDomicilio` INT(11) DEFAULT NULL,
  `Piso`            INT(11) DEFAULT NULL,
  `numeroPiso`      INT(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipoDomicilio` (`IdTipoDomicilio`),
  CONSTRAINT `domicilio_ibfk_1` FOREIGN KEY (`IdTipoDomicilio`) REFERENCES `tipodomicilio` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `insumosdecarpinteria` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `PrecioUniatrio`   DECIMAL(10,2) DEFAULT NULL,
  `Cantidad`         INT(11) DEFAULT NULL,
  `Descripcion`      VARCHAR(20) DEFAULT NULL,
  `IdTipodeMaterial` INT(11) DEFAULT NULL,
  `IdTipodeCorte`    INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipodeMaterial` (`IdTipodeMaterial`),
  KEY `IdTipodeCorte` (`IdTipodeCorte`),
  CONSTRAINT `insumosdecarpinteria_ibfk_1` FOREIGN KEY (`IdTipodeMaterial`) REFERENCES `tipodematerial` (`Id`),
  CONSTRAINT `insumosdecarpinteria_ibfk_2` FOREIGN KEY (`IdTipodeCorte`) REFERENCES `tipodecorte` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `maderas` (
  `Id`             INT(11) NOT NULL AUTO_INCREMENT,
  `PrecioUnitario` DECIMAL(10,2) DEFAULT NULL,
  `CantidadStock`  INT(11) DEFAULT NULL,
  `Alto`           DECIMAL(10,2) DEFAULT NULL,
  `Largo`          DECIMAL(10,2) DEFAULT NULL,
  `Ancho`          DECIMAL(10,2) DEFAULT NULL,
  `IdTipodeMadera` INT(11) DEFAULT NULL,
  `FechaBorrado`   DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipodeMadera` (`IdTipodeMadera`),
  CONSTRAINT `maderas_ibfk_1` FOREIGN KEY (`IdTipodeMadera`) REFERENCES `tipodemadera` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `stock` (
  `Id`                     INT(11) NOT NULL AUTO_INCREMENT,
  `Fecha`                  DATETIME DEFAULT NULL,
  `CantitdadTotal`         INT(11) DEFAULT NULL,
  `MontoTotal`             DECIMAL(10,2) DEFAULT NULL,
  `IdMaderas`              INT(11) DEFAULT NULL,
  `IdInsumosdeCarpinteria` INT(11) DEFAULT NULL,
  `FechaBorrado`           DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdMaderas` (`IdMaderas`),
  KEY `IdInsumosdeCarpinteria` (`IdInsumosdeCarpinteria`),
  CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`IdMaderas`) REFERENCES `maderas` (`Id`),
  CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`IdInsumosdeCarpinteria`) REFERENCES `insumosdecarpinteria` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `producto` (
  `Id`                     INT(11) NOT NULL AUTO_INCREMENT,
  `NombredelProducto`      VARCHAR(100) DEFAULT NULL,
  `Descripcion`            TEXT DEFAULT NULL,
  `URLImagen`              VARCHAR(500) DEFAULT NULL,
  `Ancho`                  DECIMAL(10,2) DEFAULT NULL,
  `Largo`                  DECIMAL(10,2) DEFAULT NULL,
  `Alto`                   DECIMAL(10,2) DEFAULT NULL,
  `CostoTotalMateriales`   DECIMAL(10,2) DEFAULT 0.00,
  `TiempoFabricacionHoras` DECIMAL(5,2) DEFAULT 0.00,
  `PrecioVenta`            DECIMAL(10,2) DEFAULT NULL,
  `IdCategoria`            INT(11) DEFAULT NULL,
  `IdTipodeProducto`       INT(11) DEFAULT NULL,
  `IdTipodeDiseño`         INT(11) DEFAULT NULL,
  `IdTipodeAcabado`        INT(11) DEFAULT NULL,
  `IdTipodeHerraje`        INT(11) DEFAULT NULL,
  `IdTipodeAlmacenamiento` INT(11) DEFAULT NULL,
  `FechaCreacion`          DATETIME DEFAULT CURRENT_TIMESTAMP,
  `FechaBorrado`           DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdCategoria` (`IdCategoria`),
  KEY `IdTipodeProducto` (`IdTipodeProducto`),
  KEY `IdTipodeDiseño` (`IdTipodeDiseño`),
  KEY `IdTipodeAcabado` (`IdTipodeAcabado`),
  KEY `IdTipodeHerraje` (`IdTipodeHerraje`),
  KEY `IdTipodeAlmacenamiento` (`IdTipodeAlmacenamiento`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`IdCategoria`) REFERENCES `categoria` (`Id`),
  CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`IdTipodeProducto`) REFERENCES `tipodeproducto` (`Id`),
  CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`IdTipodeDiseño`) REFERENCES `tipodediseño` (`Id`),
  CONSTRAINT `producto_ibfk_4` FOREIGN KEY (`IdTipodeAcabado`) REFERENCES `tipodeacabado` (`Id`),
  CONSTRAINT `producto_ibfk_5` FOREIGN KEY (`IdTipodeHerraje`) REFERENCES `tipodeherraje` (`Id`),
  CONSTRAINT `producto_ibfk_6` FOREIGN KEY (`IdTipodeAlmacenamiento`) REFERENCES `tipodealmacenamiento` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `productoimagenes` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto`   INT(11) NOT NULL,
  `URLImagen`    VARCHAR(500) NOT NULL,
  `Orden`        TINYINT(4) NOT NULL DEFAULT 1,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `idx_producto_imagenes` (`IdProducto`),
  CONSTRAINT `productoimagenes_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `producto` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `productomaderas` (
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
  CONSTRAINT `productomaderas_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `producto` (`Id`) ON DELETE CASCADE,
  CONSTRAINT `productomaderas_ibfk_2` FOREIGN KEY (`IdMadera`) REFERENCES `maderas` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `clientes` (
  `Id`              INT(11) NOT NULL AUTO_INCREMENT,
  `DNI`             VARCHAR(300) DEFAULT NULL,
  `Nombre`          VARCHAR(30) DEFAULT NULL,
  `Apellido`        VARCHAR(30) DEFAULT NULL,
  `Telefono`        VARCHAR(20) DEFAULT NULL,
  `IdLocalidad`     INT(11) DEFAULT NULL,
  `IdTipodeDni`     INT(11) DEFAULT NULL,
  `IdDomicilio`     INT(11) DEFAULT NULL,
  `IdTipodomicilio` INT(11) DEFAULT NULL,
  `FechaBorrado`    DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdLocalidad` (`IdLocalidad`),
  KEY `IdDomicilio` (`IdDomicilio`),
  KEY `IdTipodeDni` (`IdTipodeDni`),
  KEY `IdTipodomicilio` (`IdTipodomicilio`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`IdLocalidad`) REFERENCES `localidad` (`Id`),
  CONSTRAINT `clientes_ibfk_2` FOREIGN KEY (`IdDomicilio`) REFERENCES `domicilio` (`Id`),
  CONSTRAINT `clientes_ibfk_3` FOREIGN KEY (`IdTipodeDni`) REFERENCES `tipodedni` (`Id`),
  CONSTRAINT `clientes_ibfk_4` FOREIGN KEY (`IdTipodomicilio`) REFERENCES `tipodomicilio` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `usuario` (
  `Id`                INT(11) NOT NULL AUTO_INCREMENT,
  `NombredeUsuario`   VARCHAR(40) DEFAULT NULL,
  `Contraseña`        VARCHAR(300) DEFAULT NULL,
  `CorreoElectronico` VARCHAR(50) DEFAULT NULL,
  `Restablecer`       INT(11) DEFAULT NULL,
  `Confirmado`        INT(11) DEFAULT NULL,
  `Token`             VARCHAR(700) DEFAULT NULL,
  `IdTipodeUsuario`   INT(11) DEFAULT NULL,
  `IdTipodeRol`       INT(11) DEFAULT NULL,
  `IdCliente`         INT(11) DEFAULT NULL,
  `FechaBorrado`      DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipodeUsuario` (`IdTipodeUsuario`),
  KEY `IdTipodeRol` (`IdTipodeRol`),
  KEY `IdCliente` (`IdCliente`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`IdTipodeUsuario`) REFERENCES `tipodeusuario` (`Id`),
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`IdTipodeRol`) REFERENCES `tipoderol` (`Id`),
  CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`IdCliente`) REFERENCES `clientes` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `carrito` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Cantidad`     INT(11) DEFAULT NULL,
  `IdCliente`    INT(11) DEFAULT NULL,
  `Estado`       TINYINT(4) NOT NULL DEFAULT 0 COMMENT '0=activo, 1=concretado',
  PRIMARY KEY (`Id`),
  KEY `IdCliente` (`IdCliente`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`IdCliente`) REFERENCES `clientes` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `productocarrito` (
  `Id`         INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto` INT(11) DEFAULT NULL,
  `IdCarrito`  INT(11) DEFAULT NULL,
  `Cantidad`   INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`Id`),
  KEY `IdProducto` (`IdProducto`),
  KEY `IdCarrito` (`IdCarrito`),
  CONSTRAINT `productocarrito_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `producto` (`Id`),
  CONSTRAINT `productocarrito_ibfk_2` FOREIGN KEY (`IdCarrito`) REFERENCES `carrito` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `emisor` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`       VARCHAR(30) DEFAULT NULL,
  `IdUsuario`    INT(11) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdUsuario` (`IdUsuario`),
  CONSTRAINT `emisor_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `caja` (
  `Id`            INT(11) NOT NULL AUTO_INCREMENT,
  `CantidadTotal` DECIMAL(10,2) DEFAULT NULL,
  `FechadeCaja`   DATETIME DEFAULT NULL,
  `IdEmisor`      INT(11) DEFAULT NULL,
  `FechaBorrado`  DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdEmisor` (`IdEmisor`),
  CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `emisor` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `estadobancarios` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `NumeroCuenta` VARCHAR(30) DEFAULT NULL,
  `MontoTotal`   DECIMAL(10,2) DEFAULT NULL,
  `TotalNeto`    DECIMAL(10,2) DEFAULT NULL,
  `MontosaPagar` DECIMAL(10,2) DEFAULT NULL,
  `IdEmisor`     INT(11) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdEmisor` (`IdEmisor`),
  CONSTRAINT `estadobancarios_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `emisor` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `detallesdebalance` (
  `Id`                INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto`        INT(11) DEFAULT NULL,
  `IdUsuario`         INT(11) DEFAULT NULL,
  `IdStock`           INT(11) DEFAULT NULL,
  `IdCaja`            INT(11) DEFAULT NULL,
  `IdEstadoBancarios` INT(11) DEFAULT NULL,
  `IdEmisor`          INT(11) DEFAULT NULL,
  `FechaBorrado`      DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdProducto` (`IdProducto`),
  KEY `IdUsuario` (`IdUsuario`),
  KEY `IdStock` (`IdStock`),
  KEY `IdCaja` (`IdCaja`),
  KEY `IdEstadoBancarios` (`IdEstadoBancarios`),
  KEY `IdEmisor` (`IdEmisor`),
  CONSTRAINT `detallesdebalance_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `producto` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_3` FOREIGN KEY (`IdStock`) REFERENCES `stock` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_4` FOREIGN KEY (`IdCaja`) REFERENCES `caja` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_5` FOREIGN KEY (`IdEstadoBancarios`) REFERENCES `estadobancarios` (`Id`),
  CONSTRAINT `detallesdebalance_ibfk_6` FOREIGN KEY (`IdEmisor`) REFERENCES `emisor` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `diseño` (
  `Id`              INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre`          VARCHAR(30) DEFAULT NULL,
  `FechadeCreación` DATETIME DEFAULT NULL,
  `UrlDoc`          VARCHAR(650) DEFAULT NULL,
  `IdTipodeDiseño`  INT(11) DEFAULT NULL,
  `IdUsuario`       INT(11) DEFAULT NULL,
  `IdEmisor`        INT(11) DEFAULT NULL,
  `FechaBorrado`    DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipodeDiseño` (`IdTipodeDiseño`),
  KEY `IdUsuario` (`IdUsuario`),
  KEY `IdEmisor` (`IdEmisor`),
  CONSTRAINT `diseño_ibfk_1` FOREIGN KEY (`IdTipodeDiseño`) REFERENCES `tipodediseño` (`Id`),
  CONSTRAINT `diseño_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`Id`),
  CONSTRAINT `diseño_ibfk_3` FOREIGN KEY (`IdEmisor`) REFERENCES `emisor` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `soportedeproduccion` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `Descripcón`       VARCHAR(30) DEFAULT NULL,
  `CargadeTrabajo`   INT(11) DEFAULT NULL,
  `IdTipodeProducto` INT(11) DEFAULT NULL,
  `IdDiseño`         INT(11) DEFAULT NULL,
  `IdEmisor`         INT(11) DEFAULT NULL,
  `IdTipodeAcabado`  INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipodeProducto` (`IdTipodeProducto`),
  KEY `IdDiseño` (`IdDiseño`),
  KEY `IdEmisor` (`IdEmisor`),
  KEY `IdTipodeAcabado` (`IdTipodeAcabado`),
  CONSTRAINT `soportedeproduccion_ibfk_1` FOREIGN KEY (`IdTipodeProducto`) REFERENCES `tipodeproducto` (`Id`),
  CONSTRAINT `soportedeproduccion_ibfk_2` FOREIGN KEY (`IdDiseño`) REFERENCES `diseño` (`Id`),
  CONSTRAINT `soportedeproduccion_ibfk_3` FOREIGN KEY (`IdEmisor`) REFERENCES `emisor` (`Id`),
  CONSTRAINT `soportedeproduccion_ibfk_4` FOREIGN KEY (`IdTipodeAcabado`) REFERENCES `tipodeacabado` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `datosempresa` (
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
  CONSTRAINT `datosempresa_ibfk_1` FOREIGN KEY (`IdRazonSocial`) REFERENCES `razonsocial` (`Id`),
  CONSTRAINT `datosempresa_ibfk_2` FOREIGN KEY (`IdLocalidad`) REFERENCES `localidad` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `facturacliente` (
  `Id`                   INT(11) NOT NULL AUTO_INCREMENT,
  `NumeroFactura`        INT(11) DEFAULT NULL,
  `FechadeEmision`       DATETIME DEFAULT NULL,
  `SubTotal`             DECIMAL(10,2) DEFAULT NULL,
  `Impuestos`            DECIMAL(10,2) DEFAULT NULL,
  `MontoTotal`           DECIMAL(10,2) DEFAULT NULL,
  `Interes`              DECIMAL(10,2) DEFAULT NULL,
  `Cuotas`               INT(11) DEFAULT NULL,
  `MarcaTarjeta`         VARCHAR(20) DEFAULT NULL,
  `IdEmisor`             INT(11) DEFAULT NULL,
  `IdTipodePago`         INT(11) DEFAULT NULL,
  `IdEstadodePago`       INT(11) DEFAULT NULL,
  `IdEntidadBancaria`    INT(11) DEFAULT NULL,
  `IdDatosEmpresa`       INT(11) DEFAULT NULL,
  `IdAutorizaciondePago` INT(11) DEFAULT NULL,
  `IdClientes`           INT(11) DEFAULT NULL,
  `FechaBorrado`         DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdEmisor` (`IdEmisor`),
  KEY `IdTipodePago` (`IdTipodePago`),
  KEY `IdEstadodePago` (`IdEstadodePago`),
  KEY `IdEntidadBancaria` (`IdEntidadBancaria`),
  KEY `IdDatosEmpresa` (`IdDatosEmpresa`),
  KEY `IdAutorizaciondePago` (`IdAutorizaciondePago`),
  KEY `IdClientes` (`IdClientes`),
  CONSTRAINT `facturacliente_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `emisor` (`Id`),
  CONSTRAINT `facturacliente_ibfk_2` FOREIGN KEY (`IdTipodePago`) REFERENCES `tipodepago` (`Id`),
  CONSTRAINT `facturacliente_ibfk_3` FOREIGN KEY (`IdEstadodePago`) REFERENCES `estadodepago` (`Id`),
  CONSTRAINT `facturacliente_ibfk_4` FOREIGN KEY (`IdEntidadBancaria`) REFERENCES `entidadbancaria` (`Id`),
  CONSTRAINT `facturacliente_ibfk_5` FOREIGN KEY (`IdDatosEmpresa`) REFERENCES `datosempresa` (`Id`),
  CONSTRAINT `facturacliente_ibfk_6` FOREIGN KEY (`IdAutorizaciondePago`) REFERENCES `autorizaciondepago` (`Id`),
  CONSTRAINT `facturacliente_ibfk_7` FOREIGN KEY (`IdClientes`) REFERENCES `clientes` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `venta` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `NumerodeVenta`    INT(11) DEFAULT NULL,
  `CantidadTotal`    INT(11) DEFAULT NULL,
  `IdCarrito`        INT(11) DEFAULT NULL,
  `IdFacturaCliente` INT(11) DEFAULT NULL,
  `Identrega`        INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdCarrito` (`IdCarrito`),
  KEY `IdFacturaCliente` (`IdFacturaCliente`),
  CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`IdCarrito`) REFERENCES `carrito` (`Id`),
  CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`IdFacturaCliente`) REFERENCES `facturacliente` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `detallesventa` (
  `Id`               INT(11) NOT NULL AUTO_INCREMENT,
  `IdVenta`          INT(11) NOT NULL,
  `Ancho`            DECIMAL(10,2) DEFAULT NULL,
  `Alto`             DECIMAL(10,2) DEFAULT NULL,
  `Largo`            DECIMAL(10,2) DEFAULT NULL,
  `IdTipodeProducto` INT(11) DEFAULT NULL,
  `IdTipodeMadera`   INT(11) DEFAULT NULL,
  `IdTipodeAcabado`  INT(11) DEFAULT NULL,
  `FechaBorrado`     DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipodeProducto` (`IdTipodeProducto`),
  KEY `IdTipodeMadera` (`IdTipodeMadera`),
  KEY `IdTipodeAcabado` (`IdTipodeAcabado`),
  KEY `fk_detallesventa_venta` (`IdVenta`),
  CONSTRAINT `detallesventa_ibfk_1` FOREIGN KEY (`IdTipodeProducto`) REFERENCES `tipodeproducto` (`Id`),
  CONSTRAINT `detallesventa_ibfk_2` FOREIGN KEY (`IdTipodeMadera`) REFERENCES `tipodemadera` (`Id`),
  CONSTRAINT `detallesventa_ibfk_3` FOREIGN KEY (`IdTipodeAcabado`) REFERENCES `tipodeacabado` (`Id`),
  CONSTRAINT `fk_detallesventa_venta` FOREIGN KEY (`IdVenta`) REFERENCES `venta` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pedido` (
  `Id`           INT(11) NOT NULL AUTO_INCREMENT,
  `Estado`       VARCHAR(30) DEFAULT NULL,
  `Responsable`  VARCHAR(50) DEFAULT NULL,
  `IdVenta`      INT(11) DEFAULT NULL,
  `FechaBorrado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdVenta` (`IdVenta`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`IdVenta`) REFERENCES `venta` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `entrega` (
  `Id`                 INT(11) NOT NULL AUTO_INCREMENT,
  `FechadeEntrega`     DATETIME DEFAULT NULL,
  `IdTipodeEntrega`    INT(11) DEFAULT NULL,
  `IdEstadosdeEntrega` INT(11) DEFAULT NULL,
  `IdUsuario`          INT(11) DEFAULT NULL,
  `IdVenta`            INT(11) DEFAULT NULL,
  `CodigoEntrega`      VARCHAR(20) DEFAULT NULL,
  `Direccion`          VARCHAR(200) DEFAULT NULL,
  `CostoEnvio`         DECIMAL(10,2) DEFAULT 0.00,
  `FechaBorrado`       DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipodeEntrega` (`IdTipodeEntrega`),
  KEY `IdEstadosdeEntrega` (`IdEstadosdeEntrega`),
  KEY `IdUsuario` (`IdUsuario`),
  KEY `IdVenta` (`IdVenta`),
  CONSTRAINT `entrega_ibfk_1` FOREIGN KEY (`IdTipodeEntrega`) REFERENCES `tipodeentrega` (`Id`),
  CONSTRAINT `entrega_ibfk_2` FOREIGN KEY (`IdEstadosdeEntrega`) REFERENCES `estadosdeentrega` (`Id`),
  CONSTRAINT `entrega_ibfk_3` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`Id`),
  CONSTRAINT `entrega_ibfk_4` FOREIGN KEY (`IdVenta`) REFERENCES `venta` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `proveedores` (
  `Id`                INT(11) NOT NULL AUTO_INCREMENT,
  `cuit`              INT(11) DEFAULT NULL,
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
  CONSTRAINT `proveedores_ibfk_1` FOREIGN KEY (`IdRazonSocial`) REFERENCES `razonsocial` (`Id`),
  CONSTRAINT `proveedores_ibfk_2` FOREIGN KEY (`IdLocalidad`) REFERENCES `localidad` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `detallesproveedor` (
  `Id`                     INT(11) NOT NULL AUTO_INCREMENT,
  `IdMaderas`              INT(11) DEFAULT NULL,
  `IdInsumosdeCarpinteria` INT(11) DEFAULT NULL,
  `FechaBorrado`           DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdMaderas` (`IdMaderas`),
  KEY `IdInsumosdeCarpinteria` (`IdInsumosdeCarpinteria`),
  CONSTRAINT `detallesproveedor_ibfk_1` FOREIGN KEY (`IdMaderas`) REFERENCES `maderas` (`Id`),
  CONSTRAINT `detallesproveedor_ibfk_2` FOREIGN KEY (`IdInsumosdeCarpinteria`) REFERENCES `insumosdecarpinteria` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `remito` (
  `Id`                    INT(11) NOT NULL AUTO_INCREMENT,
  `NumerodeRemito`        INT(11) DEFAULT NULL,
  `Cantidad`              INT(11) DEFAULT NULL,
  `PrecioUnitario`        DECIMAL(10,2) DEFAULT NULL,
  `Subtotal`              DECIMAL(10,2) DEFAULT NULL,
  `FechadeEmision`        DATETIME DEFAULT NULL,
  `IdTipodeEncargoRemito` INT(11) DEFAULT NULL,
  `IdDetallesProveedor`   INT(11) DEFAULT NULL,
  `IdDatosEmpresa`        INT(11) DEFAULT NULL,
  `IdClientes`            INT(11) DEFAULT NULL,
  `IdEmisor`              INT(11) DEFAULT NULL,
  `IdProveedor`           INT(11) DEFAULT NULL,
  `FechaBorrado`          DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdTipodeEncargoRemito` (`IdTipodeEncargoRemito`),
  KEY `IdDetallesProveedor` (`IdDetallesProveedor`),
  KEY `IdDatosEmpresa` (`IdDatosEmpresa`),
  KEY `IdClientes` (`IdClientes`),
  KEY `IdEmisor` (`IdEmisor`),
  KEY `IdProveedor` (`IdProveedor`),
  CONSTRAINT `remito_ibfk_1` FOREIGN KEY (`IdTipodeEncargoRemito`) REFERENCES `tipodeencargoremito` (`Id`),
  CONSTRAINT `remito_ibfk_2` FOREIGN KEY (`IdDetallesProveedor`) REFERENCES `detallesproveedor` (`Id`),
  CONSTRAINT `remito_ibfk_3` FOREIGN KEY (`IdDatosEmpresa`) REFERENCES `datosempresa` (`Id`),
  CONSTRAINT `remito_ibfk_4` FOREIGN KEY (`IdClientes`) REFERENCES `clientes` (`Id`),
  CONSTRAINT `remito_ibfk_5` FOREIGN KEY (`IdEmisor`) REFERENCES `emisor` (`Id`),
  CONSTRAINT `remito_ibfk_6` FOREIGN KEY (`IdProveedor`) REFERENCES `proveedores` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pedidoscliente` (
  `Id`             INT(11) NOT NULL AUTO_INCREMENT,
  `IdCLientes`     INT(11) DEFAULT NULL,
  `IdTipodePedido` INT(11) DEFAULT NULL,
  `IdVenta`        INT(11) DEFAULT NULL,
  `FechaBorrado`   DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdCLientes` (`IdCLientes`),
  KEY `IdTipodePedido` (`IdTipodePedido`),
  KEY `IdVenta` (`IdVenta`),
  CONSTRAINT `pedidoscliente_ibfk_1` FOREIGN KEY (`IdCLientes`) REFERENCES `clientes` (`Id`),
  CONSTRAINT `pedidoscliente_ibfk_2` FOREIGN KEY (`IdTipodePedido`) REFERENCES `tipodepedido` (`Id`),
  CONSTRAINT `pedidoscliente_ibfk_3` FOREIGN KEY (`IdVenta`) REFERENCES `venta` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ProductoInsumos` (
  `Id`                   INT(11) NOT NULL AUTO_INCREMENT,
  `IdProducto`           INT(11) NOT NULL,
  `IdInsumoCarpinteria`  INT(11) NOT NULL,
  `CantidadNecesaria`    DECIMAL(10,2) NOT NULL,
  `CostoUnitario`        DECIMAL(10,2) DEFAULT NULL,
  `CostoTotal`           DECIMAL(10,2) DEFAULT NULL,
  `Observaciones`        VARCHAR(200) DEFAULT NULL,
  `FechaBorrado`         DATETIME DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdProducto` (`IdProducto`),
  KEY `IdInsumoCarpinteria` (`IdInsumoCarpinteria`),
  CONSTRAINT `productoinsumos_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`) ON DELETE CASCADE,
  CONSTRAINT `productoinsumos_ibfk_2` FOREIGN KEY (`IdInsumoCarpinteria`) REFERENCES `InsumosdeCarpinteria` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =========================================================
-- SISTEMA DE RESEÑAS CON IA
-- =========================================================

CREATE TABLE IF NOT EXISTS Resena (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    IdCliente INT NOT NULL,
    IdProducto INT NOT NULL,
    IdPedido INT NULL,
    Puntuacion TINYINT NOT NULL CHECK (Puntuacion BETWEEN 1 AND 5),
    Titulo VARCHAR(150) NULL,
    ContenidoOriginal TEXT NOT NULL,
    ContenidoPublicado TEXT NULL,
    Estado ENUM('pendiente','aprobada','rechazada','oculta','en_revision') NOT NULL DEFAULT 'pendiente',
    FueEmbellecida TINYINT(1) NOT NULL DEFAULT 0,
    FechaCreacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FechaModeracion DATETIME NULL,
    FechaBorrado DATETIME NULL,
    CONSTRAINT fk_resena_cliente  FOREIGN KEY (IdCliente)  REFERENCES Clientes(Id),
    CONSTRAINT fk_resena_producto FOREIGN KEY (IdProducto) REFERENCES Productos(Id),
    INDEX idx_resena_prod_estado (IdProducto, Estado),
    INDEX idx_resena_cliente (IdCliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


use sanplacido;


CREATE TABLE IF NOT EXISTS ResenaAnalisisIA (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    IdResena INT NOT NULL,
    Sentimiento ENUM('positivo','neutro','negativo') NOT NULL,
    ScoreSentimiento DECIMAL(4,3) NOT NULL,
    ScoreToxicidad DECIMAL(4,3) NOT NULL,
    Categorias JSON NULL,
    Flags JSON NULL,
    ResumenCorto VARCHAR(200) NULL,
    ModeloUsado VARCHAR(50) NOT NULL,
    TokensConsumidos INT NOT NULL DEFAULT 0,
    FechaAnalisis DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ria_resena FOREIGN KEY (IdResena) REFERENCES Resena(Id) ON DELETE CASCADE,
    INDEX idx_ria_sent (Sentimiento),
    INDEX idx_ria_fecha (FechaAnalisis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ResenaHistorial (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    IdResena INT NOT NULL,
    Accion ENUM('creada','analizada','embellecida','aprobada','rechazada','oculta','respondida','editada') NOT NULL,
    IdUsuario INT NULL,
    Detalle JSON NULL,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_rh_resena FOREIGN KEY (IdResena) REFERENCES Resena(Id) ON DELETE CASCADE,
    INDEX idx_rh_accion (Accion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ResenaRespuesta (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    IdResena INT NOT NULL,
    IdUsuario INT NOT NULL,
    Contenido TEXT NOT NULL,
    GeneradaPorIA TINYINT(1) NOT NULL DEFAULT 0,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FechaBorrado DATETIME NULL,
    CONSTRAINT fk_rr_resena FOREIGN KEY (IdResena) REFERENCES Resena(Id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
