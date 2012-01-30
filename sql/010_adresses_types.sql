DROP TABLE IF EXISTS adresses_types;
CREATE TABLE adresses_types (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    nom VARCHAR(16) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO adresses_types (id, actif, created, nom) VALUES (1, '1', CURRENT_TIMESTAMP, 'Professionnelle');
INSERT INTO adresses_types (id, actif, created, nom) VALUES (2, '1', CURRENT_TIMESTAMP, 'Privée');
INSERT INTO adresses_types (id, actif, created, nom) VALUES (3, '1', CURRENT_TIMESTAMP, 'Autre');
