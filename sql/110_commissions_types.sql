DROP TABLE IF EXISTS commissions_types;
CREATE TABLE commissions_types (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    nom VARCHAR(255) NOT NULL,
    racine VARCHAR(32) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `commissions_types` (id, actif, created, nom, racine) VALUES ('1', '1', CURRENT_TIMESTAMP, 'Commission de présentation', 'Présentation');
/*
INSERT INTO `commissions_types` (id, actif, created, nom, racine) VALUES ('2', '1', CURRENT_TIMESTAMP, 'Commission de promotion', 'Promotion');
INSERT INTO `commissions_types` (id, actif, created, nom, racine) VALUES ('3', '1', CURRENT_TIMESTAMP, 'Commission d''appel', 'Appel');
INSERT INTO `commissions_types` (id, actif, created, nom, racine) VALUES ('4', '1', CURRENT_TIMESTAMP, 'Commission de titularisation', 'Titularisation');
INSERT INTO `commissions_types` (id, actif, created, nom, racine) VALUES ('5', '1', CURRENT_TIMESTAMP, 'Commission de stabilisation', 'Stabilisation');
INSERT INTO `commissions_types` (id, actif, created, nom, racine) VALUES ('6', '1', CURRENT_TIMESTAMP, 'Groupe de réflexion', 'Réflexion');
*/