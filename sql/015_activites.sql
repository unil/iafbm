DROP TABLE IF EXISTS activites_noms;
CREATE TABLE activites_noms (
    id INT NOT NULL AUTO_INCREMENT,
    id_unil INT,
    id_chuv INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(255) NOT NULL,
    abreviation VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



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

    section_id INT,
    activite_type_id INT,
    activite_nom_id INT,

    PRIMARY KEY (id),
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (activite_type_id) REFERENCES activites_types(id),
    FOREIGN KEY (activite_nom_id) REFERENCES activites_noms(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE UNIQUE INDEX activites_unique ON activites(section_id, activite_type_id, activite_nom_id);