CREATE DATABASE IF NOT EXISTS MOTOREPUESTOSROJAS;
USE MOTOREPUESTOSROJAS;


DROP TABLE IF EXISTS CITAS;
DROP TABLE IF EXISTS FACTURAS;
DROP TABLE IF EXISTS DETALLE_PRODUCTOS_PEDIDOS;
DROP TABLE IF EXISTS PEDIDOS;
DROP TABLE IF EXISTS USUARIOS;
DROP TABLE IF EXISTS INVENTARIO;
DROP TABLE IF EXISTS PRODUCTOS;
DROP TABLE IF EXISTS CATEGORIAS;
DROP TABLE IF EXISTS PROVEEDORES;

CREATE TABLE PROVEEDORES (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    telefono VARCHAR(20),
    correo VARCHAR(255),
    direccion VARCHAR(500),
    metodo_pago VARCHAR(100),
    estado BIT DEFAULT 1
);

CREATE TABLE CATEGORIAS (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion VARCHAR(500),
    estado BIT DEFAULT 1
);


CREATE TABLE PRODUCTOS (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    marca VARCHAR(100),
    costo_unitario DECIMAL(10, 2),
    precio_venta DECIMAL(10, 2),
    estado BIT DEFAULT 1,
    stock INT DEFAULT 0,
    id_proveedor INT DEFAULT NULL,
    id_categoria INT DEFAULT NULL,
    FOREIGN KEY (id_proveedor) REFERENCES PROVEEDORES(id_proveedor) ON DELETE SET NULL,
    FOREIGN KEY (id_categoria) REFERENCES CATEGORIAS(id_categoria) ON DELETE SET NULL
);


CREATE TABLE INVENTARIO (
    id_inventario INT AUTO_INCREMENT PRIMARY KEY,
    cantidad_disponible INT,
    stock_minimo INT,
    stock_total INT,
    estado BIT DEFAULT 1,
    id_producto INT NOT NULL UNIQUE,
    FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto)
);

CREATE TABLE USUARIOS (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol BIT DEFAULT 0,
    estado BIT DEFAULT 1
);

CREATE TABLE PEDIDOS (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATETIME,
    direccion VARCHAR(500),
    total DECIMAL(10, 2),
    estado VARCHAR(50) DEFAULT 'Pendiente',
    id_cliente INT DEFAULT NULL,
    FOREIGN KEY (id_cliente) REFERENCES USUARIOS(id_cliente) ON DELETE SET NULL
);

CREATE TABLE DETALLE_PRODUCTOS_PEDIDOS (
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    precio DECIMAL(10, 2),
    sub_total DECIMAL(10, 2),
    cantidad INT,
    PRIMARY KEY (id_pedido, id_producto),
    FOREIGN KEY (id_pedido) REFERENCES PEDIDOS(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto)
);

CREATE TABLE FACTURAS (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    fecha_factura DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2),
    metodo_pago VARCHAR(100),
    estado BIT DEFAULT 1,
    id_pedido INT NOT NULL UNIQUE,
    FOREIGN KEY (id_pedido) REFERENCES PEDIDOS(id_pedido)
);

CREATE TABLE CITAS (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE,
    hora TIME,
    motivo VARCHAR(500),
    estado VARCHAR(50) DEFAULT 'Programada',
    id_cliente INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES USUARIOS(id_cliente) ON DELETE CASCADE
);






