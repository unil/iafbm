DROP TABLE IF EXISTS candidats;
CREATE TABLE candidats (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    commission_id INT,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    genre_id INT,
    etatcivil_id INT,
    pays_id INT,
    date_naissance DATE,
    nombre_enfants SMALLINT,
    no_avs VARCHAR(255),
    adresse_pro TEXT,
    npa_pro VARCHAR(255),
    lieu_pro VARCHAR(255),
    pays_pro_id INT,
    telephone_pro_countrycode VARCHAR(3),
    telephone_pro VARCHAR(255),
    email_pro VARCHAR(255),
    adresse_pri VARCHAR(255),
    npa_pri VARCHAR(255),
    lieu_pri VARCHAR(255),
    pays_pri_id INT,
    telephone_pri_countrycode VARCHAR(3),
    telephone_pri VARCHAR(255),
    email_pri VARCHAR(255),
    adresse_defaut VARCHAR(3) DEFAULT NULL,
    position_actuelle_fonction TEXT,
    position_actuelle_lieu VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (genre_id) REFERENCES genres(id),
    FOREIGN KEY (etatcivil_id) REFERENCES etatscivils(id),
    FOREIGN KEY (pays_id) REFERENCES pays(id),
    FOREIGN KEY (pays_pro_id) REFERENCES pays(id),
    FOREIGN KEY (pays_pri_id) REFERENCES pays(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;