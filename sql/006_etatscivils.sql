DROP TABLE IF EXISTS etatscivils;
CREATE TABLE etatscivils (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(16) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO etatscivils (id, actif, nom) VALUES (1, '1', 'Célibataire');
INSERT INTO etatscivils (id, actif, nom) VALUES (2, '1', 'Marié');
INSERT INTO etatscivils (id, actif, nom) VALUES (3, '1', 'Divorcé');
INSERT INTO etatscivils (id, actif, nom) VALUES (4, '1', 'Veuf');