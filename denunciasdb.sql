SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `atenciones` (
  `id_atencion` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) NOT NULL,
  `tipo_atencion` varchar(254) DEFAULT NULL,
  `narracion_hechos` varchar(1000) NOT NULL,
  `observaciones` varchar(1000) NOT NULL,
  `fecha_registro` date NOT NULL,
  `id_ciudadano` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `comunidad` varchar(255) NOT NULL,
  PRIMARY KEY (`id_atencion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2667 ;

CREATE TABLE IF NOT EXISTS `ciudadanos` (
  `id_ciudadano` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cedula` int(11) NOT NULL,
  `apellidos` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `nombres` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) CHARACTER SET utf8 NOT NULL,
  `telefonos` varchar(60) CHARACTER SET utf8 NOT NULL,
  `fecha_registro` date NOT NULL,
  PRIMARY KEY (`id_ciudadano`),
  UNIQUE KEY `cedula` (`cedula`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2414 ;

CREATE TABLE IF NOT EXISTS `comunidades` (
  `id_comunidad` int(11) NOT NULL AUTO_INCREMENT,
  `comunidad` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `id_parroquia` int(11) NOT NULL,
  PRIMARY KEY (`id_comunidad`),
  UNIQUE KEY `comunidad` (`comunidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=516 ;

CREATE TABLE IF NOT EXISTS `denuncias` (
  `id_denuncia` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) NOT NULL,
  `narracion_hechos` varchar(850) NOT NULL,
  `observaciones` varchar(500) NOT NULL,
  `estatus` varchar(15) NOT NULL,
  `requisitos` varchar(1) NOT NULL,
  `fecha_registro` date NOT NULL,
  `fecha_tope_entrega` date DEFAULT NULL COMMENT 'Es la fecha tope de entrega de los requisitos pendientes',
  `fecha_resolucion` date DEFAULT NULL,
  `id_ciudadano` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `comunidad` varchar(255) NOT NULL,
  PRIMARY KEY (`id_denuncia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `detalles_procesos` (
  `id_detalles_procesos` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_proceso` varchar(14) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `estatus` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `observacion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `funcionario` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_detalles_procesos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `parroquias_barinas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `municipio` varchar(80) NOT NULL,
  `parroquia` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

CREATE TABLE IF NOT EXISTS `perfiles` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `perfil` varchar(25) NOT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `reclamos` (
  `id_reclamo` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) NOT NULL,
  `narracion_hechos` varchar(1000) NOT NULL,
  `observaciones` varchar(1000) NOT NULL,
  `estatus` varchar(15) NOT NULL,
  `requisitos` varchar(1) NOT NULL,
  `fecha_registro` date NOT NULL,
  `fecha_tope_entrega` date DEFAULT NULL COMMENT 'Es la fecha tope de entrega de los requisitos pendientes',
  `fecha_resolucion` date DEFAULT NULL,
  `id_ciudadano` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `comunidad` varchar(255) NOT NULL,
  PRIMARY KEY (`id_reclamo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `solicitudes` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) NOT NULL,
  `narracion_hechos` varchar(1000) NOT NULL,
  `observaciones` varchar(1000) NOT NULL,
  `estatus` varchar(15) NOT NULL,
  `requisitos` varchar(1) NOT NULL,
  `fecha_registro` date NOT NULL,
  `fecha_tope_entrega` date DEFAULT NULL COMMENT 'Es la fecha tope de entrega de los requisitos pendientes',
  `fecha_resolucion` date DEFAULT NULL,
  `id_ciudadano` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `comunidad` varchar(255) NOT NULL,
  PRIMARY KEY (`id_solicitud`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) NOT NULL,
  `password` varchar(60) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `perfil` varchar(25) NOT NULL,
  `fecharegistro` date NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
