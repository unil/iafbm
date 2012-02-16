DROP TABLE IF EXISTS commissions_etats;
CREATE TABLE commissions_etats (
    id INT NOT NULL AUTO_INCREMENT,
    actif BOOLEAN NOT NULL DEFAULT true,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `commissions_etats` (id, actif, nom, description) VALUES (1, '1', 'En cours', 'Commission en cours');
INSERT INTO `commissions_etats` (id, actif, nom, description) VALUES (2, '1', 'En suspens', 'Commission en suspens');
INSERT INTO `commissions_etats` (id, actif, nom, description) VALUES (3, '1', 'Clôturé', 'Commission clôturée');