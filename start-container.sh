#!/bin/sh
echo 'starting app ... '
cd /var/www

echo 'installing pm2...'
npm install -g pm2

echo 'caching data ... '
php artisan storage:link
php artisan key:generate
php artisan schedule-monitor:sync

echo 'building front ...'
yarn sass ./resources/sass/app.scss ./public/css/app.css
yarn build

echo "Deleting manifest"
php artisan front:clear

echo 'start worker & nginx... '
pm2 start ./queueworker.yml
nginx -t && service nginx restart

echo 'launch '
/usr/local/sbin/php-fpm -R
