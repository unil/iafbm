DROP TABLE IF EXISTS genres;
CREATE TABLE genres (
    id INT NOT NULL AUTO_INCREMENT,
    created TIMESTAMP NULL DEFAULT NULL,
    modified TIMESTAMP NULL DEFAULT NULL,
    actif BOOLEAN NOT NULL DEFAULT true,
    util_creat INT,
    util_modif INT,
    genre VARCHAR(16) NOT NULL,
    genre_short VARCHAR(1) NOT NULL,
    intitule VARCHAR(16) NOT NULL,
    intitule_short VARCHAR(8) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO genres (id, actif, created, genre, genre_short, intitule, intitule_short) VALUES (1, '1', CURRENT_TIMESTAMP, 'Masculin', 'H', 'Monsieur', 'M.');
INSERT INTO genres (id, actif, created, genre, genre_short, intitule, intitule_short) VALUES (2, '1', CURRENT_TIMESTAMP, 'Féminin', 'F', 'Madame', 'Mme.');