#!/bin/bash

cron -f &

composer run setup

#php artisan serve --host=0.0.0.0 --port=8000 &

php artisan queue:listen --tries=1 &

service nginx start

php-fpm

#systemctl start nginx &
#systemctl enable nginx
