
-- Volcando estructura de base de datos para codingtutorials
CREATE DATABASE IF NOT EXISTS `codingtutorials` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `codingtutorials`;

-- Volcando estructura para tabla codingtutorials.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Volcando estructura para tabla codingtutorials.tutorial
CREATE TABLE IF NOT EXISTS `tutorial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'Imagenes/default_tutorial.png',
  `categoria` varchar(255) DEFAULT NULL,
  `etiquetas` varchar(255) DEFAULT NULL,
  `herramientas` longtext DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `visitas` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


-- Volcando estructura para tabla codingtutorials.tutorial_usuario
CREATE TABLE IF NOT EXISTS `tutorial_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_tutorial` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_tutorial` (`id_tutorial`),
  CONSTRAINT `tutorial_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  CONSTRAINT `tutorial_usuario_ibfk_2` FOREIGN KEY (`id_tutorial`) REFERENCES `tutorial` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Volcando estructura para tabla codingtutorials.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'Imagenes/default_profile.png',
  `descripcion` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT 'regular',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

