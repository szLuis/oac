-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-01-2016 a las 15:41:17
-- Versión del servidor: 5.5.44-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `denunciasdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atenciones`
--

CREATE TABLE IF NOT EXISTS `atenciones` (
  `id_atencion` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) NOT NULL,
  `narracion_hechos` varchar(1000) NOT NULL,
  `observaciones` varchar(1000) NOT NULL,
  `fecha_registro` date NOT NULL,
  `id_ciudadano` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `comunidad` varchar(255) NOT NULL,
  PRIMARY KEY (`id_atencion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudadanos`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidades`
--

CREATE TABLE IF NOT EXISTS `comunidades` (
  `id_comunidad` int(11) NOT NULL AUTO_INCREMENT,
  `comunidad` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_comunidad`),
  UNIQUE KEY `comunidad` (`comunidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denuncias`
--

CREATE TABLE IF NOT EXISTS `denuncias` (
  `id_denuncia` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id_denuncia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_procesos`
--

CREATE TABLE IF NOT EXISTS `detalles_procesos` (
  `id_detalles_procesos` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_proceso` varchar(14) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `estatus` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `observacion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `funcionario` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id_detalles_procesos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE IF NOT EXISTS `perfiles` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `perfil` varchar(25) NOT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reclamos`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) NOT NULL,
  `password` varchar(60) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `perfil` varchar(25) NOT NULL,
  `fecharegistro` date NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `cambiar_estatus` ON SCHEDULE EVERY 1 DAY STARTS '2015-09-01 00:00:00' ON COMPLETION PRESERVE ENABLE DO UPDATE denuncias 
SET estatus = 'Rechazada' 
WHERE estatus = 'Por soportes' 
AND requisitos = 's' 
AND DATEDIFF( NOW( ) , fecha_tope_entrega ) >=1$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
