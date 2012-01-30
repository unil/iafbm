DROP TABLE IF EXISTS cantons;
CREATE TABLE cantons (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    code VARCHAR(2) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO cantons (id, actif, created, code, nom) VALUES (1, '1', CURRENT_TIMESTAMP, 'AG', 'Argovie');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (2, '1', CURRENT_TIMESTAMP, 'AR', 'Appenzell Rhodes extérieures');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (3, '1', CURRENT_TIMESTAMP, 'AI', 'Appenzell Rhodes intérieures');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (4, '1', CURRENT_TIMESTAMP, 'BL', 'Bâle Campagne');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (5, '1', CURRENT_TIMESTAMP, 'BS', 'Bâle Ville');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (6, '1', CURRENT_TIMESTAMP, 'BE', 'Berne');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (7, '1', CURRENT_TIMESTAMP, 'FR', 'Fribourg');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (8, '1', CURRENT_TIMESTAMP, 'GE', 'Genève');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (9, '1', CURRENT_TIMESTAMP, 'GL', 'Glaris');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (10, '1', CURRENT_TIMESTAMP, 'GR', 'Grisons');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (11, '1', CURRENT_TIMESTAMP, 'JU', 'Jura');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (12, '1', CURRENT_TIMESTAMP, 'LU', 'Lucerne');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (13, '1', CURRENT_TIMESTAMP, 'NE', 'Neuchâtel');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (14, '1', CURRENT_TIMESTAMP, 'NW', 'Nidwald');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (15, '1', CURRENT_TIMESTAMP, 'OW', 'Obwald');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (16, '1', CURRENT_TIMESTAMP, 'SG', 'Saint Gall');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (17, '1', CURRENT_TIMESTAMP, 'SH', 'Schaffhouse');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (18, '1', CURRENT_TIMESTAMP, 'SZ', 'Schwyz');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (19, '1', CURRENT_TIMESTAMP, 'SO', 'Soleure');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (20, '1', CURRENT_TIMESTAMP, 'TG', 'Thurgovie');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (21, '1', CURRENT_TIMESTAMP, 'TI', 'Tessin');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (22, '1', CURRENT_TIMESTAMP, 'UR', 'Uri');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (23, '1', CURRENT_TIMESTAMP, 'VD', 'Vaud');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (24, '1', CURRENT_TIMESTAMP, 'VS', 'Valais');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (25, '1', CURRENT_TIMESTAMP, 'ZG', 'Zoug');
INSERT INTO cantons (id, actif, created, code, nom) VALUES (26, '1', CURRENT_TIMESTAMP, 'ZH', 'Zurich');