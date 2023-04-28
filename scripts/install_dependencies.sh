#! /bin/bash

composer install --working-dir=/var/www/back-end.g-learning.click
chmod -R 755 /var/www/back-end.g-learning.click/storage
chmod -R 755 /var/www/back-end.g-learning.click/cache