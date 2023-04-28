#! /bin/bash
composer install --working-dir=/var/www/back-end.g-learning.click/
sudo chmod -R 755 /var/www/back-end.g-learning.click/storage
sudo chmod -R 755 /var/www/back-end.g-learning.click/bootstrap/cache