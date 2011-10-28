#!/bin/sh

MYSQL_USER=root
TMP_FILE=merged_sed.sql

# Merges all sql files into one single file
./merge.sh

# Imports the merged file
echo Connecting to database using user \'$MYSQL_USER\'
cat merged.sql | sed s/{db-name}/iafbm/ > $TMP_FILE
mysql -v -u $MYSQL_USER -p --default-character-set=utf8 < $TMP_FILE
rm $TMP_FILE

# Cleans the merged file
#rm $MERGE_FILE
