#! /bin/bash
sudo chmod -R 755 /var/www/back-end.g-learning.click/storage
sudo chmod -R 755 /var/www/back-end.g-learning.click/bootstrap/cache
cd /var/www/back-end.g-learning.click/ && composer install --optimize-autoloader --no-dev