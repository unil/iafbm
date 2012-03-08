DROP TABLE IF EXISTS rattachements;
CREATE TABLE rattachements (
    id INT NOT NULL AUTO_INCREMENT,
    id_unil INT,
    id_chuv INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    section_id INT,
    nom VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;