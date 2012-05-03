DROP TABLE IF EXISTS personnes_denominations;
CREATE TABLE personnes_denominations (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(255) NOT NULL,
    nom_masculin VARCHAR(255) NOT NULL,
    nom_feminin VARCHAR(255) NOT NULL,
    abreviation VARCHAR(255) NOT NULL,
    abreviation_masculin VARCHAR(255) NOT NULL,
    abreviation_feminin VARCHAR(255) NOT NULL,
    poids TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;