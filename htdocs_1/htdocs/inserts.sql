USE if0_41556808_sanplacido;

-- ══ PAÍSES ═══════════════════════════════════════════════════════════════════
INSERT INTO Pais (Nombre) VALUES
('Argentina'),('Bolivia'),('Brasil'),('Chile'),('Colombia'),('Costa Rica'),
('Cuba'),('Ecuador'),('El Salvador'),('Guatemala'),('Honduras'),('México'),
('Nicaragua'),('Panamá'),('Paraguay'),('Perú'),('Puerto Rico'),
('República Dominicana'),('Uruguay'),('Venezuela'),('Belice'),('Haití');

-- ══ LOCALIDADES ══════════════════════════════════════════════════════════════
INSERT INTO Localidad (Nombre) VALUES
('Achiras'),('Adelia María'),('Agua de Oro'),('Alcira Gigena'),('Aldea Santa María'),
('Alejandro Roca'),('Alejo Ledesma'),('Alicia'),('Almafuerte'),('Alpa Corral'),
('Alta Gracia'),('Alto Alegre'),('Altos de Chipión'),('Amboy'),('Ambul'),
('Ana Zumarán'),('Anisacate'),('Arias'),('Arroyito'),('Arroyo Algodón'),
('Arroyo Cabral'),('Arroyo de Los Patos'),('Assunta'),('Atahona'),('Ausonia'),
('Avellaneda'),('Ballesteros'),('Ballesteros Sud'),('Balnearia'),('Bañado de Soto'),
('Bell Ville'),('Bengolea'),('Benjamín Gould'),('Berrotarán'),('Bialet Massé'),
('Bouwer'),('Brinkmann'),('Buchardo'),('Bulnes'),('Cabalango'),('Calchín'),
('Calchín Oeste'),('Camilo Aldao'),('Caminiaga'),('Canals'),('Candelaria Sud'),
('Cañada de Luque'),('Cañada de Machado'),('Cañada de Río Pinto'),('Cañada del Sauce'),
('Capilla de los Remedios'),('Capilla de Sitón'),('Capilla del Carmen'),('Capilla del Monte'),
('Carnerillo'),('Carrilobo'),('Casa Grande'),('Cavanagh'),('Cerro Colorado'),
('Chaján'),('Chalácea'),('Chancaní'),('Chañar Viejo'),('Charbonier'),('Charras'),
('Chazón'),('Chilibroste'),('Chucul'),('Chuña'),('Chuña Huasi'),('Churquí Cañada'),
('Ciénaga del Coro'),('Cintra'),('Colazo'),('Colonia Almada'),('Colonia Anita'),
('Colonia Barge'),('Colonia Bismarck'),('Colonia Bremen'),('Colonia Caroya'),
('Colonia Italiana'),('Colonia Iturraspe'),('Colonia Las Cuatro Esquinas'),
('Colonia Las Pichanas'),('Colonia Marina'),('Colonia Prosperidad'),
('Colonia San Bartolomé'),('Colonia San Pedro'),('Colonia Tirolesa'),
('Colonia Valtelina'),('Colonia Vicente Agüero'),('Colonia Videla'),
('Colonia Vignaud'),('Comechingones'),('Conlara'),('Copacabana'),('Córdoba'),
('Coronel Baigorria'),('Coronel Moldes'),('Corral de Bustos Ifflinger'),
('Corralito'),('Cosquín'),('Costa Sacate'),('Cruz Alta'),('Cruz de Caña'),
('Cruz del Eje'),('Cuesta Blanca'),('Dalmacio Vélez'),('Deán Funes'),
('Del Campillo'),('Despeñaderos'),('Devoto'),('Diego de Rojas'),('Dique Chico'),
('El Arañado'),('El Brete'),('El Chacho'),('El Crispín'),('El Fortín'),
('El Manzano'),('El Rastreador'),('El Rodeo'),('El Tío'),('Elena'),('Embalse'),
('Esquina'),('Estación General Paz'),('Estación Juárez Celman'),
('Estancia de Guadalupe'),('Estancia Vieja'),('Etruria'),('Eufrasio Loza'),
('Falda del Carmen'),('Freyre'),('General Baldissera'),('General Cabrera'),
('General Deheza'),('General Fotheringham'),('General Levalle'),('General Roca'),
('Guanaco Muerto'),('Guasapampa'),('Guatimozín'),('Gutemberg'),('Hernando'),
('Huinca Renancó'),('Idiazábal'),('Inriville'),('Isla Verde'),('James Crámer'),
('Jesús María'),('La Calera'),('La Carlota'),('La Cumbre'),('La Falda'),
('La Granja'),('La Laguna'),('Laboulaye'),('Las Bajadas'),('Las Caleras'),
('Las Varillas'),('Las Vertientes'),('Leones'),('Liu-Leu'),('Marcos Juárez'),
('Mendoza'),('Monte Buey'),('Morteros'),('Oliva'),('Oncativo'),('Pasco'),
('Pilar'),('Río Ceballos'),('Río Cuarto'),('Río Segundo'),('Río Tercero'),
('San Francisco'),('Salsipuedes'),('Santa María de Punilla'),('Unquillo'),
('Villa Allende'),('Villa Carlos Paz'),('Villa Del Río'),('Villa María'),
('Villa Nueva'),('Villa Dolores'),('Villa Santo Domingo'),('Villa Serranópolis'),
('Villa Valeria'),('Villa La Serranía'),('Villa General Belgrano'),
('Villa Los Reartes'),('Villa Córdoba'),('Villa Santa Rosa'),('Zurigh-Ville');

