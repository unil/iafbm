DROP TABLE IF EXISTS commissions_membres;
CREATE TABLE commissions_membres (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    personne_id INT NOT NULL,
    commission_id INT NOT NULL,
    commission_fonction_id INT NOT NULL,
    activite_id INT,
    departement_id INT,
    version_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (commission_fonction_id) REFERENCES commissions_fonctions(id),
    FOREIGN KEY (activite_id) REFERENCES activites(id),
    FOREIGN KEY (departement_id) REFERENCES departements(id),
    FOREIGN KEY (version_id) REFERENCES versions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;