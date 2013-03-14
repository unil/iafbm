DROP TABLE IF EXISTS commissions;
CREATE TABLE commissions (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN DEFAULT false,
    nom VARCHAR(255) NOT NULL,
    institut TEXT,
    commentaire TEXT,
    commission_type_id INT NOT NULL,
    commission_etat_id INT NOT NULL,
    section_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_type_id) REFERENCES commissions_types(id),
    FOREIGN KEY (commission_etat_id) REFERENCES commissions_etats(id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
