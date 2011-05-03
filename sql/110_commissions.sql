DROP TABLE IF EXISTS commissions;
CREATE TABLE commissions (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    commission_type_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_type_id) REFERENCES commissions_types(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
