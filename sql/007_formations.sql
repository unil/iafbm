DROP TABLE IF EXISTS formations;
CREATE TABLE formations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    abreviation VARCHAR(32),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO formations (id, actif, abreviation) VALUES (1, '1', 'MD');
INSERT INTO formations (id, actif, abreviation) VALUES (2, '1', 'PhD');
INSERT INTO formations (id, actif, abreviation) VALUES (3, '1', 'MD-PhD');
INSERT INTO formations (id, actif, abreviation) VALUES (4, '1', 'Autre');
INSERT INTO formations (id, actif, abreviation) VALUES (5, '1', 'Inconnu');