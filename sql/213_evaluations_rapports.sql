DROP TABLE IF EXISTS evaluations_rapports;
CREATE  TABLE evaluations_rapports (
    id INT NOT NULL AUTO_INCREMENT ,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    evaluation_id INT NOT NULL,
    date_biblio_demandee DATE DEFAULT NULL,
    date_biblio_recue DATE DEFAULT NULL,
    date_relance DATE DEFAULT NULL,
    date_rapport_recu DATE DEFAULT NULL,
    date_transmis_evaluateur DATE DEFAULT NULL,
    date_entretien DATE DEFAULT NULL,
    date_accuse_lettre DATE DEFAULT NULL,
    date_accuse_email DATE DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
