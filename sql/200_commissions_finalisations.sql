DROP TABLE IF EXISTS commissions_finalisations;
CREATE TABLE commissions_finalisations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    commission_id INT NOT NULL,
    candidat_id INT,
    reception_contrat_date DATE DEFAULT NULL,
    reception_contrat_etat BOOLEAN DEFAULT false,
    reception_contrat_commentaire TEXT,
    debut_activite DATE DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (candidat_id) REFERENCES candidats(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE UNIQUE INDEX commission_id_unique ON commissions_finalisations(commission_id);