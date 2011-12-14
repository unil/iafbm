DROP TABLE IF EXISTS cantons;
CREATE TABLE cantons (
    id INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(2) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO cantons VALUES (1, 'AG', 'Argovie');
INSERT INTO cantons VALUES (2, 'AR', 'Appenzell Rhodes extérieures');
INSERT INTO cantons VALUES (3, 'AI', 'Appenzell Rhodes intérieures');
INSERT INTO cantons VALUES (4, 'BL', 'Bâle Campagne');
INSERT INTO cantons VALUES (5, 'BS', 'Bâle Ville');
INSERT INTO cantons VALUES (6, 'BE', 'Berne');
INSERT INTO cantons VALUES (7, 'FR', 'Fribourg');
INSERT INTO cantons VALUES (8, 'GE', 'Genève');
INSERT INTO cantons VALUES (9, 'GL', 'Glaris');
INSERT INTO cantons VALUES (10, 'GR', 'Grisons');
INSERT INTO cantons VALUES (11, 'JU', 'Jura');
INSERT INTO cantons VALUES (12, 'LU', 'Lucerne');
INSERT INTO cantons VALUES (13, 'NE', 'Neuchâtel');
INSERT INTO cantons VALUES (14, 'NW', 'Nidwald');
INSERT INTO cantons VALUES (15, 'OW', 'Obwald');
INSERT INTO cantons VALUES (16, 'SG', 'Saint Gall');
INSERT INTO cantons VALUES (17, 'SH', 'Schaffhouse');
INSERT INTO cantons VALUES (18, 'SZ', 'Schwyz');
INSERT INTO cantons VALUES (19, 'SO', 'Soleure');
INSERT INTO cantons VALUES (20, 'TG', 'Thurgovie');
INSERT INTO cantons VALUES (21, 'TI', 'Tessin');
INSERT INTO cantons VALUES (22, 'UR', 'Uri');
INSERT INTO cantons VALUES (23, 'VD', 'Vaud');
INSERT INTO cantons VALUES (24, 'VS', 'Valais');
INSERT INTO cantons VALUES (25, 'ZG', 'Zoug');
INSERT INTO cantons VALUES (26, 'ZH', 'Zurich');