DROP TABLE IF EXISTS commissions_validations_etats;
CREATE TABLE commissions_validations_etats (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS commissions_validations;
CREATE TABLE commissions_validations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    commission_id INT NOT NULL,
    decanat_validation_date DATE DEFAULT NULL,
    decanat_validation_etat INT DEFAULT 1,
    decanat_validation_commentaire TEXT,
    dg_commentaire_date DATE DEFAULT NULL,
    dg_commentaire_commentaire TEXT,
    cf_validation_date DATE DEFAULT NULL,
    cf_validation_etat INT DEFAULT 1,
    cf_validation_commentaire TEXT,
    cdir_validation_date DATE DEFAULT NULL,
    cdir_validation_etat INT DEFAULT 1,
    cdir_validation_commentaire TEXT,
    cdir_nomination_date DATE DEFAULT NULL,
    cdir_nomination_etat INT DEFAULT 1,
    cdir_nomination_commentaire TEXT,
    reception_rapport DATE DEFAULT NULL,
    envoi_proposition_nomination DATE DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (decanat_validation_etat) REFERENCES commissions_validations_etats(id),
    FOREIGN KEY (cf_validation_etat) REFERENCES commissions_validations_etats(id),
    FOREIGN KEY (cdir_validation_etat) REFERENCES commissions_validations_etats(id),
    FOREIGN KEY (cdir_nomination_etat) REFERENCES commissions_validations_etats(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE UNIQUE INDEX commission_id_unique ON commissions_validations(commission_id);