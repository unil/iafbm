DROP TABLE IF EXISTS commissions_types;
CREATE TABLE commissions_types (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    nom VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `commissions_types` (actif, created, nom) VALUES ('1', CURRENT_TIMESTAMP, 'Commission de présentation');
INSERT INTO `commissions_types` (actif, created, nom) VALUES ('1', CURRENT_TIMESTAMP, 'Groupe de réflexion');
INSERT INTO `commissions_types` (actif, created, nom) VALUES ('1', CURRENT_TIMESTAMP, 'Commission de promotion');
INSERT INTO `commissions_types` (actif, created, nom) VALUES ('1', CURRENT_TIMESTAMP, 'Commission d''appel');
INSERT INTO `commissions_types` (actif, created, nom) VALUES ('1', CURRENT_TIMESTAMP, 'Commission de titularisation');
INSERT INTO `commissions_types` (actif, created, nom) VALUES ('1', CURRENT_TIMESTAMP, 'Commission d''appel');
INSERT INTO `commissions_types` (actif, created, nom) VALUES ('1', CURRENT_TIMESTAMP, 'Commission d''évaluation');