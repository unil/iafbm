DROP TABLE IF EXISTS personnes_adresses;
CREATE TABLE personnes_adresses (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,

    personne_id INT,
    adresse_id INT,

    defaut BOOLEAN NULL DEFAULT false,

    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (adresse_id) REFERENCES adresses(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;