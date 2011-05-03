/*******************************************************************************
 * HUMANS
 ******************************************************************************/

-- personnes
INSERT INTO `personnes` (nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES ('Damien', 'Corpataux', 'Villy 10', '0216101010', '36', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES ('Maryline', 'Monroe', 'Anges 10', '0216101011', '32', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES ('Catherine', 'Deneuve', 'Adresse', '0216101012', '31', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES ('Isabelle', 'Alésiebleu', 'Adresse', '0216101013', '26', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES ('Cannelle', 'Vanille', 'Adresse', '0216101014', '21', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES ('Marie', 'Jesus', 'Adresse', '0216101015', '22', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES ('Stefan', 'Bern', 'Adresse', '0216101016', '14', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES ('Matthieu', 'Kassovic', 'Adresse', '0216101017', '16', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);

/*******************************************************************************
 * COMMISSIONS
 ******************************************************************************/

-- commissions
INSERT INTO `commissions` (nom, description, commission_type_id, actif, created)
VALUES ('Nom de la commission', 'Description de cette commission', '1', '1', CURRENT_TIMESTAMP);
INSERT INTO `commissions` (nom, description, commission_type_id, actif, created)
VALUES ('Nom d''une autre commission', 'Description de cette autre commission', '1', '1', CURRENT_TIMESTAMP);

-- commissions_membres
INSERT INTO commissions_membres (actif, created, taux, personne_id, commission_fonction_id, commission_id)
VALUES ('1', CURRENT_TIMESTAMP, 100, 4, 1, 1);
INSERT INTO commissions_membres (actif, created, taux, personne_id, commission_fonction_id, commission_id)
VALUES ('1', CURRENT_TIMESTAMP, 100, 1, 2, 1);
INSERT INTO commissions_membres (actif, created, taux, personne_id, commission_fonction_id, commission_id)
VALUES ('1', CURRENT_TIMESTAMP, 100, 2, 2, 1);
INSERT INTO commissions_membres (actif, created, taux, personne_id, commission_fonction_id, commission_id)
VALUES ('1', CURRENT_TIMESTAMP, 100, 3, 2, 1);
INSERT INTO commissions_membres (actif, created, taux, personne_id, commission_fonction_id, commission_id)
VALUES ('1', CURRENT_TIMESTAMP, 100, 5, 2, 1);
INSERT INTO commissions_membres (actif, created, taux, personne_id, commission_fonction_id, commission_id)
VALUES ('1', CURRENT_TIMESTAMP, 100, 6, 2, 1);

-- commissions_candidats
INSERT INTO commissions_candidats (actif, created, personne_id, commission_id)
VALUES ('1', CURRENT_TIMESTAMP, 7, 1);
INSERT INTO commissions_candidats (actif, created, personne_id, commission_id)
VALUES ('1', CURRENT_TIMESTAMP, 8, 1);