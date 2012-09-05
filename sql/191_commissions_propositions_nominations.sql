DROP TABLE IF EXISTS commissions_propositions_nominations;
CREATE TABLE commissions_propositions_nominations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    commission_id INT NOT NULL,
    candidat_id INT,
    institut TEXT,
    objet TEXT,
    activite_id INT,
    contrat_debut DATE NULL DEFAULT NULL,
    contrat_fin DATE,
    contrat_taux TINYINT UNSIGNED,
    indemnite INT UNSIGNED,
    discipline_generale TEXT,
    observations TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (candidat_id) REFERENCES candidats(id),
    FOREIGN KEY (activite_id) REFERENCES activites(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE UNIQUE INDEX commission_id_unique ON commissions_propositions_nominations(commission_id);