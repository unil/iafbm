DROP TABLE IF EXISTS sections;
CREATE TABLE sections (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    code VARCHAR(8) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO sections (id, actif, code, nom) VALUES (1, '1', 'SSC', 'Section des sciences cliniques');
INSERT INTO sections (id, actif, code, nom) VALUES (2, '1', 'SSF', 'Section des sciences fondamentales');