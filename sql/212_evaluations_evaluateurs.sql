DROP TABLE IF EXISTS evaluations_evaluateurs;
CREATE  TABLE evaluations_evaluateurs (
    id INT NOT NULL AUTO_INCREMENT ,
    actif BOOLEAN NOT NULL DEFAULT true,
    evaluation_id INT NOT NULL,
    personne_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id),
    FOREIGN KEY (personne_id) REFERENCES personnes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
