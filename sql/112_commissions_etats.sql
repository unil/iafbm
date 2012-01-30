DROP TABLE IF EXISTS commissions_etats;
CREATE TABLE commissions_etats (
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

INSERT INTO `commissions_etats` (id, actif, created, nom, description) VALUES (1, '1', CURRENT_TIMESTAMP, 'En cours', 'Commission en cours');
INSERT INTO `commissions_etats` (id, actif, created, nom, description) VALUES (2, '1', CURRENT_TIMESTAMP, 'En suspens', 'Commission en suspens');
INSERT INTO `commissions_etats` (id, actif, created, nom, description) VALUES (3, '1', CURRENT_TIMESTAMP, 'Clôturé', 'Commission clôturée');