DROP TABLE IF EXISTS personnes_types;
CREATE TABLE personnes_types (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO personnes_types (id, actif, nom) VALUES (1, '1', 'Enseignant');
INSERT INTO personnes_types (id, actif, nom) VALUES (2, '1', 'Externe');
INSERT INTO personnes_types (id, actif, nom) VALUES (3, '1', 'Etudiant');