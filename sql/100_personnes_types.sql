DROP TABLE IF EXISTS personnes_types;
CREATE TABLE personnes_types (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO personnes_types (id, actif, created, nom) VALUES (1, '1', CURRENT_TIMESTAMP, 'Enseignant');
INSERT INTO personnes_types (id, actif, created, nom) VALUES (2, '1', CURRENT_TIMESTAMP, 'Externe');
INSERT INTO personnes_types (id, actif, created, nom) VALUES (3, '1', CURRENT_TIMESTAMP, 'Etudiant');