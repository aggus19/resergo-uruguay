/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `barberias` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `barberias`;

CREATE TABLE IF NOT EXISTS `barberias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL DEFAULT 'N/A',
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT 'N/A',
  `sitio_web` varchar(50) NOT NULL DEFAULT 'www.barberia-demo.resergo.uy',
  `descripcion` text DEFAULT NULL,
  `logo` varchar(100) NOT NULL DEFAULT 'https://cdn.resergo.uy/default.png',
  `membresia` enum('Demo','Gratis','Básico','Avanzado','Premium') NOT NULL DEFAULT 'Gratis',
  `slug` varchar(50) NOT NULL DEFAULT 'N/A',
  `activa` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `barberos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT 'N/A',
  `apellido` varchar(50) NOT NULL DEFAULT 'N/A',
  `celular` varchar(15) NOT NULL DEFAULT 'N/A',
  `email` varchar(50) NOT NULL DEFAULT 'N/A',
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sucursal_id` (`sucursal_id`),
  CONSTRAINT `barberos_ibfk_1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `barberos_servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barbero_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `sucursal_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_barbero_servicio` (`barbero_id`,`servicio_id`,`sucursal_id`),
  KEY `barbero_id` (`barbero_id`),
  KEY `servicio_id` (`servicio_id`),
  KEY `sucursal_id` (`sucursal_id`),
  CONSTRAINT `barberos_servicios_ibfk_1` FOREIGN KEY (`barbero_id`) REFERENCES `barberos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barberos_servicios_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barberos_servicios_ibfk_3` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=381 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) NOT NULL,
  `usuario_id` int(10) unsigned DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo_movimiento` enum('Ingreso','Egreso','Rectificación') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT 'Efectivo' COMMENT 'Método de pago con el que se realizó',
  PRIMARY KEY (`id`),
  KEY `sucursal_id` (`sucursal_id`),
  KEY `fk_caja_usuario` (`usuario_id`),
  CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_caja_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT 'N/A',
  `apellido` varchar(50) NOT NULL DEFAULT 'N/A',
  `email` varchar(50) NOT NULL DEFAULT 'N/A',
  `telefono` varchar(50) NOT NULL DEFAULT '0',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `sucursal_id` (`sucursal_id`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DELIMITER //
CREATE PROCEDURE `crear_demo_barberia`(
    IN nombre_barberia VARCHAR(50),
    IN telefono_barberia VARCHAR(50),
    IN email_barberia VARCHAR(50),
    IN sitio_web_barberia VARCHAR(50),
    IN descripcion_barberia TEXT,
    IN logo_barberia VARCHAR(100),
    IN membresia_barberia ENUM('Demo','Gratis','Básico','Avanzado','Premium'),
    IN slug_barberia VARCHAR(50),
    IN nombre_sucursal VARCHAR(50),
    IN direccion_sucursal VARCHAR(50),
    IN email_sucursal VARCHAR(50),
    IN telefono_sucursal VARCHAR(50),
    IN nombre_barbero VARCHAR(50),
    IN apellido_barbero VARCHAR(50),
    IN celular_barbero VARCHAR(15),
    IN email_barbero VARCHAR(50),
    IN nombre_servicio VARCHAR(50),
    IN descripcion_servicio TEXT,
    IN duracion_servicio TINYINT,
    IN precio_servicio DECIMAL(10,2)
)
BEGIN
    DECLARE barberia_id INT;
    DECLARE sucursal_id INT;
    DECLARE barbero_id INT;

    -- Insertar barbería
    INSERT INTO barberias (nombre, telefono, email, sitio_web, descripcion, logo, membresia, slug)
    VALUES (nombre_barberia, telefono_barberia, email_barberia, sitio_web_barberia, descripcion_barberia, logo_barberia, membresia_barberia, slug_barberia);
    SET barberia_id = LAST_INSERT_ID();

    -- Insertar sucursal
    INSERT INTO sucursales (barberia_id, nombre, direccion, email, telefono)
    VALUES (barberia_id, nombre_sucursal, direccion_sucursal, email_sucursal, telefono_sucursal);
    SET sucursal_id = LAST_INSERT_ID();

    -- Insertar barbero
    INSERT INTO barberos (sucursal_id, nombre, apellido, celular, email)
    VALUES (sucursal_id, nombre_barbero, apellido_barbero, celular_barbero, email_barbero);
    SET barbero_id = LAST_INSERT_ID();

    -- Insertar horarios del barbero (Lunes a Sábado, 09:00 a 19:00)
    INSERT INTO horarios_barberos (barbero_id, sucursal_id, dia, hora_inicio, hora_fin)
    VALUES 
        (barbero_id, sucursal_id, 'Lunes', '09:00:00', '19:00:00'),
        (barbero_id, sucursal_id, 'Martes', '09:00:00', '19:00:00'),
        (barbero_id, sucursal_id, 'Miércoles', '09:00:00', '19:00:00'),
        (barbero_id, sucursal_id, 'Jueves', '09:00:00', '19:00:00'),
        (barbero_id, sucursal_id, 'Viernes', '09:00:00', '19:00:00'),
        (barbero_id, sucursal_id, 'Sábado', '09:00:00', '19:00:00');

    -- Insertar servicio en la sucursal
    INSERT INTO servicios (sucursal_id, nombre, descripcion, duracion, precio, activo, pack_especial)
    VALUES (sucursal_id, nombre_servicio, descripcion_servicio, duracion_servicio, precio_servicio, 1, 0);

    -- Insertar horarios de la sucursal (Lunes a Sábado, 09:00 a 19:00)
    INSERT INTO horarios_sucursales (sucursal_id, dia, hora_apertura, hora_cierre, estado)
    VALUES 
        (sucursal_id, 'Lunes', '09:00:00', '19:00:00', 1),
        (sucursal_id, 'Martes', '09:00:00', '19:00:00', 1),
        (sucursal_id, 'Miércoles', '09:00:00', '19:00:00', 1),
        (sucursal_id, 'Jueves', '09:00:00', '19:00:00', 1),
        (sucursal_id, 'Viernes', '09:00:00', '19:00:00', 1),
        (sucursal_id, 'Sábado', '09:00:00', '19:00:00', 1);
END//
DELIMITER ;

CREATE TABLE IF NOT EXISTS `horarios_barberos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barbero_id` int(11) DEFAULT NULL,
  `sucursal_id` int(11) DEFAULT NULL,
  `dia` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') DEFAULT NULL,
  `hora_inicio` time NOT NULL DEFAULT '10:00:00',
  `hora_fin` time NOT NULL DEFAULT '18:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `barbero_id_2` (`barbero_id`,`dia`),
  KEY `barbero_id` (`barbero_id`),
  KEY `sucursal_id` (`sucursal_id`),
  CONSTRAINT `horariosbarberos_ibfk_1` FOREIGN KEY (`barbero_id`) REFERENCES `barberos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `horariosbarberos_ibfk_2` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=645 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `horarios_sucursales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) DEFAULT NULL,
  `dia` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') DEFAULT NULL,
  `hora_apertura` time DEFAULT NULL,
  `hora_cierre` time DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `sucursal_id` (`sucursal_id`),
  KEY `idx_sucursal_dia` (`sucursal_id`,`dia`),
  CONSTRAINT `horarios_sucursales_ibfk_1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=931 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(10) unsigned DEFAULT NULL,
  `ip` varchar(45) NOT NULL DEFAULT 'N/A',
  `accion` varchar(50) NOT NULL DEFAULT '',
  `descripcion` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_logs_usuarios` (`usuario_id`),
  CONSTRAINT `FK_logs_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=359 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `metodos_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT 'N/A',
  `descripcion` text NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `sucursal_id` (`sucursal_id`),
  CONSTRAINT `metodos_pago_ibfk_1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reserva_id` int(11) NOT NULL,
  `tipo` enum('recordatorio','confirmacion') NOT NULL,
  `fecha_envio` datetime NOT NULL,
  `estado` enum('pendiente','enviado','fallido') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `reserva_id` (`reserva_id`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(5) NOT NULL DEFAULT 'UYU',
  `payer_email` varchar(255) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `planes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_usd` decimal(10,2) NOT NULL,
  `precio_uyu` decimal(10,2) DEFAULT NULL,
  `mercadopago_link` varchar(255) DEFAULT NULL,
  `is_discounted` tinyint(1) DEFAULT 0,
  `discount_percentage` int(11) DEFAULT NULL,
  `is_popular` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `plan_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `feature_text` varchar(255) NOT NULL,
  `has_feature` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `plan_features_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `barbero_id` int(11) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `fecha_reserva` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `estado` enum('Pendiente','Confirmada','Cancelada') NOT NULL DEFAULT 'Pendiente',
  `metodo_pago` varchar(50) NOT NULL DEFAULT 'Sin asignar',
  `fecha_emision` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_pago` timestamp NULL DEFAULT NULL,
  `token` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sucursal_id` (`sucursal_id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `barbero_id` (`barbero_id`),
  KEY `servicio_id` (`servicio_id`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`barbero_id`) REFERENCES `barberos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reservas_ibfk_4` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT 'N/A',
  `descripcion` text DEFAULT NULL,
  `duracion` tinyint(4) NOT NULL DEFAULT 0,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `pack_especial` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sucursal_id` (`sucursal_id`),
  CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `sucursales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barberia_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT 'N/A',
  `direccion` varchar(50) NOT NULL DEFAULT 'N/A',
  `email` varchar(50) NOT NULL DEFAULT 'default@resergo.uy',
  `telefono` varchar(50) NOT NULL DEFAULT 'N/A',
  `activa` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `barberia_id` (`barberia_id`),
  CONSTRAINT `sucursales_ibfk_1` FOREIGN KEY (`barberia_id`) REFERENCES `barberias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT 'N/A',
  `apellido` varchar(50) NOT NULL DEFAULT 'N/A',
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT 'N/A',
  `password` varchar(255) NOT NULL DEFAULT 'N/A',
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `rol` enum('Admin','Dueño','Barbero') NOT NULL DEFAULT 'Barbero',
  `first_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `sucursal_id` (`sucursal_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
