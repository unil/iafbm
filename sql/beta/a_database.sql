/*
	Auteur:  Stefan Meier
	Version: 2010.03.08
	
	Description: script de création de tables pour la base
				 iafbm (cf. MCD/MLD)
				 partie: système - applicatif
				 
	Détails: 	 cf conventions CakePHP
				 l'ordre de création est à respecter (contraintes)
*/
DROP DATABASE IF EXISTS iafbm_dev;
CREATE DATABASE iafbm_dev DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE iafbm_dev;

DROP TABLE IF EXISTS menus;
CREATE TABLE menus (
	id INTEGER(10) unsigned NOT NULL auto_increment,
	parent_id INTEGER(10),
	lft INTEGER(10),
	rght INTEGER(10),
	name VARCHAR(255),
	url VARCHAR(255),
	PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

INSERT INTO menus VALUES(1, NULL, 1, 22, 'Intranet', '');
INSERT INTO menus VALUES(2, 1, 2, 3, 'Décanat', '/decanat');
INSERT INTO menus VALUES(3, 1, 4, 11, 'Relève', '/releve');
INSERT INTO menus VALUES(4, 3, 5, 10, 'Commissions', '/releve/commission/afficher');
INSERT INTO menus VALUES(5, 1, 12, 21, 'Application', '/application');
INSERT INTO menus VALUES(6, 5, 13, 14, 'Droits', '/application/droits');
INSERT INTO menus VALUES(7, 5, 17, 20, 'Système', '/application/systeme');
INSERT INTO menus VALUES(8, 7, 18, 19, 'Navigation', '/application/systeme/navigation');
INSERT INTO menus VALUES(9, 5, 15, 16, 'Personnes', '/application/personnes');
INSERT INTO menus VALUES(22, 4, 6, 7, 'Rechercher', '/releve/commission/rechercher');
INSERT INTO menus VALUES(23, 4, 8, 9, 'Nouvelle', '/releve/commission/nouveau');