-- ══ TABLAS DE REFERENCIA ═════════════════════════════════════════════════════
INSERT INTO TipodeUsuario (Nombre) VALUES ('vendedor'),('cliente'),('administrador');
INSERT INTO TipodeRol (Nombre) VALUES ('gerente'),('cliente'),('repartidor'),('encargado'),('carpintero');
INSERT INTO TipodeDni (Nombre) VALUES ('DNI'),('Libreta'),('Pasaporte');
INSERT INTO TipoDomicilio (Nombre) VALUES ('Domicilio'),('Departamento'),('Barrio Privado');
INSERT INTO Categoria (Nombre) VALUES ('muebles interior'),('muebles cocina'),('placares'),('mesas de noche'),('aberturas interior'),('aberturas exterior'),('guarda ropas');

INSERT INTO TipodeProducto (Nombre) VALUES
('Mesa'),('Silla'),('Sillón'),('Sofá'),('Ropero'),('Cómoda'),('Biblioteca'),
('Escritorio'),('Cama'),('Cabecera'),('Mesa de luz'),('Mueble de TV'),
('Aparador'),('Buffet'),('Baúl'),('Estantería'),('Banqueta'),('Taburete'),
('Mueble de baño'),('Mueble de cocina');

INSERT INTO TipodeDiseño (Nombre) VALUES
('Clásico'),('Rústico'),('Moderno'),('Contemporáneo'),('Minimalista'),
('Industrial'),('Escandinavo'),('Provenzal'),('Colonial'),('Art Déco'),
('Vintage'),('Campestre');

INSERT INTO TipodeAcabado (Nombre) VALUES
('Lustrado'),('Barnizado'),('Laqueado'),('Pintado'),('Encerado'),
('Aceite de tung'),('Teñido'),('Natural sin tratamiento'),
('Poliuretano mate'),('Poliuretano brillante'),('Microcemento'),('Patinado');

INSERT INTO TipodeHerraje (Nombre, Descripcion) VALUES
('Sin herraje','Sin herraje adicional'),
('Bisagras ocultas','Bisagras europeas de cierre suave'),
('Bisagras vistas','Bisagras de hierro forjado o latón a la vista'),
('Correderas simples','Correderas metálicas para cajones'),
('Correderas soft-close','Correderas con cierre amortiguado'),
('Patas metálicas','Patas de hierro o acero, varios acabados'),
('Patas de madera','Patas torneadas o rectas en madera'),
('Tiradores hierro','Tiradores de hierro rústico o forjado'),
('Tiradores latón','Tiradores de latón dorado o envejecido'),
('Tiradores acero','Tiradores de acero inoxidable'),
('Ruedas','Ruedas con o sin freno para muebles móviles'),
('Herraje de cama','Herraje estructural para camas y sommiers');

