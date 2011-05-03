DROP TABLE IF EXISTS commissions_seances;
CREATE TABLE commissions_seances (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    commission_travail_id INT NOT NULL,
    commission_seance_type_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_travail_id) REFERENCES commission_travails(id),
    FOREIGN KEY (commission_seance_type_id) REFERENCES commissions_seances_type(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
