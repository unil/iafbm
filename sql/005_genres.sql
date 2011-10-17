DROP TABLE IF EXISTS genres;
CREATE TABLE genres (
    id INT NOT NULL AUTO_INCREMENT,
    genre VARCHAR(16) NOT NULL,
    genre_short VARCHAR(1) NOT NULL,
    intitule VARCHAR(16) NOT NULL,
    intitule_short VARCHAR(8) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO genres (id, genre, genre_short, intitule, intitule_short) VALUES (1, 'Masculin', 'H', 'Monsieur', 'M.');
INSERT INTO genres (id, genre, genre_short, intitule, intitule_short) VALUES (2, 'Féminin', 'F', 'Madame', 'Mme.');