#!/bin/bash

composer install --working-dir=/var/www/back-end.g-learning.click
composer dump-autoload--working-dir=/var/www/back-end.g-learning.click

php /var/www/back-end.g-learning.click/artisan route:clear
php /var/www/back-end.g-learning.click/artisan route:cache

php /var/www/back-end.g-learning.click/artisan config:clear
php /var/www/back-end.g-learning.click/artisan config:cache

php /var/www/back-end.g-learning.click/artisan event:cache

php /var/www/back-end.g-learning.click/artisan cache:clear