-- PROVEEDORES
INSERT INTO PROVEEDORES (nombre, telefono, correo, direccion, metodo_pago, estado) VALUES
('Proveedor Uno', '22220001', 'prov1@email.com', 'Calle 1, Ciudad', 'Transferencia', 1),
('Proveedor Dos', '22220002', 'prov2@email.com', 'Calle 2, Ciudad', 'Efectivo', 1),
('Proveedor Tres', '22220003', 'prov3@email.com', 'Calle 3, Ciudad', 'Transferencia', 1),
('Proveedor Cuatro', '22220004', 'prov4@email.com', 'Calle 4, Ciudad', 'Efectivo', 1),
('Proveedor Cinco', '22220005', 'prov5@email.com', 'Calle 5, Ciudad', 'Transferencia', 1),
('Proveedor Seis', '22220006', 'prov6@email.com', 'Calle 6, Ciudad', 'Efectivo', 1),
('Proveedor Siete', '22220007', 'prov7@email.com', 'Calle 7, Ciudad', 'Transferencia', 1),
('Proveedor Ocho', '22220008', 'prov8@email.com', 'Calle 8, Ciudad', 'Efectivo', 1),
('Proveedor Nueve', '22220009', 'prov9@email.com', 'Calle 9, Ciudad', 'Transferencia', 1),
('Proveedor Diez', '22220010', 'prov10@email.com', 'Calle 10, Ciudad', 'Efectivo', 1),
('Proveedor Once', '22220011', 'prov11@email.com', 'Calle 11, Ciudad', 'Transferencia', 1),
('Proveedor Doce', '22220012', 'prov12@email.com', 'Calle 12, Ciudad', 'Efectivo', 1),
('Proveedor Trece', '22220013', 'prov13@email.com', 'Calle 13, Ciudad', 'Transferencia', 1),
('Proveedor Catorce', '22220014', 'prov14@email.com', 'Calle 14, Ciudad', 'Efectivo', 1),
('Proveedor Quince', '22220015', 'prov15@email.com', 'Calle 15, Ciudad', 'Transferencia', 1),
('Proveedor Dieciséis', '22220016', 'prov16@email.com', 'Calle 16, Ciudad', 'Efectivo', 1),
('Proveedor Diecisiete', '22220017', 'prov17@email.com', 'Calle 17, Ciudad', 'Transferencia', 1),
('Proveedor Dieciocho', '22220018', 'prov18@email.com', 'Calle 18, Ciudad', 'Efectivo', 1),
('Proveedor Diecinueve', '22220019', 'prov19@email.com', 'Calle 19, Ciudad', 'Transferencia', 1),
('Proveedor Veinte', '22220020', 'prov20@email.com', 'Calle 20, Ciudad', 'Efectivo', 1);

-- CATEGORIAS
INSERT INTO CATEGORIAS (nombre, descripcion, estado) VALUES
('Filtros', 'Filtros de aceite, aire y combustible', 1),
('Aceites y Lubricantes', 'Aceites de motor, grasa y lubricantes', 1),
('Frenos', 'Pastillas, discos y kits de freno', 1),
('Suspensión', 'Amortiguadores, resortes y kits de suspensión', 1),
('Correas y Cadenas', 'Correas de distribución y accesorios', 1),
('Baterías', 'Baterías y acumuladores', 1),
('Iluminación', 'Faros, lámparas y luces auxiliares', 1),
('Filtros de Cabina', 'Filtros de aire para el habitáculo', 1),
('Radiadores', 'Radiadores y componentes de enfriamiento', 1),
('Escape', 'Silenciadores, tubos y kits de escape', 1),
('Transmisión', 'Embragues, ejes y accesorios de transmisión', 1),
('Ruedas y Llantas', 'Llantas, aros y accesorios de ruedas', 1),
('Motores', 'Piezas internas de motores', 1),
('Inyección', 'Inyectores, bombas y sensores', 1),
('Dirección', 'Bombas, cremalleras y accesorios de dirección', 1),
('Electrónica', 'Sensores, ECU y componentes eléctricos', 1),
('Carrocería', 'Paneles, puertas y accesorios de carrocería', 1),
('Climatización', 'Aires acondicionados y calefacción', 1),
('Hidráulicos', 'Bombas y mangueras hidráulicas', 1),
('Accesorios', 'Accesorios varios para autos', 1);

