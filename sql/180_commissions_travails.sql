DROP TABLE IF EXISTS commissions_travails;
CREATE TABLE commissions_travails (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    termine BOOLEAN NOT NULL DEFAULT false,
    commission_id INT NOT NULL,
    loco_primo INT DEFAULT NULL,
    loco_secondo INT DEFAULT NULL,
    loco_tertio INT DEFAULT NULL,
    commentaire TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (loco_primo) REFERENCES candidats(id),
    FOREIGN KEY (loco_secondo) REFERENCES candidats(id),
    FOREIGN KEY (loco_tertio) REFERENCES candidats(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS commissions_travails_evenements_types;
CREATE TABLE commissions_travails_evenements_types (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO commissions_travails_evenements_types (id, actif, nom) VALUES (1, '1', 'Séance: 1ère sélection sur dossiers');
INSERT INTO commissions_travails_evenements_types (id, actif, nom) VALUES (2, '1', 'Journée: leçons, séminaires et visites');
INSERT INTO commissions_travails_evenements_types (id, actif, nom) VALUES (3, '1', 'Séance de délibération');
INSERT INTO commissions_travails_evenements_types (id, actif, nom) VALUES (4, '1', 'Préparation séance');
INSERT INTO commissions_travails_evenements_types (id, actif, nom) VALUES (5, '1', 'Rédaction du rapport');

DROP TABLE IF EXISTS commissions_travails_evenements;
CREATE TABLE commissions_travails_evenements (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    commission_id INT NOT NULL,
    commission_travail_evenement_type_id INT NOT NULL,
    date DATE DEFAULT NULL,
    proces_verbal BOOLEAN DEFAULT false,
    duree SMALLINT DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (commission_travail_evenement_type_id) REFERENCES commissions_travails_evenements_types(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;