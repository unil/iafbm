DROP TABLE IF EXISTS commissions_etats;
CREATE TABLE commissions_etats (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `commissions_etats` (id, nom, description) VALUES (1, 'En cours', 'Commission en cours');
INSERT INTO `commissions_etats` (id, nom, description) VALUES (2, 'En suspens', 'Commission en suspens');
INSERT INTO `commissions_etats` (id, nom, description) VALUES (3, 'Clôturé', 'Commission clôturée');