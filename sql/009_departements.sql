DROP TABLE IF EXISTS departements;
CREATE TABLE departements (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    section_id INT,
    nom VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO departements (id, actif, section_id, nom) VALUES (1, '1', 1, 'SSC A');
INSERT INTO departements (id, actif, section_id, nom) VALUES (2, '1', 1, 'SSC B');
INSERT INTO departements (id, actif, section_id, nom) VALUES (3, '1', 1, 'SSC C');
INSERT INTO departements (id, actif, section_id, nom) VALUES (4, '1', 2, 'SSF X');
INSERT INTO departements (id, actif, section_id, nom) VALUES (5, '1', 2, 'SSF Y');
INSERT INTO departements (id, actif, section_id, nom) VALUES (6, '1', 2, 'SSF Z');