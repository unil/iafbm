DROP TABLE IF EXISTS adresses;
CREATE TABLE adresses (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,

    adresse_type_id INT,
    rue TEXT,
    npa VARCHAR(255),
    lieu VARCHAR(255),
    pays_id INT,
    telephone VARCHAR(255),

    PRIMARY KEY (id),
    FOREIGN KEY (adresse_type_id) REFERENCES adresses_types(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;