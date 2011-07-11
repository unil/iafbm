DROP TABLE IF EXISTS commissions_membres;
CREATE TABLE commissions_membres (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    taux SMALLINT NOT NULL DEFAULT 100,
    personne_id INT NOT NULL,
    commission_id INT NOT NULL,
    commission_fonction_id INT NOT NULL,
    titre_academique_id INT,
    departement_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (commission_fonction_id) REFERENCES commissions_fonctions(id),
    FOREIGN KEY (titre_academique_id) REFERENCES titres_academiques(id),
    FOREIGN KEY (departement_id) REFERENCES departements(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE UNIQUE INDEX unique_commission_membre ON commissions_membres (personne_id, commission_id);