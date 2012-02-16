DROP TABLE IF EXISTS candidats_formations;
CREATE TABLE candidats_formations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    candidat_id INT,
    formation_id INT,
    lieu_these VARCHAR(255),
    date_these DATE DEFAULT NULL,
    commentaire VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (candidat_id) REFERENCES candidats(id),
    FOREIGN KEY (formation_id) REFERENCES formations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;