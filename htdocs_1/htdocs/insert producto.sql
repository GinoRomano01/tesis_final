-- ============================================================
--  INSERTS - Tablas de referencia para el formulario Producto
--  San Plácido - Carpintería
-- ============================================================

-- ── TipodeProducto ──────────────────────────────────────────
INSERT INTO TipodeProducto (Nombre) VALUES
('Mesa'),
('Silla'),
('Sillón'),
('Sofá'),
('Ropero'),
('Cómoda'),
('Biblioteca'),
('Escritorio'),
('Cama'),
('Cabecera'),
('Mesa de luz'),
('Mueble de TV'),
('Aparador'),
('Buffet'),
('Baúl'),
('Estantería'),
('Banqueta'),
('Taburete'),
('Mueble de baño'),
('Mueble de cocina');

-- ── TipodeDiseño ────────────────────────────────────────────
INSERT INTO TipodeDiseño (Nombre) VALUES
('Clásico'),
('Rústico'),
('Moderno'),
('Contemporáneo'),
('Minimalista'),
('Industrial'),
('Escandinavo'),
('Provenzal'),
('Colonial'),
('Art Déco'),
('Vintage'),
('Campestre');

-- ── TipodeAcabado ───────────────────────────────────────────
INSERT INTO TipodeAcabado (Nombre) VALUES
('Lustrado'),
('Barnizado'),
('Laqueado'),
('Pintado'),
('Encerado'),
('Aceite de tung'),
('Teñido'),
('Natural sin tratamiento'),
('Poliuretano mate'),
('Poliuretano brillante'),
('Microcemento'),
('Patinado');

-- ── TipodeHerraje ───────────────────────────────────────────
INSERT INTO TipodeHerraje (Nombre, Descripcion) VALUES
('Sin herraje',       'Sin herraje adicional'),
('Bisagras ocultas',  'Bisagras europeas de cierre suave'),
('Bisagras vistas',   'Bisagras de hierro forjado o latón a la vista'),
('Correderas simples','Correderas metálicas para cajones'),
('Correderas soft-close', 'Correderas con cierre amortiguado'),
('Patas metálicas',   'Patas de hierro o acero, varios acabados'),
('Patas de madera',   'Patas torneadas o rectas en madera'),
('Tiradores hierro',  'Tiradores de hierro rústico o forjado'),
('Tiradores latón',   'Tiradores de latón dorado o envejecido'),
('Tiradores acero',   'Tiradores de acero inoxidable'),
('Ruedas',            'Ruedas con o sin freno para muebles móviles'),
('Herraje de cama',   'Herraje estructural para camas y sommiers');

-- ── TipodeAlmacenamiento ────────────────────────────────────
INSERT INTO TipodeAlmacenamiento (Nombre, Descripcion) VALUES
('Sin almacenamiento',    'El producto no tiene espacio de almacenamiento'),
('Cajones',               'Uno o más cajones deslizantes'),
('Puertas con estantes',  'Puertas que ocultan estantes internos'),
('Puertas sin estantes',  'Puertas que ocultan espacio libre'),
('Estantes abiertos',     'Estantes a la vista sin puertas'),
('Baúl / contenedor',     'Tapa abatible con espacio interior amplio'),
('Cajones + puertas',     'Combinación de cajones y puertas'),
('Perchero',              'Barra o ganchos para colgar ropa'),
('Perchero + cajones',    'Barra para colgar más cajones inferiores'),
('Zapatera',              'Espacio diseñado para guardar calzado'),
('Vitrina',               'Puertas de vidrio para exhibir objetos');




-- ============================================================
--  INSERTS - Maderas e Insumos de Carpintería
--  San Plácido - Carpintería
--  Orden: primero tablas de tipos (FK), luego las principales
-- ============================================================

