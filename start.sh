#!/bin/bash
if [ -f .env ] ; then export $(cat .env | sed 's/\r$//#.*//g' | xargs) ; fi ;

mysql -h $DB_HOST -u root -p${DB_PASSWORD} << EOF
    # Create database
    CREATE DATABASE IF NOT EXISTS $DB_DATABASE;
    # Create test database
    CREATE DATABASE IF NOT EXISTS ${DB_DATABASE}_test;

    #Grant .env user privilege to both databases
    GRANT ALL PRIVILEGES ON ${DB_DATABASE}.* TO '${DB_USERNAME}'@'%';
    GRANT ALL PRIVILEGES ON ${DB_DATABASE}_test.* TO '${DB_USERNAME}'@'%';
EOF

php artisan migrate;
php artisan migrate --env=testing;
php artisan db:seed;

php artisan key:generate;
php artisan key:generate --env=testing;

echo Token:
php artisan token:create admin;