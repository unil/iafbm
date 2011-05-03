DROP TABLE IF EXISTS commissions_fonctions;
CREATE TABLE commissions_fonctions (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    PRIMARY KEY (id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
