DROP TABLE IF EXISTS personnes;
CREATE TABLE personnes (
    id INT NOT NULL AUTO_INCREMENT,
    id_unil INT,
    id_chuv INT,
    id_adifac INT,

    actif BOOLEAN NOT NULL DEFAULT TRUE,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,

    personne_type_id INT,

    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    genre_id INT,
    date_naissance DATE,
    no_avs VARCHAR(255),
    canton_id INT,
    pays_id INT,
    permis_id INT,

    titre_lecon_inaug VARCHAR(100),
    date_lecon_inaug DATE,
    PRIMARY KEY (id),
    FOREIGN KEY (personne_type_id) REFERENCES personnes_types(id),
    FOREIGN KEY (genre_id) REFERENCES genres(id),
    FOREIGN KEY (pays_id) REFERENCES pays(id),
    FOREIGN KEY (canton_id) REFERENCES cantons(id),
    FOREIGN KEY (permis_id) REFERENCES permis(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
