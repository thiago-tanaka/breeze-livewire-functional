#!/bin/bash
git pull
docker exec app4 php artisan config:clear
docker exec app4 php artisan cache:clear
docker exec app4 php artisan view:clear
docker exec app4 php artisan route:clear
docker exec app4 php artisan migrate

docker exec app4 php artisan config:cache
docker exec app4 php artisan route:cache
docker exec app4 php artisan view:cache

