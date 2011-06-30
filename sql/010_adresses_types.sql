DROP TABLE IF EXISTS adresses_types;
CREATE TABLE adresses_types (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(16) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO adresses_types (id, nom) VALUES (1, 'Professionnelle');
INSERT INTO adresses_types (id, nom) VALUES (2, 'Privée');
INSERT INTO adresses_types (id, nom) VALUES (3, 'Autre');
