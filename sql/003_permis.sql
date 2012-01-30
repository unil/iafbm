DROP TABLE IF EXISTS permis;
CREATE TABLE permis (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO permis (id, actif, created, nom) VALUES (1, '1', CURRENT_TIMESTAMP, 'Permis A');
INSERT INTO permis (id, actif, created, nom) VALUES (2, '1', CURRENT_TIMESTAMP, 'Permis B');