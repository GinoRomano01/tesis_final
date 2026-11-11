SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `AutorizaciondePago`
--

CREATE TABLE `AutorizaciondePago` (
  `Id` int(11) NOT NULL,
  `TokendePago` varchar(255) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL,
  `PaymentIdMP` varchar(100) DEFAULT NULL COMMENT 'ID del pago en MercadoPago',
  `Status` varchar(30) DEFAULT NULL COMMENT 'approved, pending, rejected',
  `StatusDetail` varchar(100) DEFAULT NULL COMMENT 'motivo del rechazo/estado',
  `PaymentMethod` varchar(50) DEFAULT NULL COMMENT 'visa, master, rapipago, etc.',
  `Cuotas` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Busquedas`
--

CREATE TABLE `Busquedas` (
  `Id` int(11) NOT NULL,
  `TerminoBuscado` varchar(300) NOT NULL COMMENT 'Texto ingresado por el usuario',
  `CantidadResultados` int(11) DEFAULT NULL COMMENT 'Cuántos productos devolvió',
  `IdUsuario` int(11) DEFAULT NULL,
  `IdCliente` int(11) DEFAULT NULL,
  `SesionId` varchar(100) DEFAULT NULL,
  `FechaRegistro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Caja`
--

CREATE TABLE `Caja` (
  `Id` int(11) NOT NULL,
  `CantidadTotal` decimal(10,2) DEFAULT NULL,
  `FechadeCaja` datetime DEFAULT NULL,
  `IdEmisor` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Carrito`
--

CREATE TABLE `Carrito` (
  `Id` int(11) NOT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `IdCliente` int(11) DEFAULT NULL,
  `Estado` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=activo, 1=concretado',
  `FechaCreacion` datetime DEFAULT NULL,
  `FechaNotificadoAbandono` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Categoria`
--

CREATE TABLE `Categoria` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Clientes`
--

CREATE TABLE `Clientes` (
  `Id` int(11) NOT NULL,
  `DNI` varchar(300) DEFAULT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `Apellido` varchar(30) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `IdLocalidad` int(11) DEFAULT NULL,
  `IdTipodeDni` int(11) DEFAULT NULL,
  `IdDomicilio` int(11) DEFAULT NULL,
  `IdTipodomicilio` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ColorAcabado`
--

CREATE TABLE `ColorAcabado` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `ColorHex` varchar(7) NOT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Comprobante`
--

CREATE TABLE `Comprobante` (
  `Id` int(11) NOT NULL,
  `NumeroComprobante` int(11) DEFAULT NULL,
  `FechadeEmision` datetime DEFAULT NULL,
  `SubTotal` decimal(10,2) DEFAULT NULL,
  `Impuestos` decimal(10,2) DEFAULT NULL,
  `MontoTotal` decimal(10,2) DEFAULT NULL,
  `Interes` decimal(10,2) DEFAULT NULL,
  `Cuotas` int(11) DEFAULT NULL,
  `MarcaTarjeta` varchar(20) DEFAULT NULL,
  `IdEmisor` int(11) DEFAULT NULL,
  `IdTipodePago` int(11) DEFAULT NULL,
  `IdEstadodePago` int(11) DEFAULT NULL,
  `IdEntidadBancaria` int(11) DEFAULT NULL,
  `IdDatosEmpresa` int(11) DEFAULT NULL,
  `IdAutorizaciondePago` int(11) DEFAULT NULL,
  `IdClientes` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ConfiguracionGlobal`
--

CREATE TABLE `ConfiguracionGlobal` (
  `Id` int(11) NOT NULL,
  `PrecioPorHora` decimal(10,2) NOT NULL DEFAULT 0.00,
  `FechaActualizacion` datetime DEFAULT NULL,
  `IdUsuarioActualizo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DatosEmpresa`
--

CREATE TABLE `DatosEmpresa` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `Apellido` varchar(30) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL,
  `Calle` varchar(30) DEFAULT NULL,
  `Numero` int(11) DEFAULT NULL,
  `CorreoElectronico` varchar(50) DEFAULT NULL,
  `IdRazonSocial` int(11) DEFAULT NULL,
  `IdLocalidad` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetallesDeBalance`
--

CREATE TABLE `DetallesDeBalance` (
  `Id` int(11) NOT NULL,
  `IdProducto` int(11) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `IdStock` int(11) DEFAULT NULL,
  `IdCaja` int(11) DEFAULT NULL,
  `IdEstadoBancarios` int(11) DEFAULT NULL,
  `IdEmisor` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetallesProveedor`
--

CREATE TABLE `DetallesProveedor` (
  `Id` int(11) NOT NULL,
  `IdMaderas` int(11) DEFAULT NULL,
  `IdInsumosdeCarpinteria` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetallesVenta`
--

CREATE TABLE `DetallesVenta` (
  `Id` int(11) NOT NULL,
  `IdVenta` int(11) NOT NULL,
  `Ancho` decimal(10,2) DEFAULT NULL,
  `Alto` decimal(10,2) DEFAULT NULL,
  `Largo` decimal(10,2) DEFAULT NULL,
  `IdTipodeProducto` int(11) DEFAULT NULL,
  `IdTipodeMadera` int(11) DEFAULT NULL,
  `IdTipodeAcabado` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Diseño`
--

CREATE TABLE `Diseño` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechadeCreación` datetime DEFAULT NULL,
  `UrlDoc` varchar(650) DEFAULT NULL,
  `IdTipodeDiseño` int(11) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `IdEmisor` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Domicilio`
--

CREATE TABLE `Domicilio` (
  `Id` int(11) NOT NULL,
  `Calle` varchar(50) DEFAULT NULL,
  `Numero` int(11) DEFAULT NULL,
  `Country` varchar(200) DEFAULT NULL,
  `Departamento` int(11) DEFAULT NULL,
  `Barrio` varchar(200) DEFAULT NULL,
  `IdTipoDomicilio` int(11) DEFAULT NULL,
  `Piso` int(11) DEFAULT NULL,
  `numeroPiso` int(11) DEFAULT NULL,
  `CodigoPostal` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Emisor`
--

CREATE TABLE `Emisor` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EntidadBancaria`
--

CREATE TABLE `EntidadBancaria` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Entrega`
--

CREATE TABLE `Entrega` (
  `Id` int(11) NOT NULL,
  `FechadeEntrega` datetime DEFAULT NULL,
  `IdTipodeEntrega` int(11) DEFAULT NULL,
  `IdEstadosdeEntrega` int(11) DEFAULT NULL,
  `IdRuta` int(11) DEFAULT NULL,
  `OrdenEnRuta` int(11) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `IdVenta` int(11) DEFAULT NULL,
  `CodigoEntrega` varchar(20) DEFAULT NULL,
  `Direccion` varchar(200) DEFAULT NULL,
  `CostoEnvio` decimal(10,2) DEFAULT 0.00,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EstadoBancarios`
--

CREATE TABLE `EstadoBancarios` (
  `Id` int(11) NOT NULL,
  `NumeroCuenta` varchar(30) DEFAULT NULL,
  `MontoTotal` decimal(10,2) DEFAULT NULL,
  `TotalNeto` decimal(10,2) DEFAULT NULL,
  `MontosaPagar` decimal(10,2) DEFAULT NULL,
  `IdEmisor` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EstadodePago`
--

CREATE TABLE `EstadodePago` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EstadosdeEntrega`
--

CREATE TABLE `EstadosdeEntrega` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(200) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EventosDeUsuario`
--

CREATE TABLE `EventosDeUsuario` (
  `Id` int(11) NOT NULL,
  `TipoEvento` varchar(50) NOT NULL COMMENT 'page_view | clic | registro | login | logout | add_carrito | busqueda | checkout_paso | compra',
  `Modulo` varchar(50) DEFAULT NULL COMMENT 'home | producto | carrito | checkout | login | admin | stock',
  `ElementoId` varchar(100) DEFAULT NULL COMMENT 'ID del elemento clickeado (p.ej. id del producto)',
  `ElementoTipo` varchar(50) DEFAULT NULL COMMENT 'producto | categoria | boton | enlace | formulario',
  `ValorExtra` varchar(500) DEFAULT NULL COMMENT 'JSON libre: {precio, query, paso, etc.}',
  `IdUsuario` int(11) DEFAULT NULL COMMENT 'FK Usuario.Id',
  `IdCliente` int(11) DEFAULT NULL COMMENT 'FK Clientes.Id',
  `SesionId` varchar(100) DEFAULT NULL,
  `IpHash` varchar(64) DEFAULT NULL,
  `FechaRegistro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Factura`
--

CREATE TABLE `Factura` (
  `Id` int(11) NOT NULL,
  `IdComprobante` int(11) DEFAULT NULL,
  `PuntoVenta` int(11) DEFAULT NULL,
  `TipoComprobante` varchar(5) DEFAULT NULL,
  `NumeroFactura` int(11) DEFAULT NULL,
  `CAE` varchar(20) DEFAULT NULL,
  `CAEFechaVencimiento` date DEFAULT NULL,
  `FechaEmision` datetime DEFAULT NULL,
  `Estado` varchar(20) DEFAULT 'Pendiente',
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumosdecarpinteria`
--

CREATE TABLE `insumosdecarpinteria` (
  `Id` int(11) NOT NULL,
  `Descripcion` varchar(60) DEFAULT NULL,
  `IdTipodeMaterial` int(11) DEFAULT NULL,
  `IdTipodeCorte` int(11) DEFAULT NULL,
  `PrecioUnitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ColorHex` varchar(7) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Localidad`
--

CREATE TABLE `Localidad` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maderas`
--

CREATE TABLE `maderas` (
  `Id` int(11) NOT NULL,
  `Alto` decimal(10,2) DEFAULT NULL,
  `Largo` decimal(10,2) DEFAULT NULL,
  `Ancho` decimal(10,2) DEFAULT NULL,
  `IdTipodeMadera` int(11) DEFAULT NULL,
  `Formato` enum('plancha','tablon') NOT NULL DEFAULT 'tablon',
  `PrecioUnitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `Id` int(11) NOT NULL,
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
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pais`
--

CREATE TABLE `Pais` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pedido`
--

CREATE TABLE `Pedido` (
  `Id` int(11) NOT NULL,
  `Estado` varchar(30) DEFAULT NULL,
  `Responsable` varchar(50) DEFAULT NULL,
  `IdVenta` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PedidosCliente`
--

CREATE TABLE `PedidosCliente` (
  `Id` int(11) NOT NULL,
  `IdCLientes` int(11) DEFAULT NULL,
  `IdTipodePedido` int(11) DEFAULT NULL,
  `IdVenta` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Producto`
--

CREATE TABLE `Producto` (
  `Id` int(11) NOT NULL,
  `NombredelProducto` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `URLImagen` varchar(500) DEFAULT NULL,
  `Ancho` decimal(10,2) DEFAULT NULL,
  `Largo` decimal(10,2) DEFAULT NULL,
  `Alto` decimal(10,2) DEFAULT NULL,
  `CostoTotalMateriales` decimal(10,2) DEFAULT 0.00,
  `CostoManoDeObra` decimal(10,2) DEFAULT 0.00,
  `PorcentajeGanancia` decimal(5,2) NOT NULL DEFAULT 30.00 COMMENT '% ganancia sobre costo de materiales',
  `TiempoFabricacionHoras` decimal(5,2) DEFAULT 0.00,
  `PrecioVenta` decimal(10,2) DEFAULT NULL,
  `IdTipoIVA` int(11) DEFAULT NULL,
  `IdCategoria` int(11) DEFAULT NULL,
  `IdTipodeProducto` int(11) DEFAULT NULL,
  `IdTipodeDiseño` int(11) DEFAULT NULL,
  `IdTipodeAcabado` int(11) DEFAULT NULL,
  `IdColorAcabado` int(11) DEFAULT NULL,
  `IdTipodeHerraje` int(11) DEFAULT NULL,
  `IdTipodeAlmacenamiento` int(11) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `FechaBorrado` datetime DEFAULT NULL,
  `EstadoAprobacion` varchar(20) NOT NULL DEFAULT 'Aprobado',
  `IdUsuarioSugirio` int(11) DEFAULT NULL,
  `IdUsuarioAprobo` int(11) DEFAULT NULL,
  `FechaAprobacion` datetime DEFAULT NULL,
  `MotivoRechazo` varchar(255) DEFAULT NULL,
  `FechaNotificadoNuevo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ProductoCarrito`
--

CREATE TABLE `ProductoCarrito` (
  `Id` int(11) NOT NULL,
  `IdProducto` int(11) DEFAULT NULL,
  `IdCarrito` int(11) DEFAULT NULL,
  `Cantidad` int(11) NOT NULL DEFAULT 1,
  `PrecioUnitario` decimal(10,2) DEFAULT NULL,
  `PorcentajeIva` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ProductoImagenes`
--

CREATE TABLE `ProductoImagenes` (
  `Id` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `URLImagen` varchar(500) NOT NULL,
  `Orden` tinyint(4) NOT NULL DEFAULT 1,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ProductoInsumos`
--

CREATE TABLE `ProductoInsumos` (
  `Id` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdInsumoCarpinteria` int(11) NOT NULL,
  `CantidadNecesaria` decimal(10,2) NOT NULL,
  `CostoUnitario` decimal(10,2) DEFAULT NULL,
  `CostoTotal` decimal(10,2) DEFAULT NULL,
  `Observaciones` varchar(200) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ProductoMaderas`
--

CREATE TABLE `ProductoMaderas` (
  `Id` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdMadera` int(11) NOT NULL,
  `CantidadNecesaria` decimal(10,2) NOT NULL,
  `CostoUnitario` decimal(10,2) DEFAULT NULL,
  `CostoTotal` decimal(10,2) DEFAULT NULL,
  `Observaciones` varchar(200) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Proveedores`
--

CREATE TABLE `Proveedores` (
  `Id` int(11) NOT NULL,
  `cuit` int(11) DEFAULT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `Apellido` varchar(30) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL,
  `CorreoElectronico` varchar(50) DEFAULT NULL,
  `Calle` varchar(30) DEFAULT NULL,
  `Numero` int(11) DEFAULT NULL,
  `IdRazonSocial` int(11) DEFAULT NULL,
  `IdLocalidad` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RazonSocial`
--

CREATE TABLE `RazonSocial` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Remito`
--

CREATE TABLE `Remito` (
  `Id` int(11) NOT NULL,
  `NumerodeRemito` int(11) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `PrecioUnitario` decimal(10,2) DEFAULT NULL,
  `Subtotal` decimal(10,2) DEFAULT NULL,
  `FechadeEmision` datetime DEFAULT NULL,
  `IdTipodeEncargoRemito` int(11) DEFAULT NULL,
  `IdDetallesProveedor` int(11) DEFAULT NULL,
  `IdDatosEmpresa` int(11) DEFAULT NULL,
  `IdClientes` int(11) DEFAULT NULL,
  `IdEmisor` int(11) DEFAULT NULL,
  `IdProveedor` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Resena`
--

CREATE TABLE `Resena` (
  `Id` int(11) NOT NULL,
  `IdCliente` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdPedido` int(11) DEFAULT NULL,
  `Puntuacion` tinyint(4) NOT NULL,
  `Titulo` varchar(150) DEFAULT NULL,
  `ContenidoOriginal` text NOT NULL,
  `ContenidoPublicado` text DEFAULT NULL,
  `Estado` enum('pendiente','aprobada','rechazada','oculta','en_revision') NOT NULL DEFAULT 'pendiente',
  `FueEmbellecida` tinyint(1) NOT NULL DEFAULT 0,
  `FechaCreacion` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaModeracion` datetime DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ResenaAnalisisIA`
--

CREATE TABLE `ResenaAnalisisIA` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `IdResena` int(11) NOT NULL,
  `Sentimiento` enum('positivo','neutro','negativo') NOT NULL,
  `ScoreSentimiento` decimal(4,3) NOT NULL,
  `ScoreToxicidad` decimal(4,3) NOT NULL,
  `Categorias` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Categorias`)),
  `Flags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Flags`)),
  `ResumenCorto` varchar(200) DEFAULT NULL,
  `ModeloUsado` varchar(50) NOT NULL,
  `TokensConsumidos` int(11) NOT NULL DEFAULT 0,
  `FechaAnalisis` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Id`),
  KEY `fk_ria_resena` (`IdResena`),
  KEY `idx_ria_sent` (`Sentimiento`),
  KEY `idx_ria_fecha` (`FechaAnalisis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ResenaHistorial`
--

CREATE TABLE `ResenaHistorial` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `IdResena` int(11) NOT NULL,
  `Accion` enum('creada','analizada','embellecida','aprobada','rechazada','oculta','respondida','editada') NOT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `Detalle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Detalle`)),
  `Fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Id`),
  KEY `fk_rh_resena` (`IdResena`),
  KEY `idx_rh_accion` (`Accion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ResenaRespuesta`
--

CREATE TABLE `ResenaRespuesta` (
  `Id` int(11) NOT NULL,
  `IdResena` int(11) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `Contenido` text NOT NULL,
  `GeneradaPorIA` tinyint(1) NOT NULL DEFAULT 0,
  `Fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ResumenDeVentas`
--

CREATE TABLE `ResumenDeVentas` (
  `Id` int(11) NOT NULL,
  `Fecha` date NOT NULL COMMENT 'Día del resumen (sin hora)',
  `CantidadOrdenes` int(11) NOT NULL DEFAULT 0 COMMENT 'Total de ventas ese día',
  `MontoTotal` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Suma de MontoTotal de FacturaCliente',
  `MontoPromedio` decimal(10,2) DEFAULT NULL COMMENT 'Ticket promedio del día',
  `CantidadProductos` int(11) NOT NULL DEFAULT 0 COMMENT 'Unidades vendidas',
  `VentasAprobadas` int(11) NOT NULL DEFAULT 0,
  `VentasPendientes` int(11) NOT NULL DEFAULT 0,
  `VentasRechazadas` int(11) NOT NULL DEFAULT 0,
  `ClientesNuevos` int(11) NOT NULL DEFAULT 0 COMMENT 'Clientes registrados ese día',
  `VisitasTotales` int(11) NOT NULL DEFAULT 0 COMMENT 'Vistas de página ese día',
  `FechaActualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RutaEntrega`
--

CREATE TABLE `RutaEntrega` (
  `Id` int(11) NOT NULL,
  `IdRepartidor` int(11) NOT NULL,
  `FechaInicio` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaFin` datetime DEFAULT NULL,
  `Estado` varchar(20) NOT NULL DEFAULT 'En curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SoporteDeProduccion`
--

CREATE TABLE `SoporteDeProduccion` (
  `Id` int(11) NOT NULL,
  `Descripcón` varchar(30) DEFAULT NULL,
  `CargadeTrabajo` int(11) DEFAULT NULL,
  `IdTipodeProducto` int(11) DEFAULT NULL,
  `IdDiseño` int(11) DEFAULT NULL,
  `IdEmisor` int(11) DEFAULT NULL,
  `IdTipodeAcabado` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `Id` int(11) NOT NULL,
  `IdMaterial` int(11) NOT NULL COMMENT 'FK a maderas.Id o insumosdecarpinteria.Id',
  `TipoMaterial` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Madera, 2 = Insumo',
  `Cantidad` decimal(10,2) NOT NULL DEFAULT 0.00,
  `FechaIngreso` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `StockDiagnostico`
--

CREATE TABLE `StockDiagnostico` (
  `Id` int(11) NOT NULL,
  `FechaGenerado` datetime NOT NULL DEFAULT current_timestamp(),
  `GeneradoPor` int(11) DEFAULT NULL COMMENT 'FK → usuario.Id',
  `TotalMaderas` int(11) NOT NULL DEFAULT 0,
  `TotalInsumos` int(11) NOT NULL DEFAULT 0,
  `ValorTotalStock` decimal(14,2) NOT NULL DEFAULT 0.00,
  `ItemsBajoStock` int(11) NOT NULL DEFAULT 0,
  `ItemsSinStock` int(11) NOT NULL DEFAULT 0,
  `VariacionPromedioPct` decimal(6,2) DEFAULT NULL COMMENT 'Promedio % aumento de precios desde último diagnóstico',
  `DiagnosticoJSON` longtext NOT NULL COMMENT 'JSON con secciones: consumo, precios, alertas, recomendaciones',
  `ResumenTexto` text DEFAULT NULL COMMENT 'Primer párrafo del diagnóstico para preview',
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `StockHistorialPrecios`
--

CREATE TABLE `StockHistorialPrecios` (
  `Id` int(11) NOT NULL,
  `IdStock` int(11) DEFAULT NULL,
  `TipoMaterial` tinyint(1) NOT NULL COMMENT '1=Madera, 2=Insumo',
  `IdMaterial` int(11) NOT NULL COMMENT 'FK → maderas.Id o insumosdecarpinteria.Id',
  `PrecioAnterior` decimal(10,2) DEFAULT NULL,
  `PrecioNuevo` decimal(10,2) NOT NULL,
  `Variacion` decimal(6,2) GENERATED ALWAYS AS (case when `PrecioAnterior` > 0 then round((`PrecioNuevo` - `PrecioAnterior`) / `PrecioAnterior` * 100,2) else NULL end) STORED COMMENT '% de cambio respecto al precio anterior',
  `FechaRegistro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeAcabado`
--

CREATE TABLE `TipodeAcabado` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeAlmacenamiento`
--

CREATE TABLE `TipodeAlmacenamiento` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodecorte`
--

CREATE TABLE `tipodecorte` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `StockMinimo` decimal(10,2) NOT NULL DEFAULT 5.00,
  `StockAceptable` decimal(10,2) NOT NULL DEFAULT 20.00,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeDiseño`
--

CREATE TABLE `TipodeDiseño` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeDni`
--

CREATE TABLE `TipodeDni` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeEncargoRemito`
--

CREATE TABLE `TipodeEncargoRemito` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeEntrega`
--

CREATE TABLE `TipodeEntrega` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeHerraje`
--

CREATE TABLE `TipodeHerraje` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `Descripcion` varchar(300) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodemadera`
--

CREATE TABLE `tipodemadera` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodematerial`
--

CREATE TABLE `tipodematerial` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodePago`
--

CREATE TABLE `TipodePago` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodePedido`
--

CREATE TABLE `TipodePedido` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeProducto`
--

CREATE TABLE `TipodeProducto` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeProductoAcabado`
--

CREATE TABLE `TipodeProductoAcabado` (
  `Id` int(11) NOT NULL,
  `IdTipodeProducto` int(11) NOT NULL,
  `IdTipodeAcabado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeProductoAlmacenamiento`
--

CREATE TABLE `TipodeProductoAlmacenamiento` (
  `Id` int(11) NOT NULL,
  `IdTipodeProducto` int(11) NOT NULL,
  `IdTipodeAlmacenamiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeProductoDiseño`
--

CREATE TABLE `TipodeProductoDiseño` (
  `Id` int(11) NOT NULL,
  `IdTipodeProducto` int(11) NOT NULL,
  `IdTipodeDiseño` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeProductoHerraje`
--

CREATE TABLE `TipodeProductoHerraje` (
  `Id` int(11) NOT NULL,
  `IdTipodeProducto` int(11) NOT NULL,
  `IdTipodeHerraje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeRol`
--

CREATE TABLE `TipodeRol` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipodeUsuario`
--

CREATE TABLE `TipodeUsuario` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipoDomicilio`
--

CREATE TABLE `TipoDomicilio` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipoIVA`
--

CREATE TABLE `TipoIVA` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Porcentaje` decimal(5,2) NOT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UbicacionRepartidor`
--

CREATE TABLE `UbicacionRepartidor` (
  `IdRepartidor` int(11) NOT NULL,
  `Lat` decimal(10,7) NOT NULL,
  `Lng` decimal(10,7) NOT NULL,
  `FechaActualizacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `UmbralFormatoMadera`
--

CREATE TABLE `UmbralFormatoMadera` (
  `Formato` enum('plancha','tablon') NOT NULL,
  `StockMinimo` decimal(10,2) NOT NULL DEFAULT 10.00,
  `StockAceptable` decimal(10,2) NOT NULL DEFAULT 40.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE `Usuario` (
  `Id` int(11) NOT NULL,
  `NombredeUsuario` varchar(40) DEFAULT NULL,
  `Contraseña` varchar(300) DEFAULT NULL,
  `CorreoElectronico` varchar(50) DEFAULT NULL,
  `Restablecer` int(11) DEFAULT NULL,
  `Confirmado` int(11) DEFAULT NULL,
  `Token` varchar(700) DEFAULT NULL,
  `IdTipodeUsuario` int(11) DEFAULT NULL,
  `IdTipodeRol` int(11) DEFAULT NULL,
  `IdCliente` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Venta`
--

CREATE TABLE `Venta` (
  `Id` int(11) NOT NULL,
  `NumerodeVenta` int(11) DEFAULT NULL,
  `CantidadTotal` int(11) DEFAULT NULL,
  `IdCarrito` int(11) DEFAULT NULL,
  `IdComprobante` int(11) DEFAULT NULL,
  `Identrega` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `VistasDePagina`
--

CREATE TABLE `VistasDePagina` (
  `Id` int(11) NOT NULL,
  `UrlVisitada` varchar(500) NOT NULL COMMENT 'URL completa visitada',
  `Titulo` varchar(200) DEFAULT NULL COMMENT 'document.title de la página',
  `Referidor` varchar(500) DEFAULT NULL COMMENT 'document.referrer (origen)',
  `IdUsuario` int(11) DEFAULT NULL COMMENT 'FK Usuario.Id — NULL si anónimo',
  `IdCliente` int(11) DEFAULT NULL COMMENT 'FK Clientes.Id — NULL si anónimo',
  `SesionId` varchar(100) DEFAULT NULL COMMENT 'session_id() del servidor PHP',
  `DispositivoTipo` varchar(20) DEFAULT NULL COMMENT 'mobile / tablet / desktop',
  `Navegador` varchar(100) DEFAULT NULL COMMENT 'User-Agent simplificado',
  `IpHash` varchar(64) DEFAULT NULL COMMENT 'SHA-256 de IP (privacidad)',
  `TiempoEnPagina` int(11) DEFAULT NULL COMMENT 'Segundos en la página (beacon)',
  `FechaRegistro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `_bak_insumos`
--

CREATE TABLE `_bak_insumos` (
  `Id` int(11) NOT NULL DEFAULT 0,
  `PrecioUniatrio` decimal(10,2) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Descripcion` varchar(20) DEFAULT NULL,
  `IdTipodeMaterial` int(11) DEFAULT NULL,
  `IdTipodeCorte` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `_bak_maderas`
--

CREATE TABLE `_bak_maderas` (
  `Id` int(11) NOT NULL DEFAULT 0,
  `PrecioUnitario` decimal(10,2) DEFAULT NULL,
  `CantidadStock` int(11) DEFAULT NULL,
  `Alto` decimal(10,2) DEFAULT NULL,
  `Largo` decimal(10,2) DEFAULT NULL,
  `Ancho` decimal(10,2) DEFAULT NULL,
  `IdTipodeMadera` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `_bak_stock`
--

CREATE TABLE `_bak_stock` (
  `Id` int(11) NOT NULL DEFAULT 0,
  `Fecha` datetime DEFAULT NULL,
  `CantitdadTotal` int(11) DEFAULT NULL,
  `MontoTotal` decimal(10,2) DEFAULT NULL,
  `IdMaderas` int(11) DEFAULT NULL,
  `IdInsumosdeCarpinteria` int(11) DEFAULT NULL,
  `FechaBorrado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `AutorizaciondePago`
--
ALTER TABLE `AutorizaciondePago`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `Busquedas`
--
ALTER TABLE `Busquedas`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_busquedas_termino` (`TerminoBuscado`(100)),
  ADD KEY `idx_busquedas_fecha` (`FechaRegistro`),
  ADD KEY `busquedas_ibfk_1` (`IdUsuario`),
  ADD KEY `busquedas_ibfk_2` (`IdCliente`);

--
-- Indices de la tabla `Caja`
--
ALTER TABLE `Caja`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdEmisor` (`IdEmisor`);

--
-- Indices de la tabla `Carrito`
--
ALTER TABLE `Carrito`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdCliente` (`IdCliente`);

--
-- Indices de la tabla `Categoria`
--
ALTER TABLE `Categoria`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `Clientes`
--
ALTER TABLE `Clientes`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdLocalidad` (`IdLocalidad`),
  ADD KEY `IdDomicilio` (`IdDomicilio`),
  ADD KEY `IdTipodeDni` (`IdTipodeDni`),
  ADD KEY `IdTipodomicilio` (`IdTipodomicilio`);

--
-- Indices de la tabla `ColorAcabado`
--
ALTER TABLE `ColorAcabado`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `Comprobante`
--
ALTER TABLE `Comprobante`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdEmisor` (`IdEmisor`),
  ADD KEY `IdTipodePago` (`IdTipodePago`),
  ADD KEY `IdEstadodePago` (`IdEstadodePago`),
  ADD KEY `IdEntidadBancaria` (`IdEntidadBancaria`),
  ADD KEY `IdDatosEmpresa` (`IdDatosEmpresa`),
  ADD KEY `IdAutorizaciondePago` (`IdAutorizaciondePago`),
  ADD KEY `IdClientes` (`IdClientes`);

--
-- Indices de la tabla `ConfiguracionGlobal`
--
ALTER TABLE `ConfiguracionGlobal`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `DatosEmpresa`
--
ALTER TABLE `DatosEmpresa`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdRazonSocial` (`IdRazonSocial`),
  ADD KEY `IdLocalidad` (`IdLocalidad`);

--
-- Indices de la tabla `DetallesDeBalance`
--
ALTER TABLE `DetallesDeBalance`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdProducto` (`IdProducto`),
  ADD KEY `IdUsuario` (`IdUsuario`),
  ADD KEY `IdCaja` (`IdCaja`),
  ADD KEY `IdEstadoBancarios` (`IdEstadoBancarios`),
  ADD KEY `IdEmisor` (`IdEmisor`),
  ADD KEY `detallesdebalance_ibfk_3` (`IdStock`);

--
-- Indices de la tabla `DetallesProveedor`
--
ALTER TABLE `DetallesProveedor`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdMaderas` (`IdMaderas`),
  ADD KEY `IdInsumosdeCarpinteria` (`IdInsumosdeCarpinteria`);

--
-- Indices de la tabla `DetallesVenta`
--
ALTER TABLE `DetallesVenta`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipodeProducto` (`IdTipodeProducto`),
  ADD KEY `IdTipodeMadera` (`IdTipodeMadera`),
  ADD KEY `IdTipodeAcabado` (`IdTipodeAcabado`),
  ADD KEY `fk_detallesventa_venta` (`IdVenta`);

--
-- Indices de la tabla `Diseño`
--
ALTER TABLE `Diseño`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipodeDiseño` (`IdTipodeDiseño`),
  ADD KEY `IdUsuario` (`IdUsuario`),
  ADD KEY `IdEmisor` (`IdEmisor`);

--
-- Indices de la tabla `Domicilio`
--
ALTER TABLE `Domicilio`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipoDomicilio` (`IdTipoDomicilio`);

--
-- Indices de la tabla `Emisor`
--
ALTER TABLE `Emisor`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdUsuario` (`IdUsuario`);

--
-- Indices de la tabla `EntidadBancaria`
--
ALTER TABLE `EntidadBancaria`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `Entrega`
--
ALTER TABLE `Entrega`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipodeEntrega` (`IdTipodeEntrega`),
  ADD KEY `IdEstadosdeEntrega` (`IdEstadosdeEntrega`),
  ADD KEY `IdUsuario` (`IdUsuario`),
  ADD KEY `IdVenta` (`IdVenta`);

--
-- Indices de la tabla `EstadoBancarios`
--
ALTER TABLE `EstadoBancarios`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdEmisor` (`IdEmisor`);

--
-- Indices de la tabla `EstadodePago`
--
ALTER TABLE `EstadodePago`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `EstadosdeEntrega`
--
ALTER TABLE `EstadosdeEntrega`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `EventosDeUsuario`
--
ALTER TABLE `EventosDeUsuario`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_eventos_tipo` (`TipoEvento`),
  ADD KEY `idx_eventos_usuario` (`IdUsuario`),
  ADD KEY `idx_eventos_cliente` (`IdCliente`),
  ADD KEY `idx_eventos_fecha` (`FechaRegistro`),
  ADD KEY `idx_eventos_modulo` (`Modulo`);

--
-- Indices de la tabla `Factura`
--
ALTER TABLE `Factura`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `insumosdecarpinteria`
--
ALTER TABLE `insumosdecarpinteria`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipodeMaterial` (`IdTipodeMaterial`),
  ADD KEY `IdTipodeCorte` (`IdTipodeCorte`);

--
-- Indices de la tabla `Localidad`
--
ALTER TABLE `Localidad`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `maderas`
--
ALTER TABLE `maderas`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipodeMadera` (`IdTipodeMadera`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_notif_usuario` (`IdUsuario`,`Leida`),
  ADD KEY `idx_notif_cliente` (`IdCliente`,`Leida`),
  ADD KEY `idx_notif_fecha` (`FechaCreacion`);

--
-- Indices de la tabla `Pais`
--
ALTER TABLE `Pais`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `Pedido`
--
ALTER TABLE `Pedido`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdVenta` (`IdVenta`);

--
-- Indices de la tabla `PedidosCliente`
--
ALTER TABLE `PedidosCliente`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdCLientes` (`IdCLientes`),
  ADD KEY `IdTipodePedido` (`IdTipodePedido`),
  ADD KEY `IdVenta` (`IdVenta`);

--
-- Indices de la tabla `Producto`
--
ALTER TABLE `Producto`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdCategoria` (`IdCategoria`),
  ADD KEY `IdTipodeProducto` (`IdTipodeProducto`),
  ADD KEY `IdTipodeDiseño` (`IdTipodeDiseño`),
  ADD KEY `IdTipodeAcabado` (`IdTipodeAcabado`),
  ADD KEY `IdTipodeHerraje` (`IdTipodeHerraje`),
  ADD KEY `IdTipodeAlmacenamiento` (`IdTipodeAlmacenamiento`);

--
-- Indices de la tabla `ProductoCarrito`
--
ALTER TABLE `ProductoCarrito`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdProducto` (`IdProducto`),
  ADD KEY `IdCarrito` (`IdCarrito`);

--
-- Indices de la tabla `ProductoImagenes`
--
ALTER TABLE `ProductoImagenes`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_producto_imagenes` (`IdProducto`);

--
-- Indices de la tabla `ProductoInsumos`
--
ALTER TABLE `ProductoInsumos`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdProducto` (`IdProducto`),
  ADD KEY `IdInsumoCarpinteria` (`IdInsumoCarpinteria`);

--
-- Indices de la tabla `ProductoMaderas`
--
ALTER TABLE `ProductoMaderas`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdProducto` (`IdProducto`),
  ADD KEY `IdMadera` (`IdMadera`);

--
-- Indices de la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdRazonSocial` (`IdRazonSocial`),
  ADD KEY `IdLocalidad` (`IdLocalidad`);

--
-- Indices de la tabla `RazonSocial`
--
ALTER TABLE `RazonSocial`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `Remito`
--
ALTER TABLE `Remito`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipodeEncargoRemito` (`IdTipodeEncargoRemito`),
  ADD KEY `IdDetallesProveedor` (`IdDetallesProveedor`),
  ADD KEY `IdDatosEmpresa` (`IdDatosEmpresa`),
  ADD KEY `IdClientes` (`IdClientes`),
  ADD KEY `IdEmisor` (`IdEmisor`),
  ADD KEY `IdProveedor` (`IdProveedor`);

--
-- Indices de la tabla `Resena`
--
ALTER TABLE `Resena`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_resena_prod_estado` (`IdProducto`,`Estado`),
  ADD KEY `idx_resena_cliente` (`IdCliente`);

--
-- Indices de la tabla `ResenaRespuesta`
--
ALTER TABLE `ResenaRespuesta`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_rr_resena` (`IdResena`);

--
-- Indices de la tabla `ResumenDeVentas`
--
ALTER TABLE `ResumenDeVentas`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `uk_resumen_fecha` (`Fecha`),
  ADD KEY `idx_resumen_fecha` (`Fecha`);

--
-- Indices de la tabla `RutaEntrega`
--
ALTER TABLE `RutaEntrega`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `SoporteDeProduccion`
--
ALTER TABLE `SoporteDeProduccion`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipodeProducto` (`IdTipodeProducto`),
  ADD KEY `IdDiseño` (`IdDiseño`),
  ADD KEY `IdEmisor` (`IdEmisor`),
  ADD KEY `IdTipodeAcabado` (`IdTipodeAcabado`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_stock_material` (`IdMaterial`,`TipoMaterial`);

--
-- Indices de la tabla `StockDiagnostico`
--
ALTER TABLE `StockDiagnostico`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_sd_fecha` (`FechaGenerado`),
  ADD KEY `idx_sd_usuario` (`GeneradoPor`);

--
-- Indices de la tabla `StockHistorialPrecios`
--
ALTER TABLE `StockHistorialPrecios`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_shp_stock` (`IdStock`),
  ADD KEY `idx_shp_fecha` (`FechaRegistro`),
  ADD KEY `idx_shp_material` (`TipoMaterial`,`IdMaterial`);

--
-- Indices de la tabla `TipodeAcabado`
--
ALTER TABLE `TipodeAcabado`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeAlmacenamiento`
--
ALTER TABLE `TipodeAlmacenamiento`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tipodecorte`
--
ALTER TABLE `tipodecorte`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeDiseño`
--
ALTER TABLE `TipodeDiseño`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeDni`
--
ALTER TABLE `TipodeDni`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeEncargoRemito`
--
ALTER TABLE `TipodeEncargoRemito`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeEntrega`
--
ALTER TABLE `TipodeEntrega`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeHerraje`
--
ALTER TABLE `TipodeHerraje`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tipodemadera`
--
ALTER TABLE `tipodemadera`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tipodematerial`
--
ALTER TABLE `tipodematerial`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodePago`
--
ALTER TABLE `TipodePago`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodePedido`
--
ALTER TABLE `TipodePedido`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeProducto`
--
ALTER TABLE `TipodeProducto`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeProductoAcabado`
--
ALTER TABLE `TipodeProductoAcabado`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `uq_tp_acabado` (`IdTipodeProducto`,`IdTipodeAcabado`);

--
-- Indices de la tabla `TipodeProductoAlmacenamiento`
--
ALTER TABLE `TipodeProductoAlmacenamiento`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `uq_tp_almacen` (`IdTipodeProducto`,`IdTipodeAlmacenamiento`);

--
-- Indices de la tabla `TipodeProductoDiseño`
--
ALTER TABLE `TipodeProductoDiseño`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `uq_tp_diseño` (`IdTipodeProducto`,`IdTipodeDiseño`);

--
-- Indices de la tabla `TipodeProductoHerraje`
--
ALTER TABLE `TipodeProductoHerraje`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `uq_tp_herraje` (`IdTipodeProducto`,`IdTipodeHerraje`);

--
-- Indices de la tabla `TipodeRol`
--
ALTER TABLE `TipodeRol`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipodeUsuario`
--
ALTER TABLE `TipodeUsuario`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipoDomicilio`
--
ALTER TABLE `TipoDomicilio`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `TipoIVA`
--
ALTER TABLE `TipoIVA`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `UbicacionRepartidor`
--
ALTER TABLE `UbicacionRepartidor`
  ADD PRIMARY KEY (`IdRepartidor`);

--
-- Indices de la tabla `UmbralFormatoMadera`
--
ALTER TABLE `UmbralFormatoMadera`
  ADD PRIMARY KEY (`Formato`);

--
-- Indices de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdTipodeUsuario` (`IdTipodeUsuario`),
  ADD KEY `IdTipodeRol` (`IdTipodeRol`),
  ADD KEY `IdCliente` (`IdCliente`);

--
-- Indices de la tabla `Venta`
--
ALTER TABLE `Venta`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdCarrito` (`IdCarrito`),
  ADD KEY `IdFacturaCliente` (`IdComprobante`);

--
-- Indices de la tabla `VistasDePagina`
--
ALTER TABLE `VistasDePagina`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_vistas_usuario` (`IdUsuario`),
  ADD KEY `idx_vistas_cliente` (`IdCliente`),
  ADD KEY `idx_vistas_fecha` (`FechaRegistro`),
  ADD KEY `idx_vistas_url` (`UrlVisitada`(191));

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `AutorizaciondePago`
--
ALTER TABLE `AutorizaciondePago`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Busquedas`
--
ALTER TABLE `Busquedas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Caja`
--
ALTER TABLE `Caja`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Carrito`
--
ALTER TABLE `Carrito`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Categoria`
--
ALTER TABLE `Categoria`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Clientes`
--
ALTER TABLE `Clientes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ColorAcabado`
--
ALTER TABLE `ColorAcabado`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Comprobante`
--
ALTER TABLE `Comprobante`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `DatosEmpresa`
--
ALTER TABLE `DatosEmpresa`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `DetallesDeBalance`
--
ALTER TABLE `DetallesDeBalance`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `DetallesProveedor`
--
ALTER TABLE `DetallesProveedor`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `DetallesVenta`
--
ALTER TABLE `DetallesVenta`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Diseño`
--
ALTER TABLE `Diseño`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Domicilio`
--
ALTER TABLE `Domicilio`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Emisor`
--
ALTER TABLE `Emisor`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EntidadBancaria`
--
ALTER TABLE `EntidadBancaria`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Entrega`
--
ALTER TABLE `Entrega`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EstadoBancarios`
--
ALTER TABLE `EstadoBancarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EstadodePago`
--
ALTER TABLE `EstadodePago`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EstadosdeEntrega`
--
ALTER TABLE `EstadosdeEntrega`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EventosDeUsuario`
--
ALTER TABLE `EventosDeUsuario`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Factura`
--
ALTER TABLE `Factura`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `insumosdecarpinteria`
--
ALTER TABLE `insumosdecarpinteria`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Localidad`
--
ALTER TABLE `Localidad`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maderas`
--
ALTER TABLE `maderas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Pais`
--
ALTER TABLE `Pais`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Pedido`
--
ALTER TABLE `Pedido`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `PedidosCliente`
--
ALTER TABLE `PedidosCliente`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Producto`
--
ALTER TABLE `Producto`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ProductoCarrito`
--
ALTER TABLE `ProductoCarrito`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ProductoImagenes`
--
ALTER TABLE `ProductoImagenes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ProductoInsumos`
--
ALTER TABLE `ProductoInsumos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ProductoMaderas`
--
ALTER TABLE `ProductoMaderas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RazonSocial`
--
ALTER TABLE `RazonSocial`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Remito`
--
ALTER TABLE `Remito`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Resena`
--
ALTER TABLE `Resena`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ResenaAnalisisIA`
--
ALTER TABLE `ResenaAnalisisIA`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ResenaHistorial`
--
ALTER TABLE `ResenaHistorial`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ResenaRespuesta`
--
ALTER TABLE `ResenaRespuesta`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ResumenDeVentas`
--
ALTER TABLE `ResumenDeVentas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RutaEntrega`
--
ALTER TABLE `RutaEntrega`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `SoporteDeProduccion`
--
ALTER TABLE `SoporteDeProduccion`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `StockDiagnostico`
--
ALTER TABLE `StockDiagnostico`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `StockHistorialPrecios`
--
ALTER TABLE `StockHistorialPrecios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeAcabado`
--
ALTER TABLE `TipodeAcabado`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeAlmacenamiento`
--
ALTER TABLE `TipodeAlmacenamiento`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipodecorte`
--
ALTER TABLE `tipodecorte`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeDiseño`
--
ALTER TABLE `TipodeDiseño`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeDni`
--
ALTER TABLE `TipodeDni`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeEncargoRemito`
--
ALTER TABLE `TipodeEncargoRemito`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeEntrega`
--
ALTER TABLE `TipodeEntrega`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeHerraje`
--
ALTER TABLE `TipodeHerraje`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipodemadera`
--
ALTER TABLE `tipodemadera`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipodematerial`
--
ALTER TABLE `tipodematerial`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodePago`
--
ALTER TABLE `TipodePago`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodePedido`
--
ALTER TABLE `TipodePedido`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeProducto`
--
ALTER TABLE `TipodeProducto`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeProductoAcabado`
--
ALTER TABLE `TipodeProductoAcabado`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeProductoAlmacenamiento`
--
ALTER TABLE `TipodeProductoAlmacenamiento`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeProductoDiseño`
--
ALTER TABLE `TipodeProductoDiseño`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeProductoHerraje`
--
ALTER TABLE `TipodeProductoHerraje`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeRol`
--
ALTER TABLE `TipodeRol`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipodeUsuario`
--
ALTER TABLE `TipodeUsuario`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipoDomicilio`
--
ALTER TABLE `TipoDomicilio`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TipoIVA`
--
ALTER TABLE `TipoIVA`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Venta`
--
ALTER TABLE `Venta`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `VistasDePagina`
--
ALTER TABLE `VistasDePagina`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Busquedas`
--
ALTER TABLE `Busquedas`
  ADD CONSTRAINT `Busquedas_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`) ON DELETE SET NULL,
  ADD CONSTRAINT `Busquedas_ibfk_2` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `Caja`
--
ALTER TABLE `Caja`
  ADD CONSTRAINT `Caja_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`);

--
-- Filtros para la tabla `Carrito`
--
ALTER TABLE `Carrito`
  ADD CONSTRAINT `Carrito_ibfk_1` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`);

--
-- Filtros para la tabla `Clientes`
--
ALTER TABLE `Clientes`
  ADD CONSTRAINT `Clientes_ibfk_1` FOREIGN KEY (`IdLocalidad`) REFERENCES `Localidad` (`Id`),
  ADD CONSTRAINT `Clientes_ibfk_2` FOREIGN KEY (`IdDomicilio`) REFERENCES `Domicilio` (`Id`),
  ADD CONSTRAINT `Clientes_ibfk_3` FOREIGN KEY (`IdTipodeDni`) REFERENCES `TipodeDni` (`Id`),
  ADD CONSTRAINT `Clientes_ibfk_4` FOREIGN KEY (`IdTipodomicilio`) REFERENCES `TipoDomicilio` (`Id`);

--
-- Filtros para la tabla `Comprobante`
--
ALTER TABLE `Comprobante`
  ADD CONSTRAINT `Comprobante_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`),
  ADD CONSTRAINT `Comprobante_ibfk_2` FOREIGN KEY (`IdTipodePago`) REFERENCES `TipodePago` (`Id`),
  ADD CONSTRAINT `Comprobante_ibfk_3` FOREIGN KEY (`IdEstadodePago`) REFERENCES `EstadodePago` (`Id`),
  ADD CONSTRAINT `Comprobante_ibfk_4` FOREIGN KEY (`IdEntidadBancaria`) REFERENCES `EntidadBancaria` (`Id`),
  ADD CONSTRAINT `Comprobante_ibfk_5` FOREIGN KEY (`IdDatosEmpresa`) REFERENCES `DatosEmpresa` (`Id`),
  ADD CONSTRAINT `Comprobante_ibfk_6` FOREIGN KEY (`IdAutorizaciondePago`) REFERENCES `AutorizaciondePago` (`Id`),
  ADD CONSTRAINT `Comprobante_ibfk_7` FOREIGN KEY (`IdClientes`) REFERENCES `Clientes` (`Id`);

--
-- Filtros para la tabla `DatosEmpresa`
--
ALTER TABLE `DatosEmpresa`
  ADD CONSTRAINT `DatosEmpresa_ibfk_1` FOREIGN KEY (`IdRazonSocial`) REFERENCES `RazonSocial` (`Id`),
  ADD CONSTRAINT `DatosEmpresa_ibfk_2` FOREIGN KEY (`IdLocalidad`) REFERENCES `Localidad` (`Id`);

--
-- Filtros para la tabla `DetallesDeBalance`
--
ALTER TABLE `DetallesDeBalance`
  ADD CONSTRAINT `DetallesDeBalance_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`),
  ADD CONSTRAINT `DetallesDeBalance_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`),
  ADD CONSTRAINT `DetallesDeBalance_ibfk_3` FOREIGN KEY (`IdStock`) REFERENCES `stock` (`Id`),
  ADD CONSTRAINT `DetallesDeBalance_ibfk_4` FOREIGN KEY (`IdCaja`) REFERENCES `Caja` (`Id`),
  ADD CONSTRAINT `DetallesDeBalance_ibfk_5` FOREIGN KEY (`IdEstadoBancarios`) REFERENCES `EstadoBancarios` (`Id`),
  ADD CONSTRAINT `DetallesDeBalance_ibfk_6` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`);

--
-- Filtros para la tabla `DetallesProveedor`
--
ALTER TABLE `DetallesProveedor`
  ADD CONSTRAINT `DetallesProveedor_ibfk_1` FOREIGN KEY (`IdMaderas`) REFERENCES `maderas` (`Id`),
  ADD CONSTRAINT `DetallesProveedor_ibfk_2` FOREIGN KEY (`IdInsumosdeCarpinteria`) REFERENCES `insumosdecarpinteria` (`Id`);

--
-- Filtros para la tabla `DetallesVenta`
--
ALTER TABLE `DetallesVenta`
  ADD CONSTRAINT `DetallesVenta_ibfk_1` FOREIGN KEY (`IdTipodeProducto`) REFERENCES `TipodeProducto` (`Id`),
  ADD CONSTRAINT `DetallesVenta_ibfk_2` FOREIGN KEY (`IdTipodeMadera`) REFERENCES `tipodemadera` (`Id`),
  ADD CONSTRAINT `DetallesVenta_ibfk_3` FOREIGN KEY (`IdTipodeAcabado`) REFERENCES `TipodeAcabado` (`Id`),
  ADD CONSTRAINT `fk_detallesventa_venta` FOREIGN KEY (`IdVenta`) REFERENCES `Venta` (`Id`);

--
-- Filtros para la tabla `Diseño`
--
ALTER TABLE `Diseño`
  ADD CONSTRAINT `Diseño_ibfk_1` FOREIGN KEY (`IdTipodeDiseño`) REFERENCES `TipodeDiseño` (`Id`),
  ADD CONSTRAINT `Diseño_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`),
  ADD CONSTRAINT `Diseño_ibfk_3` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`);

--
-- Filtros para la tabla `Domicilio`
--
ALTER TABLE `Domicilio`
  ADD CONSTRAINT `Domicilio_ibfk_1` FOREIGN KEY (`IdTipoDomicilio`) REFERENCES `TipoDomicilio` (`Id`);

--
-- Filtros para la tabla `Emisor`
--
ALTER TABLE `Emisor`
  ADD CONSTRAINT `Emisor_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`);

--
-- Filtros para la tabla `Entrega`
--
ALTER TABLE `Entrega`
  ADD CONSTRAINT `Entrega_ibfk_1` FOREIGN KEY (`IdTipodeEntrega`) REFERENCES `TipodeEntrega` (`Id`),
  ADD CONSTRAINT `Entrega_ibfk_2` FOREIGN KEY (`IdEstadosdeEntrega`) REFERENCES `EstadosdeEntrega` (`Id`),
  ADD CONSTRAINT `Entrega_ibfk_3` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`),
  ADD CONSTRAINT `Entrega_ibfk_4` FOREIGN KEY (`IdVenta`) REFERENCES `Venta` (`Id`);

--
-- Filtros para la tabla `EstadoBancarios`
--
ALTER TABLE `EstadoBancarios`
  ADD CONSTRAINT `EstadoBancarios_ibfk_1` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`);

--
-- Filtros para la tabla `EventosDeUsuario`
--
ALTER TABLE `EventosDeUsuario`
  ADD CONSTRAINT `eventosUsuario_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`) ON DELETE SET NULL,
  ADD CONSTRAINT `eventosUsuario_ibfk_2` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `insumosdecarpinteria`
--
ALTER TABLE `insumosdecarpinteria`
  ADD CONSTRAINT `insumosdecarpinteria_ibfk_1` FOREIGN KEY (`IdTipodeMaterial`) REFERENCES `tipodematerial` (`Id`),
  ADD CONSTRAINT `insumosdecarpinteria_ibfk_2` FOREIGN KEY (`IdTipodeCorte`) REFERENCES `tipodecorte` (`Id`);

--
-- Filtros para la tabla `maderas`
--
ALTER TABLE `maderas`
  ADD CONSTRAINT `maderas_ibfk_1` FOREIGN KEY (`IdTipodeMadera`) REFERENCES `tipodemadera` (`Id`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificaciones_ibfk_2` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Pedido`
--
ALTER TABLE `Pedido`
  ADD CONSTRAINT `Pedido_ibfk_1` FOREIGN KEY (`IdVenta`) REFERENCES `Venta` (`Id`);

--
-- Filtros para la tabla `PedidosCliente`
--
ALTER TABLE `PedidosCliente`
  ADD CONSTRAINT `PedidosCliente_ibfk_1` FOREIGN KEY (`IdCLientes`) REFERENCES `Clientes` (`Id`),
  ADD CONSTRAINT `PedidosCliente_ibfk_2` FOREIGN KEY (`IdTipodePedido`) REFERENCES `TipodePedido` (`Id`),
  ADD CONSTRAINT `PedidosCliente_ibfk_3` FOREIGN KEY (`IdVenta`) REFERENCES `Venta` (`Id`);

--
-- Filtros para la tabla `Producto`
--
ALTER TABLE `Producto`
  ADD CONSTRAINT `Producto_ibfk_1` FOREIGN KEY (`IdCategoria`) REFERENCES `Categoria` (`Id`),
  ADD CONSTRAINT `Producto_ibfk_2` FOREIGN KEY (`IdTipodeProducto`) REFERENCES `TipodeProducto` (`Id`),
  ADD CONSTRAINT `Producto_ibfk_3` FOREIGN KEY (`IdTipodeDiseño`) REFERENCES `TipodeDiseño` (`Id`),
  ADD CONSTRAINT `Producto_ibfk_4` FOREIGN KEY (`IdTipodeAcabado`) REFERENCES `TipodeAcabado` (`Id`),
  ADD CONSTRAINT `Producto_ibfk_5` FOREIGN KEY (`IdTipodeHerraje`) REFERENCES `TipodeHerraje` (`Id`),
  ADD CONSTRAINT `Producto_ibfk_6` FOREIGN KEY (`IdTipodeAlmacenamiento`) REFERENCES `TipodeAlmacenamiento` (`Id`);

--
-- Filtros para la tabla `ProductoCarrito`
--
ALTER TABLE `ProductoCarrito`
  ADD CONSTRAINT `ProductoCarrito_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`),
  ADD CONSTRAINT `ProductoCarrito_ibfk_2` FOREIGN KEY (`IdCarrito`) REFERENCES `Carrito` (`Id`);

--
-- Filtros para la tabla `ProductoImagenes`
--
ALTER TABLE `ProductoImagenes`
  ADD CONSTRAINT `ProductoImagenes_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ProductoInsumos`
--
ALTER TABLE `ProductoInsumos`
  ADD CONSTRAINT `ProductoInsumos_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ProductoInsumos_ibfk_2` FOREIGN KEY (`IdInsumoCarpinteria`) REFERENCES `insumosdecarpinteria` (`Id`);

--
-- Filtros para la tabla `ProductoMaderas`
--
ALTER TABLE `ProductoMaderas`
  ADD CONSTRAINT `ProductoMaderas_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ProductoMaderas_ibfk_2` FOREIGN KEY (`IdMadera`) REFERENCES `maderas` (`Id`);

--
-- Filtros para la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  ADD CONSTRAINT `Proveedores_ibfk_1` FOREIGN KEY (`IdRazonSocial`) REFERENCES `RazonSocial` (`Id`),
  ADD CONSTRAINT `Proveedores_ibfk_2` FOREIGN KEY (`IdLocalidad`) REFERENCES `Localidad` (`Id`);

--
-- Filtros para la tabla `Remito`
--
ALTER TABLE `Remito`
  ADD CONSTRAINT `Remito_ibfk_1` FOREIGN KEY (`IdTipodeEncargoRemito`) REFERENCES `TipodeEncargoRemito` (`Id`),
  ADD CONSTRAINT `Remito_ibfk_2` FOREIGN KEY (`IdDetallesProveedor`) REFERENCES `DetallesProveedor` (`Id`),
  ADD CONSTRAINT `Remito_ibfk_3` FOREIGN KEY (`IdDatosEmpresa`) REFERENCES `DatosEmpresa` (`Id`),
  ADD CONSTRAINT `Remito_ibfk_4` FOREIGN KEY (`IdClientes`) REFERENCES `Clientes` (`Id`),
  ADD CONSTRAINT `Remito_ibfk_5` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`),
  ADD CONSTRAINT `Remito_ibfk_6` FOREIGN KEY (`IdProveedor`) REFERENCES `Proveedores` (`Id`);

--
-- Filtros para la tabla `Resena`
--
ALTER TABLE `Resena`
  ADD CONSTRAINT `fk_resena_cliente` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`),
  ADD CONSTRAINT `fk_resena_producto` FOREIGN KEY (`IdProducto`) REFERENCES `Producto` (`Id`);

--
-- Filtros para la tabla `ResenaRespuesta`
--
ALTER TABLE `ResenaRespuesta`
  ADD CONSTRAINT `fk_rr_resena` FOREIGN KEY (`IdResena`) REFERENCES `Resena` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `SoporteDeProduccion`
--
ALTER TABLE `SoporteDeProduccion`
  ADD CONSTRAINT `SoporteDeProduccion_ibfk_1` FOREIGN KEY (`IdTipodeProducto`) REFERENCES `TipodeProducto` (`Id`),
  ADD CONSTRAINT `SoporteDeProduccion_ibfk_2` FOREIGN KEY (`IdDiseño`) REFERENCES `Diseño` (`Id`),
  ADD CONSTRAINT `SoporteDeProduccion_ibfk_3` FOREIGN KEY (`IdEmisor`) REFERENCES `Emisor` (`Id`),
  ADD CONSTRAINT `SoporteDeProduccion_ibfk_4` FOREIGN KEY (`IdTipodeAcabado`) REFERENCES `TipodeAcabado` (`Id`);

--
-- Filtros para la tabla `StockDiagnostico`
--
ALTER TABLE `StockDiagnostico`
  ADD CONSTRAINT `sd_ibfk_1` FOREIGN KEY (`GeneradoPor`) REFERENCES `Usuario` (`Id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD CONSTRAINT `Usuario_ibfk_1` FOREIGN KEY (`IdTipodeUsuario`) REFERENCES `TipodeUsuario` (`Id`),
  ADD CONSTRAINT `Usuario_ibfk_2` FOREIGN KEY (`IdTipodeRol`) REFERENCES `TipodeRol` (`Id`),
  ADD CONSTRAINT `Usuario_ibfk_3` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`);

--
-- Filtros para la tabla `Venta`
--
ALTER TABLE `Venta`
  ADD CONSTRAINT `Venta_ibfk_1` FOREIGN KEY (`IdCarrito`) REFERENCES `Carrito` (`Id`),
  ADD CONSTRAINT `Venta_ibfk_2` FOREIGN KEY (`IdComprobante`) REFERENCES `Comprobante` (`Id`);

--
-- Filtros para la tabla `VistasDePagina`
--
ALTER TABLE `VistasDePagina`
  ADD CONSTRAINT `vistaspagina_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`Id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vistaspagina_ibfk_2` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`) ON DELETE SET NULL;
--
-- Claves foráneas de las tablas que el phpMyAdmin de InfinityFree exporta cortadas
--
ALTER TABLE `ResenaAnalisisIA`
  ADD CONSTRAINT `fk_ria_resena` FOREIGN KEY (`IdResena`) REFERENCES `Resena` (`Id`) ON DELETE CASCADE;

ALTER TABLE `ResenaHistorial`
  ADD CONSTRAINT `fk_rh_resena` FOREIGN KEY (`IdResena`) REFERENCES `Resena` (`Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
