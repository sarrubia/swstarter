#!/bin/bash

cron -f &

php artisan serve --host=0.0.0.0 --port=8000 &
php artisan queue:listen --tries=1

