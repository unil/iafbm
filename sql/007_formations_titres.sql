DROP TABLE IF EXISTS formations_titres;
CREATE TABLE formations_titres (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(32),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO formations_titres (id, nom) VALUES (1, 'MD');
INSERT INTO formations_titres (id, nom) VALUES (2, 'PhD');
INSERT INTO formations_titres (id, nom) VALUES (3, 'MD-PhD');
INSERT INTO formations_titres (id, nom) VALUES (4, 'Autre');
INSERT INTO formations_titres (id, nom) VALUES (5, 'Inconnu');