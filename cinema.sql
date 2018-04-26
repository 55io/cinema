-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `cinema` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `cinema`;

DROP TABLE IF EXISTS `film`;
CREATE TABLE `film` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `duration` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `seance`;
CREATE TABLE `seance` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `film_id` int(7) NOT NULL,
  `start_time` datetime NOT NULL,
  `price` float NOT NULL,
  `max_tickets_count` int(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `film_id` (`film_id`),
  CONSTRAINT `seance_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `seance_id` int(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `seance_id` (`seance_id`),
  CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`seance_id`) REFERENCES `seance` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2018-04-26 08:27:49
