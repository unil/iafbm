DROP TABLE IF EXISTS commissions_creations;
CREATE TABLE commissions_creations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    commission_id INT NOT NULL,
    date_decision DATE DEFAULT NULL,
    date_preavis DATE DEFAULT NULL,
    date_autorisation DATE DEFAULT NULL,
    date_annonce DATE DEFAULT NULL,
    date_composition DATE DEFAULT NULL,
    date_composition_validation DATE DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;