-- PRODUCTOS
INSERT INTO PRODUCTOS (nombre, descripcion, marca, costo_unitario, precio_venta, estado, id_proveedor, id_categoria, stock) VALUES
('Filtro de aceite Honda', 'Filtro de aceite para motores Honda 1.6 y 2.0', 'Honda', 1500, 3000, 1, 1, 1, 10),
('Pastillas de freno Nissan', 'Pastillas de freno delanteras Nissan', 'Nissan', 5000, 10000, 1, 2, 3, 30),
('Amortiguador Toyota', 'Amortiguador trasero Toyota Corolla', 'Toyota', 15000, 30000, 1, 3, 4, 20),
('Correa de distribución Mazda', 'Correa de distribución Mazda 3', 'Mazda', 7000, 14000, 1, 4, 5, 25),
('Batería Ford', 'Batería 12V Ford Fiesta', 'Ford', 30000, 60000, 1, 5, 6, 15),
('Faro delantero Chevrolet', 'Faro delantero derecho Chevrolet Aveo', 'Chevrolet', 12000, 24000, 1, 6, 7, 10),
('Filtro de aire Kia', 'Filtro de aire Kia Rio', 'Kia', 2500, 5000, 1, 7, 1, 40),
('Radiador Hyundai', 'Radiador Hyundai Elantra', 'Hyundai', 45000, 90000, 1, 8, 9, 5),
('Silenciador Volkswagen', 'Silenciador VW Golf', 'Volkswagen', 20000, 40000, 1, 9, 10, 12),
('Embrague Mitsubishi', 'Kit de embrague Mitsubishi Lancer', 'Mitsubishi', 50000, 100000, 1, 10, 11, 8),
('Inyector Suzuki', 'Inyector de combustible Suzuki Swift', 'Suzuki', 8000, 16000, 1, 11, 14, 18),
('Cremallera Renault', 'Cremallera de dirección Renault Clio', 'Renault', 35000, 70000, 1, 12, 15, 7),
('Sensor Fiat', 'Sensor de oxígeno Fiat Punto', 'Fiat', 5000, 10000, 1, 13, 16, 20),
('Panel Citroën', 'Panel de puerta Citroën C3', 'Citroën', 25000, 50000, 1, 14, 17, 6),
('Aire acondicionado Chery', 'Compresor aire Chery QQ', 'Chery', 80000, 160000, 1, 15, 18, 4),
('Bomba de agua Geely', 'Bomba de agua Geely Emgrand', 'Geely', 15000, 30000, 1, 16, 19, 10),
('Aceite de motor Honda', 'Aceite 10W40 para Honda', 'Honda', 2000, 4000, 1, 17, 2, 0),
('Pastillas traseras Nissan', 'Pastillas de freno traseras Nissan', 'Nissan', 6000, 12000, 1, 18, 3, 0),
('Amortiguador delantero Toyota', 'Amortiguador delantero Toyota Yaris', 'Toyota', 17000, 34000, 1, 19, 4, 0),
('Kit de embrague Mazda', 'Kit de embrague Mazda 3', 'Mazda', 45000, 90000, 1, 20, 11, 10);

-- INVENTARIO
INSERT INTO INVENTARIO (cantidad_disponible, stock_minimo, stock_total, id_producto)
VALUES 
(20, 5, 20, 1),
(15, 5, 15, 2);

-- CLIENTES
-- Usuario normal
INSERT INTO USUARIOS (nombre, apellidos, telefono, email, contrasena, rol)
VALUES ('Usuario1', 'Apellido1', '88888888', 'usuario@correo.com', 
        '$2y$10$xub2M2YzcpydSbMelhYIielq8owKEoyIP5vWQhqhc1NKQfQfuhPW6', 0);

-- Usuario ADMIN
INSERT INTO USUARIOS (nombre, apellidos, telefono, email, contrasena, rol)
VALUES ('ADMIN', 'ADMIN', '11111111', 'admin@correo.com', 
        '$2y$10$6lcKvvphm11tQC0Iu8hcVeJTP.qMHSCTOjhVFm49xqCwZtDJW7MBG', 1);



-- CITAS
INSERT INTO CITAS (fecha, hora, motivo, id_cliente)
VALUES 
('2025-07-30', '09:00:00', 'Revisión general de vehículo', 1),
('2025-08-02', '10:30:00', 'Cambio de frenos', 1);


-- VER TABLAS
SELECT * FROM PROVEEDORES;
SELECT * FROM CATEGORIAS;
SELECT * FROM PRODUCTOS;
SELECT * FROM INVENTARIO;
SELECT * FROM CLIENTES;
SELECT * FROM PEDIDOS;
SELECT * FROM DETALLE_PRODUCTOS_PEDIDOS;
SELECT * FROM FACTURAS;
SELECT * FROM CITAS;