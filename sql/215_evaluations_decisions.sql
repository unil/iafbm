DROP TABLE IF EXISTS evaluations_decisions;
CREATE  TABLE evaluations_decisions (
    id INT NOT NULL AUTO_INCREMENT ,
    actif BOOLEAN NOT NULL DEFAULT true,
    decision VARCHAR(45) NOT NULL,
    commentaire VARCHAR(45) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
