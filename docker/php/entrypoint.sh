#!/bin/bash

echo "Copy .env.example to .env. If the file does not exist."
cp -n /var/www/.env.example /var/www/.env

echo "Passing variables to .env."
sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/" /var/www/.env
sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/" /var/www/.env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" /var/www/.env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" /var/www/.env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" /var/www/.env

sed -i "s/REDIS_HOST=.*/REDIS_HOST=${REDIS_HOST}/" /var/www/.env
sed -i "s/REDIS_PASSWORD=.*/REDIS_PASSWORD=${REDIS_PASSWORD}/" /var/www/.env
sed -i "s/REDIS_PORT=.*/REDIS_PORT=${REDIS_PORT}/" /var/www/.env

sed -i "s/MAIL_MAILER=.*/MAIL_MAILER=${MAIL_MAILER}/" /var/www/.env
sed -i "s/MAIL_HOST=.*/MAIL_HOST=${MAIL_HOST}/" /var/www/.env
sed -i "s/MAIL_PORT=.*/MAIL_PORT=${MAIL_PORT}/" /var/www/.env

# Wait for MySQL
echo "Wait for MySQL..."
sleep 5

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed --force

# Clearing caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Run PHP-FPM
exec "$@"
