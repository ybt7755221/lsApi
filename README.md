LsAPI - a openAPI platform.
====
How to start to run this project. (3 ways)
----
#### NO.1 The fully automatic installation.
THE STEP: 

* The firstly is configurate your database information to 'env.default' file.
* The finally is directly run 'start.sh' shell script in terminal.
  
CODE:

    sh start.sh
  
SYSTEM REQUIREMENTS: 

  * All of the operation system based on Unix/Linux.
  * There are the full php enviroment. Including:
    * php 5.5 or higher
    * apache 2.4 or higher
    * mysql 5.5 or higher
    * composer and you need added it to enviroment variable.
  * Recommond you need to install node.js and npm

#### NO.2 The semi-automatic installation.
THE STEP:

* The firstly is configurate your database information to 'env.default' file.
* The secondly is run 'composer install' in terminal.
* The Lastly is run shell script in terminal.

CODE:
    
    composer install              // Change to your configration.
    sh start.sh
    
SYSTEM REQUIREMENTS: 

  * All of the operation system based on Unix/Linux.
  * There are the full php enviroment. Including:
    * php 5.5 or higher
    * apache 2.4 or higher
    * mysql 5.5 or higher
    * composer

#### NO.3 The no-script installation.
THE STEP:

* Configurate your database information to 'env.default' file.
* You need copy 'env.default' file to '.env' file.
* Run artisan to set project key.
* Run 'composer install' to get vendor folder
* Run artisan to install database.

CODE:
    
    vim .env.default                // Change to your configration.
    cp .env.default .env            // Create the config file for project.
    php artisan key:generate        // Create the key for project.
    composer install                // Install the vendor folder from network.
    php artisan migrate:install     // Install all table to the database
    
SYSTEM REQUIREMENTS: 

  * All operation system.
  * There are the full php enviroment. Including:
    * php 5.5 or higher
    * apache 2.4 or higher
    * mysql 5.5 or higher
    * composer
