DROP TABLE IF EXISTS titres;
CREATE TABLE titres (
    id INT NOT NULL AUTO_INCREMENT,
    abreviation VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO titres (id, abreviation, nom) VALUES (1, 'Aucun', 'Aucun');
INSERT INTO titres (id, abreviation, nom) VALUES (2, 'MA suppléant', 'MA suppléant');
INSERT INTO titres (id, abreviation, nom) VALUES (3, 'MA', 'MA');
INSERT INTO titres (id, abreviation, nom) VALUES (4, 'PD et MER-2', 'PD et MER-2');
INSERT INTO titres (id, abreviation, nom) VALUES (5, 'PD et MER-1', 'PD et MER-1');
INSERT INTO titres (id, abreviation, nom) VALUES (6, 'CC sans incidence financière', 'CC sans incidence financière');
INSERT INTO titres (id, abreviation, nom) VALUES (7, 'CC avec indemnités', 'CC avec indemnités');
INSERT INTO titres (id, abreviation, nom) VALUES (8, 'CC', 'CC');
INSERT INTO titres (id, abreviation, nom) VALUES (9, 'PD sans incidence financière', 'PD sans incidence financière');
INSERT INTO titres (id, abreviation, nom) VALUES (10, 'PD', 'PD');
INSERT INTO titres (id, abreviation, nom) VALUES (11, 'MER-2 suppléant', 'MER-2 suppléant');
INSERT INTO titres (id, abreviation, nom) VALUES (12, 'MER-2', 'MER-2');
INSERT INTO titres (id, abreviation, nom) VALUES (13, 'MER-1 suppléant', 'MER-1 suppléant');
INSERT INTO titres (id, abreviation, nom) VALUES (14, 'MER-1', 'MER-1');
INSERT INTO titres (id, abreviation, nom) VALUES (15, 'PInvité sans incidence financière', 'PInvité sans incidence financière');
INSERT INTO titres (id, abreviation, nom) VALUES (16, 'PInvité avec indemnités', 'PInvité avec indemnités');
INSERT INTO titres (id, abreviation, nom) VALUES (17, 'PInvité', 'PInvité');
INSERT INTO titres (id, abreviation, nom) VALUES (18, 'Ptit', 'Ptit');
INSERT INTO titres (id, abreviation, nom) VALUES (19, 'PAST suppléant', 'PAST suppléant');
INSERT INTO titres (id, abreviation, nom) VALUES (20, 'PAST Boursier', 'PAST Boursier');
INSERT INTO titres (id, abreviation, nom) VALUES (21, 'PAST en PTC', 'PAST en PTC');
INSERT INTO titres (id, abreviation, nom) VALUES (22, 'PAST', 'PAST');
INSERT INTO titres (id, abreviation, nom) VALUES (23, 'PA ad personam', 'PA ad personam');
INSERT INTO titres (id, abreviation, nom) VALUES (24, 'PA', 'PA');
INSERT INTO titres (id, abreviation, nom) VALUES (25, 'PO ad personam', 'PO ad personam');
INSERT INTO titres (id, abreviation, nom) VALUES (26, 'PO', 'PO');
