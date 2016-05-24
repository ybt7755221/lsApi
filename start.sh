#!/bin/sh
#!/usr/bin/php

# Author : Burt
# Script follows here:

file="./.env"

if [ ! -f "$file" ]; then
    echo "Modified the file Competence..."
    chmod -R 777 storage/
    chmod -R 777 bootstrap/cache/
    cp .env.example .env
    echo 'Create the config variable file ".env", you can modified that file to modified the project configuration.'
else
    echo 'You had installed this project.'
fi