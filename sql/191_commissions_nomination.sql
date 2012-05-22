DROP TABLE IF EXISTS commissions_propositions_nominations;
CREATE TABLE commissions_propositions_nominations (
    -- FIXME: Remove fields taken from candidat/commission that are read-only (not changeable by user)
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    commission_id INT NOT NULL,
    section_id INT NOT NULL,
    objet TEXT,
    --titre_propose? INT NOT NULL,
    contrat_debut DATE NULL DEFAULT NULL,
    contrat_fin DATE,
    contrat_taux TINYINT UNSIGNED,
    indemnite INT UNSIGNED,
    --
    denomination_id INT NOT NULL,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    adresse TEXT,
    email VARCHAR(255),
    etatcivil_id INT,
    date_naissance DATE,
    pays_id INT,
    canton_id INT,
    permis_id INT,
    --
    position_actuelle_fonction TEXT,
    discipline_generale TEXT,
    formation_id INT,
    --
    observations TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (commission_id) REFERENCES commissions(id),
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (denomination_id) REFERENCES denominations(id),
    FOREIGN KEY (etatcivil_id) REFERENCES etatscivils(id),
    FOREIGN KEY (pays_id) REFERENCES pays(id),
    FOREIGN KEY (canton_id) REFERENCES cantons(id),
    FOREIGN KEY (permis_id) REFERENCES permis(id),
    FOREIGN KEY (formation_id) REFERENCES formations(id),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE UNIQUE INDEX commission_id_unique ON commissions_propositions_nominations(commission_id);