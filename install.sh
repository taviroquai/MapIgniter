#!/bin/sh
#
# This archive is part of the MapIgniter source code.
# 
# The source code of this software - MapIgniter - is under the terms of one of two
# licenses: Apache v2 and GPL v2
# 
# About:
# =====
# This script will install mapigniter dependencies and configure default files
#
# Running:
# =======
# sudo ./install.sh
#

# =============================================================================
# Install script for MapIgniter
# =============================================================================

CI_VERSION="2.1.3"

echo "==============================================================="
echo "Installing MapIgniter (install.sh)"
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

if [ -f /usr/bin/unzip ];
then
    echo "Unzip was found. Skipping install unzip..."
else
    echo "Installing unzip..."
    apt-get install -y unzip
fi

if [ -f /usr/local/bin/composer ];
then
    echo "Composer was found. Skipping install composer..."
else
    echo "Downloading php composer..."
    wget --progress=dot:mega http://getcomposer.org/installer
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
    wget -O codeigniter.zip --progress=dot:mega http://codeigniter.com/download.php
    unzip codeigniter.zip
    cp -R "CodeIgniter_$CI_VERSION/system" .
    rm codeigniter.zip
    rm -rf "CodeIgniter_$CI_VERSION"
fi

echo "Installing MapIgniter..."
cp index.dist.php index.php
cp htaccess.dist .htaccess
cp application/config/config.dist.php application/config/config.php
cp data.dist data
mkdir data/cache
chown -R www-data data
mkdir web/data
mkdir web/data/tmp
chown -R www-data web/data
cd application/third_party
composer install
cd ../../web/js
composer install

echo "Done!"

exit 0