-- ── TipodeMadera ────────────────────────────────────────────
INSERT INTO TipodeMadera (Nombre) VALUES
('Roble'),
('Algarrobo'),
('Cedro'),
('Pino'),
('Nogal'),
('Guatambú'),
('Lapacho'),
('Eucaliptus'),
('Paraíso'),
('Fresno'),
('Teca'),
('MDF'),
('Aglomerado'),
('Multilaminado / Terciado'),
('OSB');

-- ── TipodeMaterial ──────────────────────────────────────────
INSERT INTO TipodeMaterial (Nombre) VALUES
('Adhesivo'),
('Abrasivo'),
('Protector / Acabado'),
('Fijación'),
('Relleno / Masilla'),
('Sellador'),
('Tinte / Colorante'),
('Limpieza'),
('Tapizado'),
('Vidrio'),
('Metal'),
('Plástico / PVC');

-- ── TipodeCorte ─────────────────────────────────────────────
INSERT INTO TipodeCorte (Nombre) VALUES
('Sin corte'),
('Recto'),
('Diagonal / Ingleteado'),
('Curvo / Fresado'),
('Canaleta'),
('Machimbre'),
('Espiga'),
('Caja y espiga'),
('Dovetail / Cola de milano'),
('A medida del cliente');

-- ── Maderas ─────────────────────────────────────────────────
-- Columnas reales: CantidadStock, Alto, Largo, Ancho, IdTipodeMadera
-- Alto / Largo / Ancho en cm. Stock en unidades de esa medida.

INSERT INTO Maderas 
(CantidadStock, Alto, Largo, Ancho, PrecioUnitario, IdTipodeMadera) 
VALUES

-- Roble (IdTipodeMadera = 1)
(20, 3.00, 300.00, 15.00, 4800.00, 1),
(15, 3.00, 300.00, 20.00, 6200.00, 1),
(10, 5.00, 300.00, 10.00, 2900.00, 1),

-- Algarrobo (2)
(25, 3.00, 300.00, 15.00, 5200.00, 2),
(12, 5.00, 300.00, 10.00, 3100.00, 2),
(8,  3.00, 250.00, 25.00, 8500.00, 2),

-- Cedro (3)
(30, 2.00, 300.00, 10.00, 2400.00, 3),
(18, 3.00, 300.00, 15.00, 3800.00, 3),

-- Pino (4)
(50, 2.00, 300.00, 10.00, 1200.00, 4),
(40, 3.00, 300.00, 15.00, 1800.00, 4),
(35, 2.00, 300.00, 20.00, 2200.00, 4),
(20, 1.50, 240.00, 120.00, 9500.00, 4),

-- Nogal (5)
(10, 3.00, 300.00, 15.00, 7800.00, 5),
(6,  4.00, 300.00, 10.00, 5600.00, 5),

-- MDF (12)
(30, 1.80, 244.00, 122.00, 8200.00, 12),
(25, 1.50, 244.00, 122.00, 6800.00, 12),
(20, 0.60, 244.00, 122.00, 3200.00, 12),

-- Aglomerado (13)
(20, 1.80, 244.00, 122.00, 5400.00, 13),
(15, 1.50, 244.00, 122.00, 4600.00, 13),

-- Multilaminado / Terciado (14)
(15, 1.50, 244.00, 122.00, 7200.00, 14),
(12, 1.80, 244.00, 122.00, 8900.00, 14),
(25, 0.40, 244.00, 122.00, 2800.00, 14);

-- ── InsumosdeCarpinteria ─────────────────────────────────────
-- Columnas reales: PrecioUniatrio (typo del schema), Cantidad,
--                  Descripcion, IdTipodeMaterial, IdTipodeCorte

INSERT INTO InsumosdeCarpinteria (PrecioUniatrio, Cantidad, Descripcion, IdTipodeMaterial, IdTipodeCorte) VALUES
-- ADHESIVOS (TipodeMaterial 1)
(850.00,  50, 'Cola vinílica 1 kg',                1, 1),
(1200.00, 30, 'Cola de contacto 1 lt',             1, 1),
(650.00,  40, 'Pegamento epoxi bicomponente',       1, 1),

