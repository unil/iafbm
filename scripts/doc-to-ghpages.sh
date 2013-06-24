#!/bin/sh

# Configuration
TMP_ROOT=/tmp/iafbm-doc-generator
DOC_ROOT=generated
URL_BASE="http://unil.github.io/iafbm/documentation/manual/"

# Adds ssh passphrase
eval "$(ssh-agent)" && ssh-add

# Setup working directory
rm -rf $TMP_ROOT
mkdir -p $TMP_ROOT

# Generates: manual
cd $TMP_ROOT
git clone https://github.com/unil/iafbm.wiki.git
cd iafbm.wiki
mkdir -p $TMP_ROOT/$DOC_ROOT/manual
gollum-site generate --base_path "$URL_BASE" --output_path $TMP_ROOT/$DOC_ROOT/manual/
echo '<html><head><meta http-equiv="refresh" content="0; url=Home.html"></head><body></body></html>' > $TMP_ROOT/$DOC_ROOT/manual/index.html

# Generates: API server
cd $TMP_ROOT
git clone https://github.com/unil/iafbm.git
cd iafbm
mkdir -p $TMP_ROOT/$DOC_ROOT/api/server
phpdoc --directory="." --ignore="iafbm/lib/xfm/,iafbm/lib/Minify/,iafbm/lib/dompdf-0.5.1/,iafbm/public/,scripts/migrations/vendors/,documentation/" --sourcecode --title="iafbm" --target="$TMP_ROOT/$DOC_ROOT/api/server"

# Generates: API client
cd $TMP_ROOT
cd iafbm
mkdir $TMP_ROOT/$DOC_ROOT/api/client
jsduck iafbm/public/assets/js/app iafbm/public/assets/js/ext-custom/ --builtin-classes --title="iafbm - Developer documentation" --output $TMP_ROOT/$DOC_ROOT/api/client/.

# Push documentation on gh-pages
cd $TMP_ROOT
git clone --branch gh-pages git@github.com:unil/iafbm.git gh-pages
cd gh-pages/documentation
for path in "manual" "api/server" "api/client"
do
    git rm -rf $path
    mkdir -p $path
    cp -r $TMP_ROOT/$DOC_ROOT/$path/. $path/.
done
git add . && git commit -m"Generated documentation automatic update"
git push

# Cleans working directory
rm -rf $TMP_ROOT