DROP TABLE IF EXISTS activites_types;
CREATE TABLE activites_types (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO activites_types VALUES (1, 'Fonction');
INSERT INTO activites_types VALUES (2, 'Titre academique');
INSERT INTO activites_types VALUES (3, 'Mandat');



DROP TABLE IF EXISTS activites;
CREATE TABLE activites (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT TRUE,

    activite_type_id INT,
    section_id INT,
    abreviation VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (activite_type_id) REFERENCES activites_types(id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- SSF Fonctions
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (1, 1, 2, 'Autre F/SSF', 'Autre fonction SSF');
-- SSF Titres
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (2, 2, 2, 'PO', 'Professeur ordinaire');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (3, 2, 2, 'PO ad personam', 'Professeur ordinaire ad personam');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (4, 2, 2, 'PAS', 'Professeur associé');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (5, 2, 2, 'PAS ad personam', 'Professeur associé ad personam');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (6, 2, 2, 'PAST', 'Professeur assistant');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (7, 2, 2, 'PAST PTC', 'Professeur assistant en prétitularisation conditionnelle');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (8, 2, 2, 'PAST boursier', 'Professeur assistant boursier');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (9, 2, 2, 'PAST suppléant', 'Professeur assistant suppléant');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (10, 2, 2, 'PTIT', 'Professeur titulaire');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (11, 2, 2, 'PI avec indemnités', 'Professeur invité avec indemnités');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (12, 2, 2, 'PI sans incidence financière', 'Professeur invité sans incidence financière');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (13, 2, 2, 'PD-MER1', 'Privat-docent et Maître d’enseignement et de recherche, type 1');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (14, 2, 2, 'PD-MER2', 'Privat-docent et Maître d’enseignement et de recherche, type 2');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (15, 2, 2, 'PD', 'Privat-docent');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (16, 2, 2, 'PD sans incidence financière', 'Privat-docent sans incidence financière');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (17, 2, 2, 'MER1', 'Maître d''enseignement et de recherche, type 1');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (18, 2, 2, 'MER2', 'Maître d''enseignement et de recherche, type 2');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (19, 2, 2, 'CC avec indemnités', 'Chargé de cours avec indemnités');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (20, 2, 2, 'CC sans incidence financière', 'Chargé de cours sans incidence financière');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (21, 2, 2, 'MA', 'Maître assistant');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (22, 2, 2, 'MA suppléant', 'Maître assistant suppléant');
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (23, 2, 2, 'Aucun', 'Aucun');
-- SSF Mandats
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (24, 3, 2, 'Autre M/SSF', 'Autre mandat SSF');
-- SSC Fonctions
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (25, 1, 1, 'Autre F/SSC', 'Autre fonction SSC');
-- SSC Titres
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (26, 2, 1, 'Autre T/SSC', 'Autre titre SSC');
-- SSC Mandats
INSERT INTO activites (id, activite_type_id, section_id, abreviation, nom) VALUES (27, 3, 1, 'Autre M/SSC', 'Autre mandat SSC');
