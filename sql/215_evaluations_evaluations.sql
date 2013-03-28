DROP TABLE IF EXISTS evaluations_evaluations;
CREATE  TABLE evaluations_evaluations (
    id INT NOT NULL AUTO_INCREMENT ,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    evaluation_id INT NOT NULL,
    date_rapport_evaluation DATE DEFAULT NULL,
    preavis_evaluateur_id INT DEFAULT NULL,
    preavis_decanat_id INT DEFAULT NULL,
    date_liste_transmise DATE DEFAULT NULL,
    date_dossier_transmis DATE DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id),
    FOREIGN KEY (preavis_evaluateur_id) REFERENCES evaluations_preavis(id),
    FOREIGN KEY (preavis_decanat_id) REFERENCES evaluations_preavis(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
