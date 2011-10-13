DROP TABLE IF EXISTS personnes_fonctions;
CREATE TABLE personnes_fonctions (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    util_creat INT,
    util_modif INT,
    actif BOOLEAN NOT NULL DEFAULT true,

    personne_id INT,
    section_id INT,
    titre_academique_id INT,
    taux_activite TINYINT,
    date_contrat DATE DEFAULT NULL,
    debut_mandat DATE DEFAULT NULL,
    fin_mandat DATE DEFAULT NULL,
    fonction_hospitaliere_id INT,
    departement_id INT,

    PRIMARY KEY (id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id),
    FOREIGN KEY (titre_academique_id) REFERENCES titres_academiques(id),
    FOREIGN KEY (fonction_hospitaliere_id) REFERENCES fonctions_hospitalieres(id),
    FOREIGN KEY (departement_id) REFERENCES departements(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;