DROP TABLE IF EXISTS activites_types;
CREATE TABLE activites_types (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS activites;
CREATE TABLE activites (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT TRUE,

    activite_type_id INT,
    section_id INT,
    abreviation VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (activite_type_id) REFERENCES activites_types(id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;