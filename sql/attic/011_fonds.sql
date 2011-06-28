DROP TABLE IF EXISTS fonds;
CREATE TABLE fonds (
    id INT NOT NULL,
    numero INTEGER(12) NOT NULL,
    centre_couts INTEGER(10) NOT NULL,
    etat INTEGER(1) NOT NULL,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS fonds_personnes;
CREATE TABLE fonds_personnes (
    personne_id INT NOT NULL,
    fonds_id INT NOT NULL,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (personne_id, fonds_id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (fonds_id) REFERENCES fonds(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;