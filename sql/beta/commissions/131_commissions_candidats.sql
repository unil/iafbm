DROP TABLE IF EXISTS commissions_candidats;
CREATE TABLE commissions_candidats (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    commission_id INT NOT NULL,
    personne_id INT NOT NULL, /* attention aux table 'héritées' étudiant, externe, employé, ... */
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id)
) TYPE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
