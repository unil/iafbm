DROP TABLE IF EXISTS personnes_fonctions;
CREATE TABLE personnes_fonctions (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO personnes_fonctions (id, nom) VALUES (1, 'Prof.');
INSERT INTO personnes_fonctions (id, nom) VALUES (2, 'Assistant');