DROP TABLE IF EXISTS commissions_fonctions;
CREATE TABLE commissions_fonctions (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `commissions_fonctions` (actif, created, nom, description) VALUES ('1', CURRENT_TIMESTAMP, 'Président', 'Président de la commission');
INSERT INTO `commissions_fonctions` (actif, created, nom, description) VALUES ('1', CURRENT_TIMESTAMP, 'Représentant corps professoral SSF', 'Représentant du corps professoral SSF');