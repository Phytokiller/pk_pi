sudo chown www-data:www-data /var/www
sudo chmod -R 777 /var/www/html/pk_pi/www/bootstrap/cache
sudo chmod -R 777 /var/www/html/pk_pi/www/storage
sudo usermod -a -G www-data pi

cd /var/www/html/pk_pi

git pull origin master

cd /var/www/html/pk_pi/www

composer install --no-interaction --prefer-dist --optimize-autoloader

php artisan migrate --force

php artisan optimize:clear