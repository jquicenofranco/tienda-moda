-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para sistema_moda
CREATE DATABASE IF NOT EXISTS `sistema_moda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sistema_moda`;

-- Volcando estructura para tabla sistema_moda.caja_sesiones
CREATE TABLE IF NOT EXISTS `caja_sesiones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `monto_apertura` decimal(10,2) NOT NULL,
  `monto_cierre` decimal(10,2) DEFAULT NULL,
  `fecha_apertura` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_cierre` datetime DEFAULT NULL,
  `total_ventas_sistema` decimal(10,2) DEFAULT '0.00',
  `diferencia` decimal(10,2) DEFAULT NULL,
  `estado` enum('abierta','cerrada') DEFAULT 'abierta',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `caja_sesiones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.caja_sesiones: ~5 rows (aproximadamente)
DELETE FROM `caja_sesiones`;
INSERT INTO `caja_sesiones` (`id`, `usuario_id`, `monto_apertura`, `monto_cierre`, `fecha_apertura`, `fecha_cierre`, `total_ventas_sistema`, `diferencia`, `estado`) VALUES
	(1, 5, 10.00, 29.00, '2025-11-23 11:19:03', '2025-11-23 11:19:38', 19.00, 0.00, 'cerrada'),
	(2, 3, 29.00, 67.00, '2025-11-23 11:19:58', '2025-11-23 11:20:41', 38.00, 0.00, 'cerrada'),
	(3, 3, 67.00, 155.00, '2025-11-23 11:21:21', '2025-11-23 15:26:24', 98.00, 0.00, 'cerrada'),
	(4, 5, 100.00, 133.00, '2025-11-23 12:34:56', '2025-11-23 12:36:15', 38.00, 0.00, 'cerrada'),
	(5, 3, 10.00, NULL, '2025-11-23 16:14:16', NULL, 0.00, NULL, 'abierta');

-- Volcando estructura para tabla sistema_moda.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.categorias: ~0 rows (aproximadamente)
DELETE FROM `categorias`;
INSERT INTO `categorias` (`id`, `nombre`) VALUES
	(1, 'Calzado'),
	(2, 'Ropa'),
	(3, 'Accesorios');

-- Volcando estructura para tabla sistema_moda.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` text,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.clientes: ~3 rows (aproximadamente)
DELETE FROM `clientes`;
INSERT INTO `clientes` (`id`, `nombre`, `documento`, `telefono`, `correo`, `direccion`, `activo`, `fecha_registro`) VALUES
	(1, 'Público General', '00000000', NULL, NULL, NULL, 1, '2025-11-23 11:24:25'),
	(2, 'CARLOS RAMIREZ', '12121212', '966666666', 'carlosramirez@correo.com', 'mi dirección', 1, '2025-11-23 11:29:19'),
	(3, 'MARIA ARIAS', '13131313', '23232323', 'maria@tienda.com', 'mi dirección', 1, '2025-11-23 15:18:27');

