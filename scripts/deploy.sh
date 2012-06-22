#!/bin/sh

# This script will deploy the iafbm project in the current directory
#
# Instructions:
# - Run this script from the directory in which you want to create iafbm
#
# Actions:
# - Pull iafbm project (git)
# - Checkout xfreemwork library (SVN)
# - Create database structure (MySQL)

# Variables
INITIAL_DIRECTORY=`pwd`
PROJECT_DIRECTORY='iafbm'

# Checks for command availability
for CMD in git svn; do
    $CMD > /dev/null 2>&1
    if [ $? -eq 127 ]; then
        echo "! Command '$CMD' not found. Please install first."
        echo '! Aborting...'
        exit 1
    fi
done

# Checks to avoid blasting an existing installation
git status > /dev/null 2>&1;
if [ $? -eq 0 ]; then
    echo '! Cannot deploy when within a git repository.'
    echo '! Please choose another installation target.'
    echo '! Aborting...'
    exit 1
fi

if [ -f $PROJECT_DIRECTORY ]; then
    echo "! A file named '$PROJECT_DIRECTORY' already exists."
    echo "! Please 'rm -f $PROJECT_DIRECTORY' first."
    echo '! Aborting...'
    exit 1
fi

if [ -d $PROJECT_DIRECTORY ] && [ "$(ls -A $PROJECT_DIRECTORY)" ]; then
    echo "! A directory named '$PROJECT_DIRECTORY' already exists."
    echo "! The directory should be inexistant or should be empty."
    echo "! Please remove the directory with 'rm -rf $PROJECT_DIRECTORY'"
    echo "! or empty the directory with 'rm -rf $PROJECT_DIRECTORY/*'"
    echo '! Aborting...'
    exit 1
fi

# Clones iafbm project (git)
git clone git@github.com:unil/iafbm.git
if [ $? -ne 0 ]; then
    echo '! Could not clone git project.'
    echo '! Aborting...'
    exit 1
fi

# xfm-php submodule
cd iafbm
git submodule update --init --recursive
if [ $? -ne 0 ]; then
    echo '! Could not init/update git submodule(s).'
    echo '! Aborting...'
    exit 1
fi
cd -

# Creates database structure (bypassing confirmation prompt)
cd iafbm/scripts
php update.php -xyes
if [ $? -ne 0 ]; then
    echo '! Could not create database.'
    echo '! Aborting...'
    exit 1
fi
cd -

# Removes downloaded deploy.sh
cd $INITIAL_DIRECTORY
rm -f deploy.sh
if [ $? -ne 0 ]; then
    echo '! Could not delete the downloaded deploy.sh'
    echo '! Aborting...'
    exit 1
fi

# Back to initial shell state
echo Deploy completed.
cd $INITIAL_DIRECTORY