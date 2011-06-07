DROP TABLE IF EXISTS commissions_travails;
CREATE TABLE commissions_travails (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    commission_id INT NOT NULL,
    loco_primo INT DEFAULT NULL,
    loco_secondo INT DEFAULT NULL,
    loco_tertio INT DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (loco_primo) REFERENCES commissions_candidats(id),
    FOREIGN KEY (loco_secondo) REFERENCES commissions_candidats(id),
    FOREIGN KEY (loco_tertio) REFERENCES commissions_candidats(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;