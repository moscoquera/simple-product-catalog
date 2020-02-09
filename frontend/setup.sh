#!/usr/bin/env bash

composer require


rm -rf node_modules
rm package-lock.json yarn.lock
npm cache clear --force
npm install --global cross-env
npm install webpack --save
npm install -s --no-cache

npm run dev

php artisan key:generate
