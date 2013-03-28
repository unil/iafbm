DROP TABLE IF EXISTS evaluations;
CREATE  TABLE evaluations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    evaluation_type_id INT NOT NULL,
    date_periode_debut DATE DEFAULT NULL,
    date_periode_fin DATE DEFAULT NULL,
    personne_id INT NOT NULL,
    activite_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (evaluation_type_id) REFERENCES evaluations_types (id),
    FOREIGN KEY (activite_id) REFERENCES activite(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
