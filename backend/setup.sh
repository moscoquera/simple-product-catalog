#!/usr/bin/env bash

composer require
npm install
npm run dev

php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan db:seed
