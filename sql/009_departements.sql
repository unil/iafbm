DROP TABLE IF EXISTS departements;
CREATE TABLE departements (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    section_id INT,
    nom VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;