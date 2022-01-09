#!/bin/sh

docker-compose exec appshortener bash -c 'composer install'
docker-compose exec appshortener bash -c 'chmod 777 storage -R'
docker-compose exec appshortener bash -c 'cp .env.example .env'
docker-compose exec appshortener bash -c 'php artisan migrate:fresh --seed'

