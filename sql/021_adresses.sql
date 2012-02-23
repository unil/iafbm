DROP TABLE IF EXISTS adresses;
CREATE TABLE adresses (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,

    adresse_type_id INT NOT NULL,
    rue TEXT,
    npa VARCHAR(255),
    lieu VARCHAR(255),
    pays_id INT,

    PRIMARY KEY (id),
    FOREIGN KEY (adresse_type_id) REFERENCES adresses_types(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;