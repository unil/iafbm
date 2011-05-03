/*
	Auteur:  Stefan Meier
	Version: 2010.03.08
	
	Description: script de création de tables pour la base
				 iafbm (cf. MCD/MLD)
				 partie: personnes
				 
	Détails: 	 cf conventions CakePHP
				 l'ordre de création est à respecter (contraintes)

	Restauration d'un fichier: 
	mysql --user=USR --password=PWD --default-character-set=utf8  < /iafbm_personnes.sql

*/

USE iafbm_dev;

DROP TABLE IF EXISTS pays;
CREATE TABLE pays (
	id INTEGER(9) NOT NULL,
	code VARCHAR(2) NOT NULL,
	fr VARCHAR(255) NOT NULL,
	en VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS cantons;
CREATE TABLE cantons (
	id INTEGER(9) NOT NULL,
	code VARCHAR(2) NOT NULL,
	nom VARCHAR(100) NOT NULL,
	PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS permis;
CREATE TABLE permis (
	id INTEGER(9) NOT NULL,
	nom VARCHAR(100) NOT NULL,
	PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS personnes;
CREATE TABLE personnes (
	id INTEGER(9) NOT NULL,
	id_unil INTEGER(9),
	id_chuv INTEGER(9),
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	adresse VARCHAR(100),
	tel VARCHAR(15),
	email VARCHAR(50) NOT NULL,
	date_naissance DATE NOT NULL,
	etat_civile VARCHAR(10),
	sexe VARCHAR(1) NOT NULL,
	pays_id INTEGER(9) NOT NULL,
	cantons_id INTEGER(9),
	permis_id INTEGER(9),
	titre_lecon_inaug VARCHAR(100),
	date_lecon_inaug DATE,
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id),
	FOREIGN KEY (pays_id) REFERENCES pays(id),
	FOREIGN KEY (cantons_id) REFERENCES cantons(id),
	FOREIGN KEY (permis_id) REFERENCES permis(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS fonds;
CREATE TABLE fonds (
	id INTEGER(9) NOT NULL,
	numero INTEGER(12) NOT NULL,
	centre_couts INTEGER(10) NOT NULL,
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS fonds_personnes;
CREATE TABLE fonds_personnes (
	personnes_id INTEGER(9) NOT NULL,
	fonds_id INTEGER(9) NOT NULL,
	PRIMARY KEY (personnes_id, fonds_id),
	FOREIGN KEY (personnes_id) REFERENCES personnes(id),
	FOREIGN KEY (fonds_id) REFERENCES fonds(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS etablissements;
CREATE TABLE etablissements (
	id INTEGER(9) NOT NULL,
	nom VARCHAR(100) NOT NULL,
	abreviation VARCHAR(10),
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS departements;
CREATE TABLE departements (
	id INTEGER(9) NOT NULL,
	etablissements_id INTEGER(9) NOT NULL,
	nom VARCHAR(100) NOT NULL,
	abreviation VARCHAR(10),
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id),
	FOREIGN KEY (etablissements_id) REFERENCES etablissements(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS services;
CREATE TABLE services (
	id INTEGER(9) NOT NULL,
	departements_id INTEGER(9) NOT NULL,
	nom VARCHAR(100) NOT NULL,
	abreviation VARCHAR(10),
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id),
	FOREIGN KEY (departements_id) REFERENCES departements(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS cat_fonct_admin;
CREATE TABLE cat_fonct_admin (
	id INTEGER(9) NOT NULL,
	fonction VARCHAR(100) NOT NULL,
	description TEXT(200) NOT NULL,
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS fonct_admin;
CREATE TABLE fonct_admin (
	id INTEGER(9) NOT NULL,
	cat_fonct_admin_id INTEGER(9) NOT NULL,
	ad_personam INTEGER(1),
	suppleant INTEGER(1),
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id),
	FOREIGN KEY (cat_fonct_admin_id) REFERENCES cat_fonct_admin(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS cat_fonct_hosp;
CREATE TABLE cat_fonct_hosp (
	id INTEGER(9) NOT NULL,
	fonction VARCHAR(100) NOT NULL,
	description TEXT(200) NOT NULL,
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS fonct_hosp;
CREATE TABLE fonct_hosp (
	id INTEGER(9) NOT NULL,
	cat_fonct_hosp_id INTEGER(9) NOT NULL,
	ad_personam INTEGER(1),
	suppleant INTEGER(1),
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id),
	FOREIGN KEY (cat_fonct_hosp_id) REFERENCES cat_fonct_hosp(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS cat_fonct_acad;
CREATE TABLE cat_fonct_acad (
	id INTEGER(9) NOT NULL,
	fonction VARCHAR(100) NOT NULL,
	description TEXT(200) NOT NULL,
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS fonct_acad;
CREATE TABLE fonct_acad (
	id INTEGER(9) NOT NULL,
	cat_fonct_acad_id INTEGER(9) NOT NULL,
	ad_personam INTEGER(1),
	suppleant INTEGER(1),
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id),
	FOREIGN KEY (cat_fonct_acad_id) REFERENCES cat_fonct_acad(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS contrat;
CREATE TABLE contrat (
	id INTEGER(9) NOT NULL,
	fonct_admin_id INTEGER(9) NOT NULL,
	fonct_hosp_id INTEGER(9) NOT NULL,
	fonct_acad_id INTEGER(9) NOT NULL,
	services_id INTEGER(9) NOT NULL,
	debut DATE NOT NULL,
	fin DATE NOT NULL,
	taux INTEGER(3) NOT NULL,
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id),
	FOREIGN KEY (fonct_admin_id) REFERENCES fonct_admin(id),
	FOREIGN KEY (fonct_hosp_id) REFERENCES fonct_hosp(id),
	FOREIGN KEY (fonct_acad_id) REFERENCES fonct_acad(id),
	FOREIGN KEY (services_id) REFERENCES services(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS employe;
CREATE TABLE employe (
	id INTEGER(9) NOT NULL,
	personnes_id INTEGER(9) NOT NULL,
	contrat_id INTEGER(9) NOT NULL,
	id_pers_orig INTEGER(9) NOT NULL,
	section VARCHAR(3) NOT NULL,
	date_retraite DATE,
	etat INTEGER(1) NOT NULL,
	date_creat DATE NOT NULL,
	date_modif DATE NOT NULL,
	util_creat VARCHAR(50),
	util_modif VARCHAR(50),
	PRIMARY KEY (id),
	FOREIGN KEY (personnes_id) REFERENCES personnes(id),
	FOREIGN KEY (contrat_id) REFERENCES contrat(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;