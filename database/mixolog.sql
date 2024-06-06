SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

-- tabulka registrovaných uživatelů (kteří moho nahrávat recepty)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1,	'pavel',	'pavel'),
(2,	'alena',	'heslo'),
(4, 'admin', '123456789'),
(3,	'petr',	  '12345');

-- tabulka receptů – počet zobrazení
DROP TABLE IF EXISTS `drinky`;
CREATE TABLE `drinky` (
  `nazev` varchar(100) NOT NULL,
  `precteno` int unsigned NOT NULL,
  PRIMARY KEY (`nazev`)
);
