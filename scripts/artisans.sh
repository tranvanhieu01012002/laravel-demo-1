#!/bin/bash
PATH="/var/www/back-end.g-learning.click"
ARTISAN="${PATH}/artisan"

cd $PATH && composer dump-autoload

php $ARTISAN route:clear
php $ARTISAN route:cache

php $ARTISAN config:clear
php $ARTISAN config:cache

php $ARTISAN event:cache

php $ARTISAN cache:clear



