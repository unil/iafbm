DROP TABLE IF EXISTS formations;
CREATE TABLE formations (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    abreviation VARCHAR(32),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO formations (id, actif, created, abreviation) VALUES (1, '1', CURRENT_TIMESTAMP, 'MD');
INSERT INTO formations (id, actif, created, abreviation) VALUES (2, '1', CURRENT_TIMESTAMP, 'PhD');
INSERT INTO formations (id, actif, created, abreviation) VALUES (3, '1', CURRENT_TIMESTAMP, 'MD-PhD');
INSERT INTO formations (id, actif, created, abreviation) VALUES (4, '1', CURRENT_TIMESTAMP, 'Autre');
INSERT INTO formations (id, actif, created, abreviation) VALUES (5, '1', CURRENT_TIMESTAMP, 'Inconnu');