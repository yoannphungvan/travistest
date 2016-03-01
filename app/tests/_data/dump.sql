/* Replace this file with actual dump of your database */

DROP DATABASE IF EXISTS `travis`;

CREATE DATABASE `travis`;
GRANT ALL PRIVILEGES ON travis.* TO 'travis'@'localhost' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON travis.* TO 'travis'@'%' WITH GRANT OPTION;

USE `travis`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
