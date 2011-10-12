DROP TABLE IF EXISTS personnes_formations;
CREATE TABLE personnes_formations (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    personne_id INT,
    formation_id INT,
    lieu_these VARCHAR(255),
    date_these DATE DEFAULT NULL,
    commentaire VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (formation_id) REFERENCES formations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;