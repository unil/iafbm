DROP TABLE IF EXISTS evaluations_preavis;
CREATE  TABLE evaluations_preavis (
    id INT NOT NULL AUTO_INCREMENT ,
    actif BOOLEAN NOT NULL DEFAULT true,
    preavis VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
