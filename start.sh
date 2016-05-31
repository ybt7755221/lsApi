#!/bin/sh

# Author : Burt
# Script follows here:

# Check the composer.
if [ "$1" = '--auto' ]; then
	echo 'Checking the composer...'
	COMPOSER_CMD=$(which composer.phar)
	if [[ "" == "$COMPOSER_CMD" ]]; then
		echo "$COMPOSER_CMD"
		echo 'Can not found your composer, Please install the composesr and added it to enviroment variable.'
		exit 0
	fi
	echo 'Check he composer is done.'
fi

# Modifying the file Competence
echo "Modifying the file Competence..."
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
echo "Modify the file Competence is done."

# Install vendor folder
if [ "$1" = '--auto' ]; then
	if [ ! -d "./vendor" ]; then
		echo 'Installing the vendor from network...'
		$COMPOSER_CMD install
	    echo 'Successfully install the vendor folder.'
	else
		read -p 'You had a vendor folder. So we can not install it.If you want to update it?[ "Y" or "N"] ' update_status
		if [ "$update_status" = 'Y' -o "$update_status" = 'y' ]; then
			$COMPOSER_CMD update
		fi
	fi
fi

# Check env file and create .env file
ENV_DIR="./.env"
if [ -f "$ENV_DIR" ]; then
	read -p "You had a '.env' file, Are you sure thar you want to cover it?['Y' or 'N'] " cover_status
	if [ "$cover_status" == "n" -o "$cover_status" == 'N' ]; then
		echo 'The install process have been stop.'
		exit 0
	else
		continue
	fi
fi
echo 'Create the ".env" configuration file...'
cp .env.default .env
php artisan key:generate
echo 'Successfully create the config variable file ".env".'

# Create the all of tables in the database
read -p 'Please ensure you had finished the database configuration in the ".env" file [Y or N]:' checkVar
if [ "$checkVar" = 'y' -o "$checkVar" = 'Y' ]; then
	if [ "$1" = '--semi-auto' -o "$1" = "" ]; then
		if [ ! -d  "./vendor" ]; then
			echo "Not found the vendor folder. Please download the vendor firstly. you can use composer or directly download it from Laravel github. We recommond the laravel version >=5.2 "
			exit 0
		fi
	fi
    echo 'Installing the database...'
    php artisan migrate
else
    echo 'You can add your database information to ".env" file and the run "php artisan migrate:install" to create the database.'
    exit 0
fi

#Insert the baseline data and seed data.
echo 'Inserting the baseline data...'
php artisan db:seed --class=DatabaseSeeder
if [ "$2" = "--dev" -o "$2" = "" ]; then 
	echo 'Inserting the seed data...'	
	php artisan db:seed --class=SeedDataSeeder
fi
echo "Congratulations! You finished all work, you can login the LsApi now."