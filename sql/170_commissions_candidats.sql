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
    description TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;