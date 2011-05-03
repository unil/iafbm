DROP TABLE IF EXISTS commissions_propositions_nominations;
CREATE TABLE commissions_propositions_nominations (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    commission_finalisation_id INT NOT NULL,
    commission_candidat_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_finalisation_id) REFERENCES commissions_finalisations(id),
    FOREIGN KEY (commission_candidat_id) REFERENCES commissions_candidats(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
