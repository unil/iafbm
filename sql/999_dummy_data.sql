/*******************************************************************************
 * HUMANS
 ******************************************************************************/

-- personnes
INSERT INTO `personnes` (personne_type_id, prenom, nom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES (2, 'Damien', 'Rice', 'Villy 10', '0216101010', '36', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (personne_type_id, nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES (1, 'Maryline', 'Monroe', 'Anges 10', '0216101011', '32', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (personne_type_id, nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES (3, 'Catherine', 'Deneuve', 'Bien-être 12', '0216101012', '31', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (personne_type_id, nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES (2, 'Isabelle', 'Alésiebleu', 'Chats 3', '0216101013', '26', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (personne_type_id, nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES (3, 'Cannelle', 'Vanille', 'Grâce 9', '0216101014', '21', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (personne_type_id, nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES (1, 'Marie', 'Jesus', 'Intelligence 14', '0216101015', '22', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (personne_type_id, nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES (1, 'Stefan', 'Bern', 'Cadre 16', '0216101016', '14', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);
INSERT INTO `personnes` (personne_type_id, nom, prenom, adresse, tel, pays_id, date_naissance, actif, created)
VALUES (1, 'Matthieu', 'Kassovic', 'Smart 7', '0216101017', '16', '2011-04-01T00:00:00', '1', CURRENT_TIMESTAMP);

-- candidats
/*
INSERT INTO candidats (created,modified,actif,commission_id,nom,prenom,genre_id,etatcivil_id,date_naissance,nombre_enfants,no_avs,email,adresse_pro,npa_pro,lieu_pro,pays_pro_id,telephone_pro,adresse_pri,npa_pri,lieu_pri,pays_pri_id,telephone_pri)
VALUES (
    CURRENT_TIMESTAMP, null, 1, 1,
    'Damien', 'Corpataux', 1, 1, '1979-11-10', 0, 123.456.789.10,
    'd@mien.ch',
    'Av. de la profession 1', '1000', 'Lausanne', 32, null,
    'Av. du privé', '1009', 'Pully', 32, '0793213201'
);
*/

/*******************************************************************************
 * COMMISSIONS
 ******************************************************************************/

/*
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
*/