-- ABRASIVOS (TipodeMaterial 2)
(120.00, 100, 'Lija al agua grano 80',             2, 1),
(120.00, 100, 'Lija al agua grano 120',            2, 1),
(120.00, 100, 'Lija al agua grano 180',            2, 1),
(120.00, 100, 'Lija al agua grano 220',            2, 1),
(350.00,  30, 'Lija de banda grano 80',            2, 1),
(350.00,  30, 'Lija de banda grano 120',           2, 1),

-- PROTECTORES / ACABADOS (TipodeMaterial 3)
(2800.00, 20, 'Barniz marino brillante 1 lt',      3, 1),
(2800.00, 20, 'Barniz marino mate 1 lt',           3, 1),
(3200.00, 15, 'Laca poliuretano brillante 1 lt',   3, 1),
(3200.00, 15, 'Laca poliuretano mate 1 lt',        3, 1),
(1800.00, 25, 'Aceite de tung 1 lt',               3, 1),
(900.00,  30, 'Cera para madera 500 g',            3, 1),
(2500.00, 18, 'Fondo para madera 1 lt',            3, 1),

-- FIJACIÓN (TipodeMaterial 4)
(180.00, 200, 'Tornillo Spax 3.5x35 (caja x50)',   4, 1),
(220.00, 200, 'Tornillo Spax 4x50 (caja x50)',     4, 1),
(150.00, 300, 'Clavo sin cabeza 40mm (caja x100)', 4, 1),
(380.00, 100, 'Tirafondo 6x80 (caja x25)',         4, 1),
(90.00,  500, 'Taco plástico 6mm (bolsa x50)',     4, 1),
(250.00, 100, 'Tornillo para aglomerado 4x40',     4, 1),

-- RELLENO / MASILLA (TipodeMaterial 5)
(650.00,  40, 'Masilla para madera 500 g roble',   5, 1),
(650.00,  40, 'Masilla para madera 500 g pino',    5, 1),
(650.00,  40, 'Masilla para madera 500 g nogal',   5, 1),
(480.00,  50, 'Sellador de poros 500 ml',          5, 1),

-- SELLADOR (TipodeMaterial 6)
(1500.00, 20, 'Sellador fondo laca 1 lt',          6, 1),
(1200.00, 25, 'Imprimación para madera 1 lt',      6, 1),

-- TINTE / COLORANTE (TipodeMaterial 7)
(850.00,  30, 'Tinte al agua roble 500 ml',        7, 1),
(850.00,  30, 'Tinte al agua nogal 500 ml',        7, 1),
(850.00,  30, 'Tinte al agua caoba 500 ml',        7, 1),
(850.00,  30, 'Tinte al agua cedro 500 ml',        7, 1),
(850.00,  30, 'Tinte al agua wengué 500 ml',       7, 1),
(850.00,  30, 'Tinte al agua ebony 500 ml',        7, 1),

-- TAPIZADO (TipodeMaterial 9)
(4500.00, 10, 'Tela de tapicería lisa por metro',  9, 1),
(6500.00,  8, 'Cuero ecológico por metro',         9, 1),
(1200.00, 20, 'Guata relleno 500 g',               9, 1),
(980.00,  15, 'Goma espuma 3 cm por metro²',       9, 1),

-- VIDRIO (TipodeMaterial 10)
(8500.00,  5, 'Vidrio float 4mm por m²',          10, 1),
(12000.00, 3, 'Vidrio templado 6mm por m²',       10, 1),

-- METAL (TipodeMaterial 11)
(350.00,  50, 'Ángulo metálico refuerzo 40mm',    11, 1),
(280.00,  80, 'Escuadra metálica 50x50mm',        11, 1),
(1200.00, 20, 'Perfil de aluminio 2 m',           11, 1),
(180.00, 100, 'Tarugos de madera 8mm (x10)',       4, 1);


