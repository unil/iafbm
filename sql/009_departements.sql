DROP TABLE IF EXISTS departements;
CREATE TABLE departements (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    section_id INT,
    nom VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO departements (id, actif, created, section_id, nom) VALUES (1, '1', CURRENT_TIMESTAMP, 1, 'SSC A');
INSERT INTO departements (id, actif, created, section_id, nom) VALUES (2, '1', CURRENT_TIMESTAMP, 1, 'SSC B');
INSERT INTO departements (id, actif, created, section_id, nom) VALUES (3, '1', CURRENT_TIMESTAMP, 1, 'SSC C');
INSERT INTO departements (id, actif, created, section_id, nom) VALUES (4, '1', CURRENT_TIMESTAMP, 2, 'SSF X');
INSERT INTO departements (id, actif, created, section_id, nom) VALUES (5, '1', CURRENT_TIMESTAMP, 2, 'SSF Y');
INSERT INTO departements (id, actif, created, section_id, nom) VALUES (6, '1', CURRENT_TIMESTAMP, 2, 'SSF Z');