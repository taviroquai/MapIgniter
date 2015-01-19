#!/bin/sh
#
# This archive is part of the MapIgniter source code.
# 
# The source code of this software - MapIgniter - is under the terms of one of 
# two licenses: Apache v2 and GPL v2
# 
# ABOUT:
# =====
# This script will install some mapigniter dependencies and configure default 
# files
# 
# WARNING:
# Of course that Apache, PostgreSQL and Postgis should already be running 
# because it is not responsability of MapIgniter to install other software
# Anyway, the following steps are an example to help install a full system:
# 
# ------------------------------------------------------------------------------
# sudo apt-get install -y python-software-properties
# sudo add-apt-repository -y ppa:ubuntugis/ppa
# sudo apt-get update
# sudo apt-get install -y cgi-mapserver postgresql-9.1-postgis postgis postgresql-contrib
# sudo apt-get install php5-pgsql php5-gd php5-curl
# sudo a2enmod rewrite
# sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/sites-available/default
# sudo service apache2 restart
# sudo su postgres
# createuser --superuser mapigniter
# echo "alter role mapigniter with password 'postgres'" | psql
# createdb mapigniter
# createdb mapigniterdata
# psql -d mapigniterdata -f /usr/share/postgresql/9.1/contrib/postgis-2.1/postgis.sql
# psql -d mapigniterdata -f /usr/share/postgresql/9.1/contrib/postgis-2.1/spatial_ref_sys.sql
# psql -d mapigniterdata -f /usr/share/postgresql/9.1/contrib/postgis-2.1/topology.sql
# exit
# sudo apt-get install -y git
# sudo mkdir /var/www/mapigniter
# sudo chown miadmin /var/www/mapigniter
# cd /var/www/mapigniter
# git clone -b mapigniter11 http://github.com/taviroquai/MapIgniter.git .
# -----------------------------------------------------------------------------
#
# Running:
# =======
# ./install.sh
#

# =============================================================================
# Install script for MapIgniter
# =============================================================================

CI_VERSION="2.2"

echo "==============================================================="
echo "Installing MapIgniter (install.sh)"
echo "==============================================================="

cwd=$(pwd)

echo "Starting..."

if [ -f /usr/bin/unzip ];
then
    echo "Unzip was found. Skipping install unzip..."
else
    echo "FATAL ERROR: unzip was not found at /usr/bin/unzip"
    exit 1
fi

if [ -f /usr/local/bin/composer ];
then
    echo "Composer was found. Skipping install composer..."
else
    echo "Downloading php composer..."
    wget --quiet --progress=dot:mega http://getcomposer.org/installer
    echo "Installing php composer..."
    php installer
    mv composer.phar /usr/local/bin/composer
    rm -f installer
fi

if [ -f system ];
then
    echo "CodeIgniter system was found. Skipping install CodeIgniter..."
else
    echo "Downloading CodeIgniter..."
    mkdir tmp
    mkdir tmp/mapigniter
    cd tmp/mapigniter
    wget --quiet -O codeigniter.zip --progress=dot:mega "https://github.com/bcit-ci/CodeIgniter/archive/$CI_VERSION-stable.zip"
    unzip -q "codeigniter.zip"
    cd "$cwd"
    cp -R "tmp/mapigniter/CodeIgniter-$CI_VERSION-stable/system" .
    rm -Rf tmp
fi

echo "Installing MapIgniter..."
cp index.dist.php index.php
cp htaccess.dist .htaccess
cp application/config/config.dist.php application/config/config.php
cp application/config/googleearth.dist.php application/config/googleearth.php
cp -R data.dist data
mkdir data/cache
mkdir web/data
mkdir web/data/tmp
composer install
ln -s "$cwd/vendor" ./web/js/vendor

echo "Done! Open in the browser http://machine-ip/mapigniter/install"

exit 0
