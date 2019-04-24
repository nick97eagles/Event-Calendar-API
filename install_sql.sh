
#! /usr/bin/env bash

# Modified from  Install_lamp.sh used by Prof. Carman in CPTR320 @ Walla Walla university
# *Removed Vagrant functionality 
# *intended for use with AWS Ec2 instances

# Modified by Yohance Etienne for use 

# Variables
DBHOST="localhost"
DBNAMES=("calendars")
DBUSER="dbuser"
DBPASSWD="DB_1725$"

echo -e "\n--- Updating packages list ---\n"
apt-get -qq update

echo -e "\n--- Install base packages (VIM, NPM, Curl) ---\n"
apt-get -y install vim curl build-essential software-properties-common git 

echo -e "\n--- Add Node 8 ---\n"
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash 

echo -e "\n--- Updating packages list ---\n"
apt-get -qq update

# MySQL setup for development purposes ONLY
echo -e "\n--- Install MySQL specific packages and settings ---\n"
debconf-set-selections <<< "mysql-server mysql-server/root_password password $DBPASSWD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DBPASSWD"
debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $DBPASSWD"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $DBPASSWD"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $DBPASSWD"
debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none"
apt-get -y install mysql-server phpmyadmin

echo -e "\n--- Setting up our MySQL user and db ---\n"
for n in "${DBNAMES[@]}"
do
    echo -e "Adding MySQL Database: $n\n"
    mysql -uroot -p$DBPASSWD -e "CREATE DATABASE $n"
    mysql -uroot -p$DBPASSWD -e "GRANT ALL PRIVILEGES ON $n.* TO '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASSWD'" 
done

echo -e "\n--- Temporary fix for phpMyAdmin 4.6 and PHP 7.2 ---\n"
sed -i "s/|\s*\((count(\$analyzed_sql_results\['select_expr'\]\)/| (\1)/g" /usr/share/phpmyadmin/libraries/sql.lib.php

echo -e "\n--- Installing PHP-specific packages ---\n"
apt-get -y install php apache2 libapache2-mod-php php-curl php-gd php-mysql php-gettext php-mbstring php-zip 

echo -e "\n--- Enabling mod-rewrite ---\n"
a2enmod rewrite

echo -e "\n--- Allowing Apache override to all ---\n"
sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

echo -e "\n--- We definitly need to see the PHP errors, turning them on ---\n"
sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.2/apache2/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.2/apache2/php.ini
sed -i "s/display_startup_errors = .*/display_startup_errors = On/" /etc/php/7.2/apache2/php.ini

echo -e "\n--- Restarting Apache ---\n"
service apache2 restart