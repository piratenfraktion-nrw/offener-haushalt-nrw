DROP TABLE IF EXISTS `explanations`;
CREATE TABLE `explanations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter` varchar(255) CHARACTER SET utf8 NOT NULL,
  `category` varchar(255) CHARACTER SET utf8 NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `year` year(4) NOT NULL,
  `text` text CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chapter` (`chapter`,`category`,`title`,`year`),
  KEY `kapitel` (`chapter`),
  KEY `category` (`category`),
  KEY `title` (`title`),
  KEY `year` (`year`)
) ENGINE=InnoDB AUTO_INCREMENT=71252 DEFAULT CHARSET=utf8;