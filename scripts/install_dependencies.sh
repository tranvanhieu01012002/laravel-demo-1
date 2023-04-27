#!/bin/bash
PATH="/var/www/back-end.g-learning.click"
sudo chmod -R 755 $PATH/storage $PATH/bootstrap/cache
cd $PATH && composer install -n