DROP TABLE IF EXISTS permis;
CREATE TABLE permis (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO permis (id, actif, nom) VALUES (1, '1', 'Permis A');
INSERT INTO permis (id, actif, nom) VALUES (2, '1', 'Permis B');