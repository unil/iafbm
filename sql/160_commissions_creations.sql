DROP TABLE IF EXISTS commissions_creations_etats;
CREATE TABLE commissions_creations_etats (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS commissions_creations;
CREATE TABLE commissions_creations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    commission_id INT NOT NULL,
    date_decision DATE DEFAULT NULL,
    date_preavis_decanat DATE DEFAULT NULL,
    etat_preavis_decanat INT NULL,
    date_preavis_ccp DATE DEFAULT NULL,
    etat_preavis_ccp INT NULL,
    date_preavis_cpa DATE DEFAULT NULL,
    etat_preavis_cpa INT NULL,
    date_autorisation DATE DEFAULT NULL,
    etat_autorisation INT NULL,
    date_annonce DATE DEFAULT NULL,
    date_composition DATE DEFAULT NULL,
    date_composition_validation DATE DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (etat_preavis_decanat) REFERENCES commissions_creations_etats(id),
    FOREIGN KEY (etat_preavis_ccp) REFERENCES commissions_creations_etats(id),
    FOREIGN KEY (etat_preavis_cpa) REFERENCES commissions_creations_etats(id),
    FOREIGN KEY (etat_autorisation) REFERENCES commissions_creations_etats(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE UNIQUE INDEX commission_id_unique ON commissions_creations(commission_id);