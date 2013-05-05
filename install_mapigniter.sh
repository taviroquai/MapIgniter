#!/bin/sh
#
# This archive is part of the MapIgniter source code.
# 
# The source code of this software - MapIgniter - is under the terms of one of two
# licenses: Apache v2 and GPL v2
# 
# About:
# =====
# This script will install postgres, postgis, cgi-mapserver, php5,
# php5, php5-curl, php5-psql, php5-gd and composer
#
# Running:
# =======
# sudo ./install_mapigniter.sh
#

# =============================================================================
# Install script for MapIgniter
# =============================================================================

TMP="/tmp/build_mapigniter"
INSTALL_FOLDER="/var/www"
PG_USER="mapigniter"
MAPIGNITER_VERSION="master"
PG_VERSION="9.1"
CI_VERSION="2.1.3"
APACHE_CONFIG="/etc/apache2/sites-available"

echo "==============================================================="
echo "Installing MapIgniter (install_mapigniter.sh)"
echo "==============================================================="

# No point going any farther if we're not running correctly...
if [ `whoami` != 'root' ]; then
echo "Mapigniter installer requires super-user privileges to work."
echo "Quit."
exit 1
fi

if [ $SUDO_USER = "root" ]; then
/bin/echo "You must start this under your regular user account (not root) using sudo."
/bin/echo "Rerun using: sudo $0"
exit 1
fi

echo "Starting..."
apt-get update

echo "Check for python-software-properties..."
if [ ! -x "`dpkg -l \* | grep python-software-properties`" ] ; then
   apt-get -y install python-software-properties
fi

echo "Adding UbuntuGIS repo..."
add-apt-repository -y ppa:ubuntugis/ppa
apt-get update

echo "Installing apache web server..."
apt-get install -y apache2

echo "Installing cgi-mapserver..."
apt-get install -y cgi-mapserver

echo "Installing postgres and postgis..."
apt-get install -y "postgresql-$PG_VERSION-postgis" postgis

echo "Installing php5, pgsql, curl and gd..."
apt-get install -y php5-cli php5-pgsql php5-curl php5-gd libapache2-mod-php5

echo "Installing unzip..."
apt-get install -y unzip

echo "Downloading php composer..."
wget --progress=dot:mega http://getcomposer.org/installer

echo "Installing php composer..."
php installer
mv composer.phar /usr/local/bin/composer
rm -f installer

echo "Creating mapigniter postgres user with password 'postgres'..."
sudo -u postgres createuser --superuser $PG_USER
echo "alter role \"$PG_USER\" with password 'postgres'" | sudo -u postgres psql

echo "Installing MapIgniter databases..."
sudo -u postgres createdb -U postgres -E UTF8 mapigniter
sudo -u postgres createlang -d mapigniter plpgsql
sudo -u postgres createdb -U postgres -E UTF8 mapigniterdata
sudo -u postgres createlang -d mapigniterdata plpgsql

echo "Adding PostGIS extension..."
# check for POSTGIS 2.0
DIRECTORY="/usr/share/postgresql/$PG_VERSION/contrib/postgis-2.0"
if [ ! -d "$DIRECTORY" ]; then
    DIRECTORY="/usr/share/postgresql/$PG_VERSION/contrib/postgis-1.5"
fi

echo "PostGIS found at $DIRECTORY"
sudo -u postgres psql --quiet -U postgres -d mapigniter -f "$DIRECTORY/postgis.sql"
sudo -u postgres psql --quiet -U postgres -d mapigniter -f "$DIRECTORY/spatial_ref_sys.sql"
sudo -u postgres psql --quiet -U postgres -d mapigniterdata -f "$DIRECTORY/postgis.sql"
sudo -u postgres psql --quiet -U postgres -d mapigniterdata -f "$DIRECTORY/spatial_ref_sys.sql"

echo "Activating Apache2 mod_rewrite..."
a2enmod rewrite
sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/sites-available/default

echo "Restarting Apache2..."
service apache2 restart

echo "Downloading MapIgniter..."
apt-get install -y git
mkdir "$INSTALL_FOLDER/mapigniter"
git clone http://github.com/taviroquai/MapIgniter "$INSTALL_FOLDER/mapigniter"

echo "Downloading CodeIgniter..."
wget -O codeigniter.zip --progress=dot:mega http://codeigniter.com/download.php
unzip codeigniter.zip
cp -R "CodeIgniter_$CI_VERSION/system" "$INSTALL_FOLDER/mapigniter"
rm codeigniter.zip
rm -rf "CodeIgniter_$CI_VERSION"

echo "Installing MapIgniter..."
mv "$INSTALL_FOLDER/mapigniter/index.dist.php" "$INSTALL_FOLDER/mapigniter/index.php"
mv "$INSTALL_FOLDER/mapigniter/htaccess.dist" "$INSTALL_FOLDER/mapigniter/.htaccess"
mv "$INSTALL_FOLDER/mapigniter/application/config/config.dist.php" "$INSTALL_FOLDER/mapigniter/application/config/config.php"
mv "$INSTALL_FOLDER/mapigniter/application/config/database.dist.php" "$INSTALL_FOLDER/mapigniter/application/config/database.php"
mv "$INSTALL_FOLDER/mapigniter/application/config/mapigniter.dist.php" "$INSTALL_FOLDER/mapigniter/application/config/mapigniter.php"
mv "$INSTALL_FOLDER/mapigniter/data.dist" "$INSTALL_FOLDER/mapigniter/data"
chown -R www-data "$INSTALL_FOLDER/mapigniter/data"
mkdir "$INSTALL_FOLDER/mapigniter/web/data"
mkdir "$INSTALL_FOLDER/mapigniter/web/data/tmp"
chown -R www-data "$INSTALL_FOLDER/mapigniter/web/data"
cd "$INSTALL_FOLDER/mapigniter/application/third_party"
composer install
cd "$INSTALL_FOLDER/mapigniter/web/js"
composer install
exit
