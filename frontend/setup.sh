#!/usr/bin/env bash

rm -rf vendor
rm -rf node_modules

composer require


rm package-lock.json yarn.lock
npm cache clear --force
npm install --global cross-env
npm install webpack --save
npm install -s --no-cache

npm run dev

php artisan key:generate
