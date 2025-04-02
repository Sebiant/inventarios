CREATE DATABASE Restock;
USE Restock;

-- Tabla de productos (Inventario)
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    unidad_medida VARCHAR(50) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT 1
);

-- Tabla de movimientos de inventario (Entradas y Salidas)
CREATE TABLE movimientos (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    tipo_movimiento ENUM('Entrada', 'Salida') NOT NULL,
    cantidad INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    motivo TEXT NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- Vista de Reportes: Stock actual por producto
CREATE VIEW reporte_inventario AS
SELECT 
    p.id_producto,
    p.nombre,
    p.categoria,
    SUM(CASE WHEN m.tipo_movimiento = 'Entrada' THEN m.cantidad ELSE -m.cantidad END) AS stock_actual
FROM productos p
LEFT JOIN movimientos m ON p.id_producto = m.id_producto
GROUP BY p.id_producto, p.nombre, p.categoria;
