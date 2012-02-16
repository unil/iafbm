DROP TABLE IF EXISTS commissions_types;
CREATE TABLE commissions_types (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(255) NOT NULL,
    racine VARCHAR(32) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `commissions_types` (id, actif, nom, racine) VALUES ('1', '1', 'Commission de présentation', 'Présentation');
/*
INSERT INTO `commissions_types` (id, actif, nom, racine) VALUES ('2', '1', 'Commission de promotion', 'Promotion');
INSERT INTO `commissions_types` (id, actif, nom, racine) VALUES ('3', '1', 'Commission d''appel', 'Appel');
INSERT INTO `commissions_types` (id, actif, nom, racine) VALUES ('4', '1', 'Commission de titularisation', 'Titularisation');
INSERT INTO `commissions_types` (id, actif, nom, racine) VALUES ('5', '1', 'Commission de stabilisation', 'Stabilisation');
INSERT INTO `commissions_types` (id, actif, nom, racine) VALUES ('6', '1', 'Groupe de réflexion', 'Réflexion');
*/