INSERT INTO TipodeAlmacenamiento (Nombre, Descripcion) VALUES
('Sin almacenamiento','El producto no tiene espacio de almacenamiento'),
('Cajones','Uno o más cajones deslizantes'),
('Puertas con estantes','Puertas que ocultan estantes internos'),
('Puertas sin estantes','Puertas que ocultan espacio libre'),
('Estantes abiertos','Estantes a la vista sin puertas'),
('Baúl / contenedor','Tapa abatible con espacio interior amplio'),
('Cajones + puertas','Combinación de cajones y puertas'),
('Perchero','Barra o ganchos para colgar ropa'),
('Perchero + cajones','Barra para colgar más cajones inferiores'),
('Zapatera','Espacio diseñado para guardar calzado'),
('Vitrina','Puertas de vidrio para exhibir objetos');

INSERT INTO TipodeMadera (Nombre) VALUES
('Roble'),('Algarrobo'),('Cedro'),('Pino'),('Nogal'),('Guatambú'),
('Lapacho'),('Eucaliptus'),('Paraíso'),('Fresno'),('Teca'),
('MDF'),('Aglomerado'),('Multilaminado / Terciado'),('OSB');

INSERT INTO TipodeMaterial (Nombre) VALUES
('Adhesivo'),('Abrasivo'),('Protector / Acabado'),('Fijación'),
('Relleno / Masilla'),('Sellador'),('Tinte / Colorante'),('Limpieza'),
('Tapizado'),('Vidrio'),('Metal'),('Plástico / PVC');

INSERT INTO TipodeCorte (Nombre) VALUES
('Sin corte'),('Recto'),('Diagonal / Ingleteado'),('Curvo / Fresado'),
('Canaleta'),('Machimbre'),('Espiga'),('Caja y espiga'),
('Dovetail / Cola de milano'),('A medida del cliente');

INSERT INTO EstadodePago (Nombre) VALUES ('Pendiente'),('Aprobado'),('Rechazado');
INSERT INTO TipodePago (Nombre) VALUES ('Efectivo'),('Tarjeta de Crédito'),('Tarjeta de Débito'),('Transferencia');
INSERT INTO TipodeEntrega (Nombre) VALUES ('Retiro en local'),('Envío a domicilio');
INSERT INTO EstadosdeEntrega (Nombre) VALUES ('Pendiente'),('En preparación'),('En camino'),('Entregado'),('Cancelado');

-- ══ MADERAS ══════════════════════════════════════════════════════════════════
INSERT INTO Maderas (CantidadStock, Alto, Largo, Ancho, PrecioUnitario, IdTipodeMadera) VALUES
(20,3.00,300.00,15.00,4800.00,1),(15,3.00,300.00,20.00,6200.00,1),(10,5.00,300.00,10.00,2900.00,1),
(25,3.00,300.00,15.00,5200.00,2),(12,5.00,300.00,10.00,3100.00,2),(8,3.00,250.00,25.00,8500.00,2),
(30,2.00,300.00,10.00,2400.00,3),(18,3.00,300.00,15.00,3800.00,3),
(50,2.00,300.00,10.00,1200.00,4),(40,3.00,300.00,15.00,1800.00,4),(35,2.00,300.00,20.00,2200.00,4),(20,1.50,240.00,120.00,9500.00,4),
(10,3.00,300.00,15.00,7800.00,5),(6,4.00,300.00,10.00,5600.00,5),
(30,1.80,244.00,122.00,8200.00,12),(25,1.50,244.00,122.00,6800.00,12),(20,0.60,244.00,122.00,3200.00,12),
(20,1.80,244.00,122.00,5400.00,13),(15,1.50,244.00,122.00,4600.00,13),
(15,1.50,244.00,122.00,7200.00,14),(12,1.80,244.00,122.00,8900.00,14),(25,0.40,244.00,122.00,2800.00,14);

