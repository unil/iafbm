DROP TABLE IF EXISTS evaluations;
CREATE TABLE evaluations (
    id int(11) NOT NULL AUTO_INCREMENT,
    actif tinyint(1) NOT NULL DEFAULT '1',
    termine tinyint(4) NOT NULL DEFAULT '0',
    evaluation_type_id int(11) NOT NULL,
    date_periode_debut date DEFAULT NULL,
    date_periode_fin date DEFAULT NULL,
    personne_id int(11) NOT NULL,
    activite_id int(11) NOT NULL,
    evaluation_etat_id int(11) NOT NULL DEFAULT '1',
    PRIMARY KEY (id),
    FOREIGN KEY (evaluation_type_id) REFERENCES evaluations_types (id),
    FOREIGN KEY (activite_id) REFERENCES activites (id),
    FOREIGN KEY (personne_id) REFERENCES personnes (id) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
