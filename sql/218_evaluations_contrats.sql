DROP TABLE IF EXISTS evaluations_contrats;
CREATE  TABLE evaluations_contrats (
    id INT NOT NULL AUTO_INCREMENT ,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    evaluation_id INT NOT NULL,
    copie_nouveau_contrat BOOLEAN DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
