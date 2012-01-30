DROP TABLE IF EXISTS sections;
CREATE TABLE sections (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    code VARCHAR(8) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO sections (id, actif, created, code, nom) VALUES (1, '1', CURRENT_TIMESTAMP, 'SSC', 'Section des sciences cliniques');
INSERT INTO sections (id, actif, created, code, nom) VALUES (2, '1', CURRENT_TIMESTAMP, 'SSF', 'Section des sciences fondamentales');