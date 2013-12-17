DROP TABLE IF EXISTS evaluations_cdirs;
CREATE  TABLE evaluations_cdirs (
    id INT NOT NULL AUTO_INCREMENT ,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    evaluation_id INT NOT NULL,
    seance_cdir DATE DEFAULT NULL,
    commentaire TEXT,
    decision_id INT NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id),
    FOREIGN KEY (decision_id) REFERENCES evaluations_decisions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
