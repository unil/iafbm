DROP TABLE IF EXISTS employes;
CREATE TABLE employes (
    id INT NOT NULL AUTO_INCREMENT,
    personne_id INT NOT NULL,
    section INT NOT NULL,
    date_retraite DATE,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
