#!/bin/sh

MYSQL_USER=root

# Merges all sql files into one single file
./merge.sh

# Imports the merged file
echo Connecting to database using user \'$MYSQL_USER\'
mysql -v -u $MYSQL_USER -p --default-character-set=utf8 < merged.sql

# Cleans the merged file
#rm $MERGE_FILE
