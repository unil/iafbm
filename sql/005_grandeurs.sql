DROP TABLE IF EXISTS grandeurs;
CREATE TABLE grandeurs (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom TEXT NOT NULL,
    dimsension_symbole TEXT,
    unite TEXT,
    unite_singulier TEXT,
    unite_pluriel TEXT,
    unite_symbole TEXT,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;