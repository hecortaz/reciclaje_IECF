-- Base de datos para Gestión de Reciclaje

CREATE DATABASE IF NOT EXISTS waste_management;
USE waste_management;

-- Tabla de residuos
CREATE TABLE residuos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL,
    grado VARCHAR(10) NOT NULL,
    salon VARCHAR(50) NOT NULL,
    tipo_residuo ENUM('Papel', 'PET', 'Orgánico') NOT NULL,
    peso_kg DECIMAL(10, 2) NOT NULL,
    cantidad INT NOT NULL,
    numero_estudiantes INT NOT NULL,
    estado_residuo ENUM('Aprovechable', 'No aprovechable') NOT NULL,
    clasificacion VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha),
    INDEX idx_grado (grado),
    INDEX idx_salon (salon),
    INDEX idx_tipo (tipo_residuo),
    INDEX idx_estado (estado_residuo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de ejemplo
INSERT INTO residuos (fecha, grado, salon, tipo_residuo, peso_kg, cantidad, numero_estudiantes, estado_residuo, clasificacion) VALUES
('2026-05-15', '8°', 'Salón 8-A', 'Papel', 2.5, 15, 25, 'Aprovechable', 'Papel limpio - Reciclable'),
('2026-05-15', '8°', 'Salón 8-B', 'PET', 1.8, 12, 24, 'Aprovechable', 'PET limpio - Reciclable'),
('2026-05-15', '9°', 'Salón 9-A', 'Orgánico', 3.2, 8, 26, 'Aprovechable', 'Orgánico - Compostaje'),
('2026-05-14', '7°', 'Salón 7-A', 'Papel', 1.5, 10, 23, 'No aprovechable', 'Papel sucio - No aprovechable'),
('2026-05-14', '8°', 'Restaurante', 'Orgánico', 5.0, 20, 50, 'Aprovechable', 'Orgánico - Compostaje');
