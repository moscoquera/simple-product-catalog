#!/usr/bin/env bash

rm -rf vendor
rm -rf node_modules
rm -rf storage/app/public/*
rm -rf storage/logs
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/testing/*
rm -rf storage/framework/views/*

rm package-lock.json yarn.lock

npm cache clear --force
npm install --global cross-env

composer require

npm install webpack --save

npm install
npm run dev

php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan db:seed

php artisan passport:install

chown -Rf devuser:devuser storage
