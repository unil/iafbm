DROP TABLE IF EXISTS rattachements;
CREATE TABLE rattachements (
    id INT NOT NULL AUTO_INCREMENT,
    id_unil VARCHAR(255),
    id_chuv VARCHAR(255),
    actif BOOLEAN NOT NULL DEFAULT true,
    section_id INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    abreviation VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;