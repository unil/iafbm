DROP TABLE IF EXISTS commissions_travails;
CREATE TABLE commissions_travails (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    commission_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
