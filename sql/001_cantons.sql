DROP TABLE IF EXISTS cantons;
CREATE TABLE cantons (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    code VARCHAR(2) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO cantons (id, actif, code, nom) VALUES (1, '1', 'AG', 'Argovie');
INSERT INTO cantons (id, actif, code, nom) VALUES (2, '1', 'AR', 'Appenzell Rhodes extérieures');
INSERT INTO cantons (id, actif, code, nom) VALUES (3, '1', 'AI', 'Appenzell Rhodes intérieures');
INSERT INTO cantons (id, actif, code, nom) VALUES (4, '1', 'BL', 'Bâle Campagne');
INSERT INTO cantons (id, actif, code, nom) VALUES (5, '1', 'BS', 'Bâle Ville');
INSERT INTO cantons (id, actif, code, nom) VALUES (6, '1', 'BE', 'Berne');
INSERT INTO cantons (id, actif, code, nom) VALUES (7, '1', 'FR', 'Fribourg');
INSERT INTO cantons (id, actif, code, nom) VALUES (8, '1', 'GE', 'Genève');
INSERT INTO cantons (id, actif, code, nom) VALUES (9, '1', 'GL', 'Glaris');
INSERT INTO cantons (id, actif, code, nom) VALUES (10, '1', 'GR', 'Grisons');
INSERT INTO cantons (id, actif, code, nom) VALUES (11, '1', 'JU', 'Jura');
INSERT INTO cantons (id, actif, code, nom) VALUES (12, '1', 'LU', 'Lucerne');
INSERT INTO cantons (id, actif, code, nom) VALUES (13, '1', 'NE', 'Neuchâtel');
INSERT INTO cantons (id, actif, code, nom) VALUES (14, '1', 'NW', 'Nidwald');
INSERT INTO cantons (id, actif, code, nom) VALUES (15, '1', 'OW', 'Obwald');
INSERT INTO cantons (id, actif, code, nom) VALUES (16, '1', 'SG', 'Saint Gall');
INSERT INTO cantons (id, actif, code, nom) VALUES (17, '1', 'SH', 'Schaffhouse');
INSERT INTO cantons (id, actif, code, nom) VALUES (18, '1', 'SZ', 'Schwyz');
INSERT INTO cantons (id, actif, code, nom) VALUES (19, '1', 'SO', 'Soleure');
INSERT INTO cantons (id, actif, code, nom) VALUES (20, '1', 'TG', 'Thurgovie');
INSERT INTO cantons (id, actif, code, nom) VALUES (21, '1', 'TI', 'Tessin');
INSERT INTO cantons (id, actif, code, nom) VALUES (22, '1', 'UR', 'Uri');
INSERT INTO cantons (id, actif, code, nom) VALUES (23, '1', 'VD', 'Vaud');
INSERT INTO cantons (id, actif, code, nom) VALUES (24, '1', 'VS', 'Valais');
INSERT INTO cantons (id, actif, code, nom) VALUES (25, '1', 'ZG', 'Zoug');
INSERT INTO cantons (id, actif, code, nom) VALUES (26, '1', 'ZH', 'Zurich');