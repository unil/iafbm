#!/bin/sh

MERGE_FILE=merged.sql

# Merges all sql files into one single file
rm -f $MERGE_FILE 
cat *.sql > $MERGE_FILE
