-- =============================================================================
-- MÓDULO DE ESTADÍSTICAS — SanPlacido
-- Integración con la base de datos existente (sanplacido)
-- =============================================================================

USE sanplacido;

-- -----------------------------------------------------------------------------
-- 1. VISTAS_DE_PAGINA
--    Registra cada visita a una URL del sitio.
--    Equivale a "page_views" del diagrama.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `VistasDePagina` (
  `Id`              INT(11) NOT NULL AUTO_INCREMENT,
  `UrlVisitada`     VARCHAR(500) NOT NULL                COMMENT 'URL completa visitada',
  `Titulo`          VARCHAR(200) DEFAULT NULL            COMMENT 'document.title de la página',
  `Referidor`       VARCHAR(500) DEFAULT NULL            COMMENT 'document.referrer (origen)',
  `IdUsuario`       INT(11) DEFAULT NULL                 COMMENT 'FK Usuario.Id — NULL si anónimo',
  `IdCliente`       INT(11) DEFAULT NULL                 COMMENT 'FK Clientes.Id — NULL si anónimo',
  `SesionId`        VARCHAR(100) DEFAULT NULL            COMMENT 'session_id() del servidor PHP',
  `DispositivoTipo` VARCHAR(20) DEFAULT NULL             COMMENT 'mobile / tablet / desktop',
  `Navegador`       VARCHAR(100) DEFAULT NULL            COMMENT 'User-Agent simplificado',
  `IpHash`          VARCHAR(64) DEFAULT NULL             COMMENT 'SHA-256 de IP (privacidad)',
  `TiempoEnPagina`  INT(11) DEFAULT NULL                 COMMENT 'Segundos en la página (beacon)',
  `FechaRegistro`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `idx_vistas_usuario`  (`IdUsuario`),
  KEY `idx_vistas_cliente`  (`IdCliente`),
  KEY `idx_vistas_fecha`    (`FechaRegistro`),
  KEY `idx_vistas_url`      (`UrlVisitada`(191)),
  CONSTRAINT `vistaspagina_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario`  (`Id`) ON DELETE SET NULL,
  CONSTRAINT `vistaspagina_ibfk_2` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- -----------------------------------------------------------------------------
-- 2. EVENTOS_DE_USUARIO
--    Captura acciones específicas: clics, búsquedas, add-to-cart, etc.
--    Equivale a "user_events" del diagrama.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `EventosDeUsuario` (
  `Id`            INT(11) NOT NULL AUTO_INCREMENT,
  `TipoEvento`    VARCHAR(50) NOT NULL                  COMMENT 'page_view | clic | registro | login | logout | add_carrito | busqueda | checkout_paso | compra',
  `Modulo`        VARCHAR(50) DEFAULT NULL              COMMENT 'home | producto | carrito | checkout | login | admin | stock',
  `ElementoId`    VARCHAR(100) DEFAULT NULL             COMMENT 'ID del elemento clickeado (p.ej. id del producto)',
  `ElementoTipo`  VARCHAR(50) DEFAULT NULL              COMMENT 'producto | categoria | boton | enlace | formulario',
  `ValorExtra`    VARCHAR(500) DEFAULT NULL             COMMENT 'JSON libre: {precio, query, paso, etc.}',
  `IdUsuario`     INT(11) DEFAULT NULL                  COMMENT 'FK Usuario.Id',
  `IdCliente`     INT(11) DEFAULT NULL                  COMMENT 'FK Clientes.Id',
  `SesionId`      VARCHAR(100) DEFAULT NULL,
  `IpHash`        VARCHAR(64) DEFAULT NULL,
  `FechaRegistro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `idx_eventos_tipo`    (`TipoEvento`),
  KEY `idx_eventos_usuario` (`IdUsuario`),
  KEY `idx_eventos_cliente` (`IdCliente`),
  KEY `idx_eventos_fecha`   (`FechaRegistro`),
  KEY `idx_eventos_modulo`  (`Modulo`),
  CONSTRAINT `eventosUsuario_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario`  (`Id`) ON DELETE SET NULL,
  CONSTRAINT `eventosUsuario_ibfk_2` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- -----------------------------------------------------------------------------
-- 3. BUSQUEDAS
--    Registra cada búsqueda realizada en el catálogo.
--    Equivale a "searches" del diagrama.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `Busquedas` (
  `Id`              INT(11) NOT NULL AUTO_INCREMENT,
  `TerminoBuscado`  VARCHAR(300) NOT NULL               COMMENT 'Texto ingresado por el usuario',
  `CantidadResultados` INT(11) DEFAULT NULL             COMMENT 'Cuántos productos devolvió',
  `IdUsuario`       INT(11) DEFAULT NULL,
  `IdCliente`       INT(11) DEFAULT NULL,
  `SesionId`        VARCHAR(100) DEFAULT NULL,
  `FechaRegistro`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `idx_busquedas_termino` (`TerminoBuscado`(100)),
  KEY `idx_busquedas_fecha`   (`FechaRegistro`),
  CONSTRAINT `busquedas_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario`  (`Id`) ON DELETE SET NULL,
  CONSTRAINT `busquedas_ibfk_2` FOREIGN KEY (`IdCliente`) REFERENCES `Clientes` (`Id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- -----------------------------------------------------------------------------
-- 4. RESUMEN_DE_VENTAS
--    Resumen diario pre-calculado de ventas (para dashboards rápidos).
--    Equivale a "sales_summary" del diagrama.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ResumenDeVentas` (
  `Id`                  INT(11) NOT NULL AUTO_INCREMENT,
  `Fecha`               DATE NOT NULL                   COMMENT 'Día del resumen (sin hora)',
  `CantidadOrdenes`     INT(11) NOT NULL DEFAULT 0      COMMENT 'Total de ventas ese día',
  `MontoTotal`          DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Suma de MontoTotal de FacturaCliente',
  `MontoPromedio`       DECIMAL(10,2) DEFAULT NULL      COMMENT 'Ticket promedio del día',
  `CantidadProductos`   INT(11) NOT NULL DEFAULT 0      COMMENT 'Unidades vendidas',
  `VentasAprobadas`     INT(11) NOT NULL DEFAULT 0,
  `VentasPendientes`    INT(11) NOT NULL DEFAULT 0,
  `VentasRechazadas`    INT(11) NOT NULL DEFAULT 0,
  `ClientesNuevos`      INT(11) NOT NULL DEFAULT 0      COMMENT 'Clientes registrados ese día',
  `VisitasTotales`      INT(11) NOT NULL DEFAULT 0      COMMENT 'Vistas de página ese día',
  `FechaActualizacion`  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uk_resumen_fecha` (`Fecha`),
  KEY `idx_resumen_fecha` (`Fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- -----------------------------------------------------------------------------
-- STORED PROCEDURE: Recalcular ResumenDeVentas para un día dado
-- Llamar diariamente con un cron: CALL sp_RecalcularResumen(CURDATE() - INTERVAL 1 DAY);
-- -----------------------------------------------------------------------------
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_RecalcularResumen`$$

CREATE PROCEDURE `sp_RecalcularResumen`(IN p_Fecha DATE)
BEGIN
  DECLARE v_ordenes     INT DEFAULT 0;
  DECLARE v_monto       DECIMAL(12,2) DEFAULT 0;
  DECLARE v_promedio    DECIMAL(10,2) DEFAULT 0;
  DECLARE v_productos   INT DEFAULT 0;
  DECLARE v_aprobadas   INT DEFAULT 0;
  DECLARE v_pendientes  INT DEFAULT 0;
  DECLARE v_rechazadas  INT DEFAULT 0;
  DECLARE v_clientes    INT DEFAULT 0;
  DECLARE v_visitas     INT DEFAULT 0;

  -- Ventas del día desde FacturaCliente + EstadoDePago
  SELECT
    COUNT(fc.Id),
    COALESCE(SUM(fc.MontoTotal), 0),
    COALESCE(AVG(fc.MontoTotal), 0),
    SUM(CASE WHEN ep.Nombre = 'Aprobado'   THEN 1 ELSE 0 END),
    SUM(CASE WHEN ep.Nombre = 'Pendiente'  THEN 1 ELSE 0 END),
    SUM(CASE WHEN ep.Nombre = 'Rechazado'  THEN 1 ELSE 0 END)
  INTO v_ordenes, v_monto, v_promedio, v_aprobadas, v_pendientes, v_rechazadas
  FROM FacturaCliente fc
  LEFT JOIN EstadoDePago ep ON ep.Id = fc.IdEstadoDePago
  WHERE DATE(fc.FechaDeEmision) = p_Fecha
    AND fc.FechaBorrado IS NULL;

  -- Unidades vendidas (desde Venta.CantidadTotal)
  SELECT COALESCE(SUM(v.CantidadTotal), 0)
  INTO v_productos
  FROM Venta v
  JOIN FacturaCliente fc ON fc.Id = v.IdFacturaCliente
  WHERE DATE(fc.FechaDeEmision) = p_Fecha
    AND v.FechaBorrado IS NULL;

  -- Clientes que hicieron su primera compra ese día (proxy de "nuevo cliente activo")
  -- Nota: la tabla Usuario no tiene FechaCreacion; usamos la primera FacturaCliente
  --       del cliente como señal de alta. Si agregás FechaCreacion a Usuario,
  --       reemplazá esta query por: WHERE DATE(FechaCreacion) = p_Fecha
  SELECT COUNT(DISTINCT fc.IdClientes)
  INTO v_clientes
  FROM FacturaCliente fc
  WHERE fc.FechaBorrado IS NULL
    AND DATE(fc.FechaDeEmision) = p_Fecha
    AND fc.IdClientes NOT IN (
      SELECT IdClientes
      FROM FacturaCliente
      WHERE FechaBorrado IS NULL
        AND FechaDeEmision < p_Fecha
        AND IdClientes IS NOT NULL
    );

  -- Visitas del día
  SELECT COUNT(*)
  INTO v_visitas
  FROM VistasDePagina
  WHERE DATE(FechaRegistro) = p_Fecha;

  -- INSERT ... ON DUPLICATE KEY UPDATE para idempotencia
  INSERT INTO ResumenDeVentas
    (Fecha, CantidadOrdenes, MontoTotal, MontoPromedio, CantidadProductos,
     VentasAprobadas, VentasPendientes, VentasRechazadas, VisitasTotales)
  VALUES
    (p_Fecha, v_ordenes, v_monto, v_promedio, v_productos,
     v_aprobadas, v_pendientes, v_rechazadas, v_visitas)
  ON DUPLICATE KEY UPDATE
    CantidadOrdenes   = VALUES(CantidadOrdenes),
    MontoTotal        = VALUES(MontoTotal),
    MontoPromedio     = VALUES(MontoPromedio),
    CantidadProductos = VALUES(CantidadProductos),
    VentasAprobadas   = VALUES(VentasAprobadas),
    VentasPendientes  = VALUES(VentasPendientes),
    VentasRechazadas  = VALUES(VentasRechazadas),
    VisitasTotales    = VALUES(VisitasTotales);
END$$

DELIMITER ;
