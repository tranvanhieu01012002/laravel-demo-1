#!/bin/bash

ARTISAN="/var/www/back-end.g-learning.click/artisan"

cd /var/www/back-end.g-learning.click && composer dump-autoload

php /var/www/back-end.g-learning.click/artisan route:clear
php /var/www/back-end.g-learning.click/artisan route:cache

php /var/www/back-end.g-learning.click/artisan cache:clear

php /var/www/back-end.g-learning.click/artisan config:clear

php /var/www/back-end.g-learning.click/artisan config:cache

php $ARTISAN event:cache



