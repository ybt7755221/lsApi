LsAPI - a openAPI platform.
====
Introduction
----
The LsAPI is a open Api platform. The customer can create and publish their Api and added data and through the Restful way to get data use any machine (including mobile or pc or others).

The LsAPI is based on Laravel 5.2. Now we just work on the apache and mysql version, the cache use file cache in order to provide virtual space customer. In the feature we will provide the nginx and nosql version and use memcache/redis as cache system in order to provide customer that there is server machine.

How to start to run this project. (3 ways)
----
#### NO.1 The fully automatic installation.
THE STEP: 

* The firstly is configurate your database information to 'env.default' file.
* The finally is directly run 'start.sh' shell script in terminal.
  
CODE:

    sh start.sh --auto
  
SYSTEM REQUIREMENTS: 

  * All of the operation system based on Unix/Linux.
  * There are the full php enviroment. Including:
    * php 5.5 or higher
    * apache 2.4 or higher
    * mysql 5.5 or higher
    * composer and you need added it to enviroment variable

#### NO.2 The semi-automatic installation.
THE STEP:

* The firstly is configurate your database information to 'env.default' file.
* The secondly is run 'composer install' in terminal.
* The Lastly is run shell script in terminal.

CODE:
    
    composer install              // Change to your configration.
    sh start.sh --semi-auto
    
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

The LsApi system come from chinese. The developer english level in general. I gratefully welcome corrections about anything.
