#! /bin/bash
composer install --working-dir=/var/www/back-end.g-learning.click
chmod -R 777 /var/www/back-end.g-learning.click/storage
chmod -R 777 /var/www/back-end.g-learning.click/bootstrap/cache