-- ══ INSUMOS ═══════════════════════════════════════════════════════════════════
INSERT INTO InsumosdeCarpinteria (PrecioUniatrio, Cantidad, Descripcion, IdTipodeMaterial, IdTipodeCorte) VALUES
(850.00,50,'Cola vinílica 1 kg',1,1),(1200.00,30,'Cola de contacto 1 lt',1,1),(650.00,40,'Pegamento epoxi bicomponente',1,1),
(120.00,100,'Lija al agua grano 80',2,1),(120.00,100,'Lija al agua grano 120',2,1),(120.00,100,'Lija al agua grano 180',2,1),(120.00,100,'Lija al agua grano 220',2,1),(350.00,30,'Lija de banda grano 80',2,1),(350.00,30,'Lija de banda grano 120',2,1),
(2800.00,20,'Barniz marino brillante 1 lt',3,1),(2800.00,20,'Barniz marino mate 1 lt',3,1),(3200.00,15,'Laca poliuretano brillante 1 lt',3,1),(3200.00,15,'Laca poliuretano mate 1 lt',3,1),(1800.00,25,'Aceite de tung 1 lt',3,1),(900.00,30,'Cera para madera 500 g',3,1),(2500.00,18,'Fondo para madera 1 lt',3,1),
(180.00,200,'Tornillo Spax 3.5x35 (caja x50)',4,1),(220.00,200,'Tornillo Spax 4x50 (caja x50)',4,1),(150.00,300,'Clavo sin cabeza 40mm (caja x100)',4,1),(380.00,100,'Tirafondo 6x80 (caja x25)',4,1),(90.00,500,'Taco plástico 6mm (bolsa x50)',4,1),(250.00,100,'Tornillo para aglomerado 4x40',4,1),
(650.00,40,'Masilla para madera 500 g roble',5,1),(650.00,40,'Masilla para madera 500 g pino',5,1),(650.00,40,'Masilla para madera 500 g nogal',5,1),(480.00,50,'Sellador de poros 500 ml',5,1),
(1500.00,20,'Sellador fondo laca 1 lt',6,1),(1200.00,25,'Imprimación para madera 1 lt',6,1),
(850.00,30,'Tinte al agua roble 500 ml',7,1),(850.00,30,'Tinte al agua nogal 500 ml',7,1),(850.00,30,'Tinte al agua caoba 500 ml',7,1),(850.00,30,'Tinte al agua cedro 500 ml',7,1),(850.00,30,'Tinte al agua wengué 500 ml',7,1),(850.00,30,'Tinte al agua ebony 500 ml',7,1),
(4500.00,10,'Tela de tapicería lisa por metro',9,1),(6500.00,8,'Cuero ecológico por metro',9,1),(1200.00,20,'Guata relleno 500 g',9,1),(980.00,15,'Goma espuma 3 cm por metro²',9,1),
(8500.00,5,'Vidrio float 4mm por m²',10,1),(12000.00,3,'Vidrio templado 6mm por m²',10,1),
(350.00,50,'Ángulo metálico refuerzo 40mm',11,1),(280.00,80,'Escuadra metálica 50x50mm',11,1),(1200.00,20,'Perfil de aluminio 2 m',11,1),(180.00,100,'Tarugos de madera 8mm (x10)',4,1);

-- ══ USUARIO ADMINISTRADOR ════════════════════════════════════════════════════
INSERT INTO Domicilio (Calle, Numero, Country, Departamento, Barrio, IdTipoDomicilio, Piso, numeroPiso)
VALUES ('Belgrano', 742, NULL, NULL, 'Nueva Córdoba', 1, NULL, NULL);
SET @id_domicilio := LAST_INSERT_ID();

INSERT INTO Clientes (DNI, Nombre, Apellido, Telefono, IdLocalidad, IdTipodeDni, IdDomicilio, IdTipodomicilio)
VALUES ('46000999', 'Lucía', 'Fernández', '3516001234',
    (SELECT Id FROM Localidad WHERE Nombre = 'Córdoba' LIMIT 1),
    (SELECT Id FROM TipodeDni WHERE Nombre = 'DNI' LIMIT 1),
    @id_domicilio,
    (SELECT Id FROM TipoDomicilio WHERE Nombre = 'Domicilio' LIMIT 1)
);
SET @id_cliente := LAST_INSERT_ID();

INSERT INTO Usuario (NombredeUsuario, Contraseña, CorreoElectronico, Restablecer, Confirmado, Token, IdTipodeUsuario, IdTipodeRol, IdCliente)
VALUES (
    'gino_admin',
    '$2y$10$NJqMee14zZe0szZVFk4Yl.rwKzyBRUmvd6.yu1Oy.Wf1Ch7TWr41i',
    'lucia.fernandez@example.com',
    NULL, 1, NULL,
    (SELECT Id FROM TipodeUsuario WHERE Nombre = 'administrador' LIMIT 1),
    (SELECT Id FROM TipodeRol WHERE Nombre = 'gerente' LIMIT 1),
    @id_cliente
);