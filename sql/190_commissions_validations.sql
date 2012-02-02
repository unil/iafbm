DROP TABLE IF EXISTS commissions_validations_etats;
CREATE TABLE commissions_validations_etats (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO commissions_validations_etats (id, actif, created, nom) VALUES (1, '1', CURRENT_TIMESTAMP, '-');
INSERT INTO commissions_validations_etats (id, actif, created, nom) VALUES (2, '1', CURRENT_TIMESTAMP, 'Oui');
INSERT INTO commissions_validations_etats (id, actif, created, nom) VALUES (3, '1', CURRENT_TIMESTAMP, 'Non');
INSERT INTO commissions_validations_etats (id, actif, created, nom) VALUES (4, '1', CURRENT_TIMESTAMP, 'Pas de décision');

DROP TABLE IF EXISTS commissions_validations;
CREATE TABLE commissions_validations (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    commission_id INT NOT NULL,
    decanat_date DATE DEFAULT NULL,
    decanat_etat INT DEFAULT 1,
    decanat_commentaire TEXT,
    dg_date DATE DEFAULT NULL,
    dg_commentaire TEXT,
    cf_date DATE DEFAULT NULL,
    cf_etat INT DEFAULT 1,
    cf_commentaire TEXT,
    cdir_date DATE DEFAULT NULL,
    cdir_etat INT DEFAULT 1,
    cdir_commentaire TEXT,
    reception_rapport DATE DEFAULT NULL,
    envoi_proposition_nomination DATE DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (decanat_etat) REFERENCES commissions_validations_etats(id),
    FOREIGN KEY (cf_etat) REFERENCES commissions_validations_etats(id),
    FOREIGN KEY (cdir_etat) REFERENCES commissions_validations_etats(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;