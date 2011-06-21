DROP TABLE IF EXISTS etatscivils;
CREATE TABLE etatscivils (
    id INT NOT NULL,
    nom VARCHAR(16) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO etatscivils (id, nom) VALUES (1, 'Célibataire');
INSERT INTO etatscivils (id, nom) VALUES (2, 'Marié');
INSERT INTO etatscivils (id, nom) VALUES (3, 'Divorcé');
INSERT INTO etatscivils (id, nom) VALUES (4, 'Voeuf');