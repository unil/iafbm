DROP TABLE IF EXISTS personnes_types;
CREATE TABLE personnes_types (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO personnes_types (id, nom) VALUES (1, 'Enseignant');
INSERT INTO personnes_types (id, nom) VALUES (2, 'Externe');
INSERT INTO personnes_types (id, nom) VALUES (3, 'Etudiant');