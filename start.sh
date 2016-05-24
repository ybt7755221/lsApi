#!/bin/sh
#!/usr/bin/php

# Author : Burt
# Script follows here:

echo "Modifying the file Competence..."
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
echo "Modifying the file Competence is done"

file="./.env"

if [ ! -f "$file" ]; then
    echo 'Create the ".env" configuration file...'
    cp .env.example .env
    php artisan key:generate
    echo 'Create the config variable file ".env", you can modified that file to modified the project configuration.'
else
    echo 'Congratulations! You had installed this project, you can start to work on lsApi, If you still can not running it, please check "README.md" file again.'
fi