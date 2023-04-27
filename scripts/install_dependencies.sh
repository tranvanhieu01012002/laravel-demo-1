#!/bin/bash
PATH="/var/www/back-end.g-learning.click"
cd $PATH && chmod -R 755 storage bootstrap/cache
cd $PATH && composer install -n