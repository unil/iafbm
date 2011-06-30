DROP TABLE IF EXISTS departements;
CREATE TABLE departements (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(32),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO departements (id, nom) VALUES (1, 'A');
INSERT INTO departements (id, nom) VALUES (2, 'B');
INSERT INTO departements (id, nom) VALUES (3, 'C');