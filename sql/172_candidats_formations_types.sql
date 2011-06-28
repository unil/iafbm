DROP TABLE IF EXISTS candidats_formations_types;
CREATE TABLE candidats_formations_types (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(32),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO candidats_formations_types (id, nom) VALUES (1, 'MD');
INSERT INTO candidats_formations_types (id, nom) VALUES (2, 'PhD');
INSERT INTO candidats_formations_types (id, nom) VALUES (3, 'MD-PhD');
INSERT INTO candidats_formations_types (id, nom) VALUES (4, 'Autre');