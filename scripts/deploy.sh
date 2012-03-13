#!/bin/sh

# This script will deploy the iafbm project in the current directory
#
# Instructions:
# - Run this script from the directory in which you want to create iafbm
#
# Actions:
# - Checkout xfreemwork library (SVN)
# - Pull iafbm project (git)
# - Create database structure (MySQL)

# Variables
INITIAL_DIRECTORY=`pwd`

# Clones iafbm project (git)
git clone git@github.com:unil/iafbm.git

# Checks out xfreemwork library (SVN)
mkdir iafbm/iafbm/lib/xfreemwork
svn co https://xfreemwork.svn.sourceforge.net/svnroot/xfreemwork/trunk iafbm/iafbm/lib/xfreemwork/lib

# Creates database structure
cd iafbm/scripts
php update.php -x

# Back to initial shell state
echo Deploy completed.
cd $INITIAL_DIRECTORY