-- Volcando estructura para tabla sistema_moda.compras
CREATE TABLE IF NOT EXISTS `compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `proveedor_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `numero_comprobante` varchar(50) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `proveedor_id` (`proveedor_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.compras: ~1 rows (aproximadamente)
DELETE FROM `compras`;
INSERT INTO `compras` (`id`, `proveedor_id`, `usuario_id`, `numero_comprobante`, `total`, `fecha`) VALUES
	(1, 2, 3, 'BOL-0001', 4800.00, '2025-11-23 13:13:47'),
	(2, 2, 3, 'BOL-0002', 990.00, '2025-11-23 15:19:49');

-- Volcando estructura para tabla sistema_moda.compra_detalles
CREATE TABLE IF NOT EXISTS `compra_detalles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compra_id` int NOT NULL,
  `variante_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compra_id` (`compra_id`),
  KEY `variante_id` (`variante_id`),
  CONSTRAINT `compra_detalles_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  CONSTRAINT `compra_detalles_ibfk_2` FOREIGN KEY (`variante_id`) REFERENCES `producto_variantes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.compra_detalles: ~6 rows (aproximadamente)
DELETE FROM `compra_detalles`;
INSERT INTO `compra_detalles` (`id`, `compra_id`, `variante_id`, `cantidad`, `precio_compra`, `subtotal`) VALUES
	(1, 1, 4, 50, 31.00, 1550.00),
	(2, 1, 5, 50, 32.00, 1600.00),
	(3, 1, 6, 50, 33.00, 1650.00),
	(4, 2, 7, 10, 30.00, 300.00),
	(5, 2, 8, 10, 33.00, 330.00),
	(6, 2, 9, 10, 36.00, 360.00);

-- Volcando estructura para tabla sistema_moda.empresa
CREATE TABLE IF NOT EXISTS `empresa` (
  `id` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `direccion` text,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mensaje_ticket` text,
  `moneda` varchar(5) DEFAULT 'S/',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.empresa: ~1 rows (aproximadamente)
DELETE FROM `empresa`;
INSERT INTO `empresa` (`id`, `nombre`, `ruc`, `direccion`, `telefono`, `email`, `mensaje_ticket`, `moneda`) VALUES
	(1, 'MI TIENDA DE MODA', '20000000001', 'Av. Principal 123', '999-000-000', 'contacto@tienda.com', '¡Vuelva pronto!\r\n!Feliz Navidad¡', 'S/');

-- Volcando estructura para tabla sistema_moda.gastos
CREATE TABLE IF NOT EXISTS `gastos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `caja_sesion_id` int NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `caja_sesion_id` (`caja_sesion_id`),
  CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`caja_sesion_id`) REFERENCES `caja_sesiones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.gastos: ~2 rows (aproximadamente)
DELETE FROM `gastos`;
INSERT INTO `gastos` (`id`, `caja_sesion_id`, `descripcion`, `monto`, `fecha`, `usuario_id`) VALUES
	(1, 4, 'pago de delivery 1', 5.00, '2025-11-23 12:35:52', 0),
	(2, 3, 'pago de delivery 2', 10.00, '2025-11-23 15:26:10', 3);

-- Volcando estructura para tabla sistema_moda.kardex
CREATE TABLE IF NOT EXISTS `kardex` (
  `id` int NOT NULL AUTO_INCREMENT,
  `variante_id` int NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` int NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `variante_id` (`variante_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `kardex_ibfk_1` FOREIGN KEY (`variante_id`) REFERENCES `producto_variantes` (`id`),
  CONSTRAINT `kardex_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.kardex: ~15 rows (aproximadamente)
DELETE FROM `kardex`;
INSERT INTO `kardex` (`id`, `variante_id`, `tipo`, `cantidad`, `descripcion`, `fecha`, `usuario_id`) VALUES
	(1, 2, 'salida', 1, 'Venta Ticket #000009', '2025-11-23 12:46:49', 3),
	(2, 2, 'entrada', 1, 'Anulación Ticket #000009 (prueba error 2)', '2025-11-23 12:47:35', 3),
	(3, 2, 'salida', 1, 'El polo está roto', '2025-11-23 12:56:54', 3),
	(4, 2, 'entrada', 1, 'Se procederá a cambiar el polo por otra talla', '2025-11-23 12:58:18', 3),
	(5, 4, 'entrada', 50, 'Compra BOL-0001 (ID: 1)', '2025-11-23 13:13:47', 3),
	(6, 5, 'entrada', 50, 'Compra BOL-0001 (ID: 1)', '2025-11-23 13:13:47', 3),
	(7, 6, 'entrada', 50, 'Compra BOL-0001 (ID: 1)', '2025-11-23 13:13:47', 3),
	(8, 7, 'entrada', 10, 'Compra BOL-0002 (ID: 2)', '2025-11-23 15:19:49', 3),
	(9, 8, 'entrada', 10, 'Compra BOL-0002 (ID: 2)', '2025-11-23 15:19:49', 3),
	(10, 9, 'entrada', 10, 'Compra BOL-0002 (ID: 2)', '2025-11-23 15:19:49', 3),
	(11, 7, 'salida', 1, 'Venta Ticket #000010', '2025-11-23 15:20:37', 3),
	(12, 9, 'salida', 1, 'Venta Ticket #000010', '2025-11-23 15:20:37', 3),
	(13, 7, 'entrada', 1, 'Ajuste Manual: lo van a cambiar por otro polo', '2025-11-23 15:25:40', 3),
	(14, 7, 'salida', 2, 'Venta Ticket #000011', '2025-11-23 16:14:54', 3),
	(15, 8, 'salida', 1, 'Venta Ticket #000011', '2025-11-23 16:14:54', 3);

-- Volcando estructura para tabla sistema_moda.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_barras_base` varchar(50) DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text,
  `activo` tinyint(1) DEFAULT '1',
  `categoria_id` int DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.productos: ~0 rows (aproximadamente)
DELETE FROM `productos`;
INSERT INTO `productos` (`id`, `codigo_barras_base`, `nombre`, `descripcion`, `activo`, `categoria_id`, `precio_compra`, `precio_venta`, `imagen`, `fecha_creacion`) VALUES
	(2, 'POLOXXX', 'POLO BÁSICO', 'POLOS', 1, 2, 15.00, 19.00, NULL, '2025-11-23 01:20:36'),
	(3, 'POLOURBAN', 'POLO URBANO', 'POLO URBANO 2025', 1, 2, 33.00, 35.00, NULL, '2025-11-23 13:12:05'),
	(4, 'POLO MODERNO', 'POLO MODERNO', 'POLO MODERNO', 1, 2, 36.00, 30.00, NULL, '2025-11-23 15:17:20');

-- Volcando estructura para tabla sistema_moda.producto_variantes
CREATE TABLE IF NOT EXISTS `producto_variantes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `producto_id` int NOT NULL,
  `talla` varchar(10) NOT NULL,
  `color` varchar(50) NOT NULL,
  `stock_actual` int DEFAULT '0',
  `stock_minimo` int DEFAULT '5',
  `codigo_barras_variante` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `producto_variantes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.producto_variantes: ~0 rows (aproximadamente)
DELETE FROM `producto_variantes`;
INSERT INTO `producto_variantes` (`id`, `producto_id`, `talla`, `color`, `stock_actual`, `stock_minimo`, `codigo_barras_variante`) VALUES
	(1, 2, 'S', 'NEGRO', 0, 5, '2-S-NEG'),
	(2, 2, 'M', 'NEGRO', 10, 5, '2-M-NEG'),
	(3, 2, 'L', 'BLANCO', 0, 5, '2-L-BLA'),
	(4, 3, 'S', 'NEGRO', 50, 5, '3-S-NEG'),
	(5, 3, 'M', 'NEGRO', 50, 5, '3-M-NEG'),
	(6, 3, 'L', 'NEGRO', 50, 5, '3-L-NEG'),
	(7, 4, 'S', 'AZUL', 8, 5, '4-S-AZU'),
	(8, 4, 'M', 'NEGRO', 9, 5, '4-M-NEG'),
	(9, 4, 'L', 'NEGRO', 9, 5, '4-L-NEG');

-- Volcando estructura para tabla sistema_moda.proveedores
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ruc` varchar(20) DEFAULT NULL,
  `razon_social` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` text,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ruc` (`ruc`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.proveedores: ~3 rows (aproximadamente)
DELETE FROM `proveedores`;
INSERT INTO `proveedores` (`id`, `ruc`, `razon_social`, `telefono`, `correo`, `direccion`, `activo`, `fecha_registro`) VALUES
	(1, '00000000000', 'Proveedor General', NULL, NULL, 'Local', 1, '2025-11-23 13:01:24'),
	(2, '20202020201', 'PROVEEDOR 1', '12121212', 'proveedor1@correo.com', 'mi dirección', 1, '2025-11-23 13:05:07'),
	(3, '30303030301', 'PROVEEDOR 2', '12121213', 'proveedor2@correo.com', 'MI DIRECCION', 1, '2025-11-23 15:27:02');

-- Volcando estructura para tabla sistema_moda.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','vendedor') DEFAULT 'vendedor',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.usuarios: ~1 rows (aproximadamente)
DELETE FROM `usuarios`;
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `activo`) VALUES
	(3, 'Admin Principal', 'admin@tienda.com', '$2y$10$uw8PETVysGcwnKzoEH3HxualJv46S8/.evhQkepuHOdfhmIntrShe', 'admin', 1),
	(5, 'MARIO', 'mario@tienda.com', '$2y$10$rEYS6RePPbqvtvV5i9UcmOQ6LJaecD0QS3bmbmSPLZjR5.Y3HHrMS', 'vendedor', 1);

-- Volcando estructura para tabla sistema_moda.ventas
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `cliente_id` int DEFAULT '1',
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) DEFAULT NULL,
  `estado` enum('completada','anulada') DEFAULT 'completada',
  `motivo_anulacion` text,
  `fecha_anulacion` datetime DEFAULT NULL,
  `usuario_anulacion_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.ventas: ~0 rows (aproximadamente)
DELETE FROM `ventas`;
INSERT INTO `ventas` (`id`, `usuario_id`, `cliente_id`, `fecha`, `total`, `estado`, `motivo_anulacion`, `fecha_anulacion`, `usuario_anulacion_id`) VALUES
	(1, 1, 1, '2025-11-23 01:28:34', 190.00, 'completada', NULL, NULL, NULL),
	(2, 1, 1, '2025-11-23 01:31:00', 38.00, 'completada', NULL, NULL, NULL),
	(3, 3, 1, '2025-11-23 01:42:27', 57.00, 'anulada', 'prueba de error', '2025-11-23 10:40:31', 3),
	(4, 3, 1, '2025-11-23 01:50:17', 19.00, 'completada', NULL, NULL, NULL),
	(5, 5, 1, '2025-11-23 11:19:19', 19.00, 'completada', NULL, NULL, NULL),
	(6, 3, 1, '2025-11-23 11:20:16', 38.00, 'completada', NULL, NULL, NULL),
	(7, 3, 2, '2025-11-23 11:35:24', 38.00, 'completada', NULL, NULL, NULL),
	(8, 5, 1, '2025-11-23 12:35:11', 38.00, 'completada', NULL, NULL, NULL),
	(9, 3, 1, '2025-11-23 12:46:49', 19.00, 'anulada', 'prueba error 2', '2025-11-23 12:47:35', 3),
	(10, 3, 1, '2025-11-23 15:20:37', 60.00, 'completada', NULL, NULL, NULL),
	(11, 3, 1, '2025-11-23 16:14:54', 90.00, 'completada', NULL, NULL, NULL);

-- Volcando estructura para tabla sistema_moda.venta_detalles
CREATE TABLE IF NOT EXISTS `venta_detalles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `venta_id` int NOT NULL,
  `variante_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  KEY `variante_id` (`variante_id`),
  CONSTRAINT `venta_detalles_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  CONSTRAINT `venta_detalles_ibfk_2` FOREIGN KEY (`variante_id`) REFERENCES `producto_variantes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_moda.venta_detalles: ~0 rows (aproximadamente)
DELETE FROM `venta_detalles`;
INSERT INTO `venta_detalles` (`id`, `venta_id`, `variante_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
	(1, 1, 3, 5, 19.00, 95.00),
	(2, 1, 1, 5, 19.00, 95.00),
	(3, 2, 1, 2, 19.00, 38.00),
	(4, 3, 1, 3, 19.00, 57.00),
	(5, 4, 2, 1, 19.00, 19.00),
	(6, 5, 1, 1, 19.00, 19.00),
	(7, 6, 2, 2, 19.00, 38.00),
	(8, 7, 1, 2, 19.00, 38.00),
	(9, 8, 2, 2, 19.00, 38.00),
	(10, 9, 2, 1, 19.00, 19.00),
	(11, 10, 7, 1, 30.00, 30.00),
	(12, 10, 9, 1, 30.00, 30.00),
	(13, 11, 7, 2, 30.00, 60.00),
	(14, 11, 8, 1, 30.00, 30.00);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
