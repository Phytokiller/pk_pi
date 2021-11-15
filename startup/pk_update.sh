cd /var/www/html/pk_pi

git pull origin master

cd /var/www/html/pk_pi/www

composer install --no-interaction --prefer-dist --optimize-autoloader

php artisan migrate --force

php artisan optimize:clear