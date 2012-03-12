DROP TABLE IF EXISTS genres;
CREATE TABLE genres (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(16) NOT NULL,
    initiale VARCHAR(1) NOT NULL,
    intitule VARCHAR(16) NOT NULL,
    intitule_abreviation VARCHAR(8) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;