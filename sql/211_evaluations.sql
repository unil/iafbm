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
    date_biblio_demandee date DEFAULT NULL,
    date_biblio_recue date DEFAULT NULL,
    date_relance date DEFAULT NULL,
    date_rapport_recu date DEFAULT NULL,
    date_transmis_evaluateur date DEFAULT NULL,
    date_entretien date DEFAULT NULL,
    commentaire text COLLATE utf8_unicode_ci,
    date_accuse_lettre date DEFAULT NULL,
    date_accuse_email date DEFAULT NULL,
    PRIMARY KEY (id),
    KEY evaluation_type_id (evaluation_type_id),
    KEY evaluations_ibfk_2 (activite_id),
    KEY evaluations_ibfk_3 (personne_id),
    CONSTRAINT evaluations_ibfk_1 FOREIGN KEY (evaluation_type_id) REFERENCES evaluations_types (id),
    CONSTRAINT evaluations_ibfk_2 FOREIGN KEY (activite_id) REFERENCES activites (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT evaluations_ibfk_3 FOREIGN KEY (personne_id) REFERENCES personnes (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

