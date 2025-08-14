-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 04-Nov-2024 às 18:33
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `mesh`
--
CREATE DATABASE IF NOT EXISTS `mesh` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mesh`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `leaderboard`
--

CREATE TABLE IF NOT EXISTS `leaderboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `vitorias` int(11) DEFAULT '0',
  `Email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_email` (`Email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `id_like` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_email` varchar(255) NOT NULL,
  `Id_Posts` int(11) NOT NULL,
  PRIMARY KEY (`id_like`),
  UNIQUE KEY `usuario_email` (`usuario_email`,`Id_Posts`),
  KEY `Id_Posts` (`Id_Posts`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `republicar`
--

CREATE TABLE IF NOT EXISTS `republicar` (
  `id_republicar` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_email` varchar(255) NOT NULL,
  `Id_Posts` int(11) NOT NULL,
  `Imgautor` longblob NOT NULL,
  `Dia` date NOT NULL,
  `Hora` time NOT NULL,
  PRIMARY KEY (`id_republicar`),
  KEY `Id_Posts` (`Id_Posts`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `seguindo`
--

CREATE TABLE IF NOT EXISTS `seguindo` (
  `follower` varchar(255) NOT NULL DEFAULT '',
  `following` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`follower`,`following`),
  KEY `seguido_email` (`following`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_chat`
--

CREATE TABLE IF NOT EXISTS `tb_chat` (
  `Mensagem` varchar(500) NOT NULL,
  `Dia` date NOT NULL,
  `Hora` time NOT NULL,
  `Autor` varchar(100) NOT NULL,
  `Destinatario` varchar(100) NOT NULL,
  `id_mensagem` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_mensagem`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_contas`
--

CREATE TABLE IF NOT EXISTS `tb_contas` (
  `Imagem` longblob NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Senha` varchar(25) NOT NULL,
  `Seguidores` int(11) NOT NULL,
  `Cargo` varchar(35) NOT NULL,
  `Empresa` varchar(35) NOT NULL,
  `Data_Nascimento` date NOT NULL,
  `Telefone` int(15) NOT NULL,
  `Sobre` varchar(100) NOT NULL,
  `Formacao` varchar(50) NOT NULL,
  `Habilidades` varchar(100) NOT NULL,
  `Interesses` varchar(100) NOT NULL,
  `Estado` varchar(20) NOT NULL,
  `Cidade` varchar(35) NOT NULL,
  `Seguindo` tinyint(1) DEFAULT '0',
  `Facebook` varchar(200) NOT NULL,
  `Instagram` varchar(200) NOT NULL,
  `Linkedin` varchar(200) NOT NULL,
  PRIMARY KEY (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_posts`
--

CREATE TABLE IF NOT EXISTS `tb_posts` (
  `Autor` varchar(100) NOT NULL,
  `Hora` time NOT NULL,
  `Conteudo` varchar(300) NOT NULL,
  `Imagem` longblob NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Id_Posts` int(11) NOT NULL AUTO_INCREMENT,
  `Dia` date NOT NULL,
  `AutImg` longblob NOT NULL,
  `likes` int(11) NOT NULL,
  PRIMARY KEY (`Id_Posts`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `fk_email` FOREIGN KEY (`Email`) REFERENCES `tb_contas` (`Email`);

--
-- Limitadores para a tabela `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`Id_Posts`) REFERENCES `tb_posts` (`Id_Posts`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `republicar`
--
ALTER TABLE `republicar`
  ADD CONSTRAINT `republicar_ibfk_1` FOREIGN KEY (`Id_Posts`) REFERENCES `tb_posts` (`Id_Posts`);

--
-- Limitadores para a tabela `seguindo`
--
ALTER TABLE `seguindo`
  ADD CONSTRAINT `seguindo_ibfk_1` FOREIGN KEY (`follower`) REFERENCES `tb_contas` (`Email`),
  ADD CONSTRAINT `seguindo_ibfk_2` FOREIGN KEY (`following`) REFERENCES `tb_contas` (`Email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
