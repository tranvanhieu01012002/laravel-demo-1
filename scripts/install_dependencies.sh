#! /bin/bash
PATH="/var/www/back-end.g-learning.click"
composer install --working-dir=$PATH
chmod -R 777 $PATH/storage
chmod -R 777 $PATH/cache