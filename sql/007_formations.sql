DROP TABLE IF EXISTS formations;
CREATE TABLE formations (
    id INT NOT NULL AUTO_INCREMENT,
    abreviation VARCHAR(32),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO formations (id, abreviation) VALUES (1, 'MD');
INSERT INTO formations (id, abreviation) VALUES (2, 'PhD');
INSERT INTO formations (id, abreviation) VALUES (3, 'MD-PhD');
INSERT INTO formations (id, abreviation) VALUES (4, 'Autre');
INSERT INTO formations (id, abreviation) VALUES (5, 'Inconnu');