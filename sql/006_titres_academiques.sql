DROP TABLE IF EXISTS titres_academiques;
CREATE TABLE titres_academiques (
    id INT NOT NULL AUTO_INCREMENT,
    abreviation VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO titres_academiques (id, abreviation, nom) VALUES (1, 'PO', 'Professeur ordinaire');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (2, 'PO ad personam', 'Professeur ordinaire ad personam');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (3, 'PAS', 'Professeur associé');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (4, 'PAS ad personam', 'Professeur associé ad personam');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (5, 'PAST', 'Professeur assistant');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (6, 'PAST PTC', 'Professeur assistant en prétitularisation conditionnelle');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (7, 'PAST boursier', 'Professeur assistant boursier');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (8, 'PAST suppléant', 'Professeur assistant suppléant');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (9, 'PTIT', 'Professeur titulaire');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (10, 'PI avec indemnités', 'Professeur invité avec indemnités');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (11, 'PI sans incidence financière', 'Professeur invité sans incidence financière');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (12, 'PD-MER1', 'Privat-docent et Maître d’enseignement et de recherche, type 1');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (13, 'PD-MER2', 'Privat-docent et Maître d’enseignement et de recherche, type 2');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (14, 'PD', 'Privat-docent');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (15, 'PD sans incidence financière', 'Privat-docent sans incidence financière');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (16, 'MER1', 'Maître d''enseignement et de recherche, type 1');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (17, 'MER2', 'Maître d''enseignement et de recherche, type 2');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (18, 'CC avec indemnités', 'Chargé de cours avec indemnités');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (19, 'CC sans incidence financière', 'Chargé de cours sans incidence financière');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (20, 'MA', 'Maître assistant');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (21, 'MA suppléant', 'Maître assistant suppléant');
INSERT INTO titres_academiques (id, abreviation, nom) VALUES (22, 'Aucun', 'Aucun');