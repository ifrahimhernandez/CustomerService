-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-05-2015 a las 05:08:34
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `ejemplo_pdf`
--
CREATE DATABASE IF NOT EXISTS `ejemplo_pdf` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ejemplo_pdf`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `cod_usu` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `Apellido` varchar(30) DEFAULT NULL,
  `Pais` varchar(30) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `dni` char(8) DEFAULT NULL,
  PRIMARY KEY (`cod_usu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cod_usu`, `nombre`, `Apellido`, `Pais`, `edad`, `dni`) VALUES
(1, 'leonardo', 'zapata', 'Perú', 20, '12345678'),
(2, 'lennin ', 'artezano', 'Perú', 16, '87654321'),
(3, 'juan', 'pepito', 'argentina', 20, '87654367'),
(4, 'Juana', 'Magdalena', 'Argentina', 23, '87954362'),
(5, 'henrique', 'filomeno', 'Mexico', 25, '89765678'),
(6, 'pedro', 'infante', 'Mexico', 70, '78965432'),
(7, 'carlos', 'zabala', 'Colombia', 26, '12346598'),
(8, 'Cesar', 'zarate', 'Colombia', 27, '89765432');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
