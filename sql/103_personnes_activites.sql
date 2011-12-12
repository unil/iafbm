DROP TABLE IF EXISTS personnes_activites;
CREATE TABLE personnes_activites (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,

    personne_id INT,
    section_id INT,
    activite_id INT,
    departement_id INT,
    date_contrat DATE NULL DEFAULT NULL,
    debut_mandat DATE NULL DEFAULT NULL,
    fin_mandat DATE DEFAULT NULL,
    taux_activite TINYINT,

    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (activite_id) REFERENCES activites(id),
    FOREIGN KEY (departement_id) REFERENCES departements(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;