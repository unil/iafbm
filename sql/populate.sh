#!/bin/sh

MERGE_FILE=merged.sql
MYSQL_USER=root

# Merges all sql files into one single file
rm -f $MERGE_FILE 
cat *.sql > $MERGE_FILE

# Imports the merged file
echo Connecting to database using user \'$MYSQL_USER\'
mysql -u $MYSQL_USER -p < $MERGE_FILE

# Cleans the merged file
#rm $MERGE_FILE
