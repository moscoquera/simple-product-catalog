#!/usr/bin/env bash

rm -rf vendor
rm -rf node_modules

composer require

rm package-lock.json yarn.lock
npm cache clear --force
npm install --global cross-env
npm install webpack --save

npm install
npm run dev

php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan db:seed
