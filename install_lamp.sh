#! /usr/bin/env bash

# Modified from https://gist.github.com/rrosiek/8190550
# * Updated version to make current releases in Ubuntu 18.04
# * Added Angular CLI

###
#
# install_lamp.sh
#
# This script assumes your Vagrantfile has been configured to map the root of
# your application to /vagrant and that your web root is the "public" folder
# (Laravel standard).  Standard and error output is sent to
# /vagrant/vm_build.log during provisioning.
#
###

# Variables
DBHOST="localhost"
DBNAMES=("blog" "gradebook" "library" "project" "school" "todo")
DBUSER="dbuser"
DBPASSWD="test123"

echo -e "\n--- LAMP, installing now... ---\n"

echo -e "\n--- Updating packages list ---\n"
apt-get -qq update

echo -e "\n--- Install base packages (VIM, NPM, Curl) ---\n"
apt-get -y install vim curl build-essential software-properties-common git > /vagrant/vm_build.log 2>&1

echo -e "\n--- Add Node 8 ---\n"
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash - >> /vagrant/vm_build.log 2>&1

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
apt-get -y install mysql-server phpmyadmin >> /vagrant/vm_build.log 2>&1

echo -e "\n--- Setting up our MySQL user and db ---\n"
for n in "${DBNAMES[@]}"
do
    echo -e "Adding MySQL Database: $n\n"
    mysql -uroot -p$DBPASSWD -e "CREATE DATABASE $n" >> /vagrant/vm_build.log 2>&1
    mysql -uroot -p$DBPASSWD -e "GRANT ALL PRIVILEGES ON $n.* TO '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASSWD'" >> /vagrant/vm_build.log 2>&1
done

echo -e "\n--- Temporary fix for phpMyAdmin 4.6 and PHP 7.2 ---\n"
sed -i "s/|\s*\((count(\$analyzed_sql_results\['select_expr'\]\)/| (\1)/g" /usr/share/phpmyadmin/libraries/sql.lib.php

echo -e "\n--- Installing PHP-specific packages ---\n"
apt-get -y install php apache2 libapache2-mod-php php-curl php-gd php-mysql php-gettext php-mbstring php-zip >> /vagrant/vm_build.log 2>&1

echo -e "\n--- Enabling mod-rewrite ---\n"
a2enmod rewrite >> /vagrant/vm_build.log 2>&1

echo -e "\n--- Allowing Apache override to all ---\n"
sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

echo -e "\n--- Setting document root to public directory ---\n"
rm -rf /var/www/html
ln -fs /vagrant/public /var/www/html

echo -e "\n--- Give add vagrant user to the www-data group ---\n"
adduser vagrant www-data

echo -e "\n--- We definitly need to see the PHP errors, turning them on ---\n"
sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.2/apache2/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.2/apache2/php.ini
sed -i "s/display_startup_errors = .*/display_startup_errors = On/" /etc/php/7.2/apache2/php.ini

echo -e "\n--- Restarting Apache ---\n"
service apache2 restart >> /vagrant/vm_build.log 2>&1

echo -e "\n--- Installing Composer for PHP package management ---\n"
curl --silent https://getcomposer.org/installer | php >> /vagrant/vm_build.log 2>&1
mv composer.phar /usr/local/bin/composer

echo -e "\n--- Installing unzip to support composer ---\n"
sudo apt install unzip

echo -e "\n--- Installing NodeJS ---\n"
apt-get -qy install nodejs >> /vagrant/vm_build.log 2>&1

#echo -e "\n--- Installing javascript components ---\n"
#npm install -g gulp bower >> /vagrant/vm_build.log 2>&1

echo -e "\n--- Installing Angular CLI ---\n"
npm install -g @angular/cli >> /vagrant/vm_build.log 2>&1

echo -e "\n--- Updating project components and pulling latest versions ---\n"

cd /vagrant

if [[ -s /vagrant/composer.json ]] ;then
  sudo -u vagrant -H sh -c "composer install" >> /vagrant/vm_build.log 2>&1
fi

if [[ -s /vagrant/package.json ]] ;then
  sudo -u vagrant -H sh -c "npm install" >> /vagrant/vm_build.log 2>&1
fi

if [[ -s /vagrant/bower.json ]] ;then
  sudo -u vagrant -H sh -c "bower install -s" >> /vagrant/vm_build.log 2>&1
fi

if [[ -s /vagrant/gulpfile.js ]] ;then
  sudo -u vagrant -H sh -c "gulp" >> /vagrant/vm_build.log 2>&1
fi

echo -e "\n--- Creating a symlink for future phpunit use ---\n"

if [[ -x /vagrant/vendor/bin/phpunit ]] ;then
  ln -fs /vagrant/vendor/bin/phpunit /usr/local/bin/phpunit
fi
