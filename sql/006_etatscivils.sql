DROP TABLE IF EXISTS etatscivils;
CREATE TABLE etatscivils (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    nom VARCHAR(16) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO etatscivils (id, actif, created, nom) VALUES (1, '1', CURRENT_TIMESTAMP, 'Célibataire');
INSERT INTO etatscivils (id, actif, created, nom) VALUES (2, '1', CURRENT_TIMESTAMP, 'Marié');
INSERT INTO etatscivils (id, actif, created, nom) VALUES (3, '1', CURRENT_TIMESTAMP, 'Divorcé');
INSERT INTO etatscivils (id, actif, created, nom) VALUES (4, '1', CURRENT_TIMESTAMP, 'Veuf');