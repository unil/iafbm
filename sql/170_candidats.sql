/*
-- Abandoned version where 'candidats' would be a 'personne'
DROP TABLE IF EXISTS commissions_candidats;
CREATE TABLE commissions_candidats (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    personne_id INT NOT NULL,
    commission_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
*/

/* TODO: Create foreign tables: candidats_formations(id,date,lieu), candidats_positions(id,fonction,lieu) */
DROP TABLE IF EXISTS candidats;
CREATE TABLE candidats (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    commission_id INT,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    genre_id INT,
    etatcivil_id INT,
    date_naissance DATE,
    nombre_enfants SMALLINT,
    no_avs VARCHAR(255),
    email VARCHAR(255),
    adresse_pro VARCHAR(255),
    npa_pro VARCHAR(255),
    lieu_pro VARCHAR(255),
    pays_pro_id INT,
    telephone_pro VARCHAR(255),
    adresse_pri VARCHAR(255),
    npa_pri VARCHAR(255),
    lieu_pri VARCHAR(255),
    pays_pri_id INT,
    telephone_pri VARCHAR(255),
    PRIMARY KEY (id)/*,
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (genre_id) REFERENCES genres(id),
    FOREIGN KEY (etatcivil_id) REFERENCES etatscivils(id),
    FOREIGN KEY (pays_pro_id) REFERENCES pays(id),
    FOREIGN KEY (pays_pri_id) REFERENCES pays(id)*/
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;