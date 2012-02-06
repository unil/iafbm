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
    position TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (1, '1', CURRENT_TIMESTAMP, 'Président', 'Président de la commission', 1);
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (2, '1', CURRENT_TIMESTAMP, 'Expert extérieur', 'Expert extérieur', 7);
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (3, '1', CURRENT_TIMESTAMP, 'Délégué à l''égalité', 'Délégué à l''égalité', 8);
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (4, '1', CURRENT_TIMESTAMP, 'Représentant du corps professoral', 'Représentant du corps professoral', 3);
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (5, '1', CURRENT_TIMESTAMP, 'Représentant du corps intermédiaire', 'Représentant du corps intermédiaire', 4);
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (6, '1', CURRENT_TIMESTAMP, 'Représentant des étudiants', 'Représentant des étudiants', 5);
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (7, '1', CURRENT_TIMESTAMP, 'Représentant du Décanat', 'Représentant du Décanat', 2);
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (8, '1', CURRENT_TIMESTAMP, 'Représentant de la DG CHUV', 'Représentant de la DG CHUV', 6);
INSERT INTO `commissions_fonctions` (id, actif, created, nom, description, position) VALUES (9, '1', CURRENT_TIMESTAMP, 'Invité', 'Invité', 9);