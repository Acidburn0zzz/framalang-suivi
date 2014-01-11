--
-- SQL Initialization script
--

CREATE TABLE `PRIORITY` (
  `ID_Priority` tinyint(1) NOT NULL,
  `Name_Priority` varchar(10) NOT NULL,
  PRIMARY KEY (`ID_Priority`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Priority levels';

INSERT INTO `PRIORITY` (`ID_Priority`, `Name_Priority`) VALUES (0, 'Normal');
INSERT INTO `PRIORITY` (`ID_Priority`, `Name_Priority`) VALUES (1, 'Urgent');

CREATE TABLE `STATUS` (
  `ID_Status` tinyint(1) NOT NULL DEFAULT '0',
  `Name_Status` varchar(15) NOT NULL,
  PRIMARY KEY (`ID_Status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Available status for a traduction';

INSERT INTO `STATUS` VALUES (0, 'Créée', 1);
INSERT INTO `STATUS` VALUES (1, 'En cours', 2);
INSERT INTO `STATUS` VALUES (2, 'A relire', 3);
INSERT INTO `STATUS` VALUES (3, 'En relecture', 4);
INSERT INTO `STATUS` VALUES (4, 'A publier', 5);
INSERT INTO `STATUS` VALUES (5, 'Publiée', 6);

CREATE TABLE `TRADUCTION` (
  `ID_Trad` bigint(20) AUTO_INCREMENT,
  `Title_Trad` varchar(127) NOT NULL,
  `UrlPad_Trad` varchar(255) DEFAULT NULL,
  `UrlPub_Trad` varchar(255) DEFAULT NULL,
  `DateCre_Trad` date NOT NULL,
  `DateDeb_Trad` date DEFAULT NULL,
  `DateEnd_Trad` date DEFAULT NULL,
  `ID_Status` tinyint(1) NOT NULL,
  `ID_Priority` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_Trad`),
  KEY `FK_STATUS` (`ID_Status`),
  KEY `FK_PRIORITY` (`ID_Priority`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Traduction information';

-- INSERT INTO `TRADUCTION` (`ID_Trad`, `Title_Trad`, `UrlPad_Trad`, `UrlPub_Trad`, `DateCre_Trad`, `DateDeb_Trad`, `DateEnd_Trad`, `ID_Status`, `ID_Priority`) VALUES (0, 'Human rights surveillance', 'http://lite.framapad.org/p/human-rights-surveillance', NULL, '2013-06-24', '2013-06-25', NULL, 1, 0);
-- INSERT INTO `TRADUCTION` (`ID_Trad`, `Title_Trad`, `UrlPad_Trad`, `UrlPub_Trad`, `DateCre_Trad`, `DateDeb_Trad`, `DateEnd_Trad`, `ID_Status`, `ID_Priority`) VALUES (1, 'Ceci est une page web', 'http://lite.framapad.org/p/this-is-a-web-page', 'http://www.framablog.org/index.php/post/2013/06/23/ceci-est-une-page-web', '2013-06-12', '2013-06-12', '2013-06-22', 3, 0);

CREATE TABLE `ROLE` (
    `ID_Role` int(2) NOT NULL,
    `Name_Role` varchar(20) NOT NULL,
    PRIMARY KEY (`ID_Role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Role information';

INSERT INTO `ROLE` (`ID_Role`, `Name_Role`) VALUES (0, 'Administrateur');
INSERT INTO `ROLE` (`ID_Role`, `Name_Role`) VALUES (1, 'Utilisateur');

CREATE TABLE `USER` (
  `ID_User` int(11) AUTO_INCREMENT,
  `Pseudo_User` varchar(50) NOT NULL,
  `Pass_User` varchar(256) NOT NULL,
  `Mail_User` varchar(150) NOT NULL,
  `Role_User` int(2) NOT NULL,
  PRIMARY KEY (`ID_User`),
  KEY `FK_ROLE_USER` (`Role_User`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Users information';
INSERT INTO `USER` (`ID_User`, `Pseudo_User`, `Pass_User`, `Role_User`) VALUES (0, 'plop', 'e9fe716b114fe921990b10e597f2c312', 0);