DROP TABLE IF EXISTS commissions_fonctions;
CREATE TABLE commissions_fonctions (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (1, '1', CURRENT_TIMESTAMP, 'Président', 'Président de la commission');
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (2, '1', CURRENT_TIMESTAMP, 'Membre', 'Membre de la commission');
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (3, '1', CURRENT_TIMESTAMP, 'Expert extérieur', 'Expert extérieur');
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (4, '1', CURRENT_TIMESTAMP, 'Délégué à l''égalité', 'Délégué à l''égalité');
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (5, '1', CURRENT_TIMESTAMP, 'Représentant du corps professoral', 'Représentant du corps professoral');
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (6, '1', CURRENT_TIMESTAMP, 'Représentant du corps intermédiaire', 'Représentant du corps intermédiaire');
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (7, '1', CURRENT_TIMESTAMP, 'Représentant des étudiants', 'Représentant des étudiants');
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (8, '1', CURRENT_TIMESTAMP, 'Représentant du Décanat', 'Représentant du Décanat');
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description) VALUES (9, '1', CURRENT_TIMESTAMP, 'Représentant de la DG CHUV', 'Représentant de la DG CHUV');