#!/bin/sh

DUMP_FILE=999_dummy_data.sql
MYSQL_USER=root

mysqldump -v -u $MYSQL_USER -p --no-create-db --no-create-info iafbm \
personnes \
adresses \
personnes_adresses \
personnes_emails \
personnes_fonctions \
personnes_formations \
commissions \
candidats \
candidats_formations \
commissions_candidats_commentaires \
commissions_creations \
commissions_finalisations \
commissions_membres \
commissions_travails \
commissions_travails_evenements \
commissions_validations \
> $DUMP_FILE
