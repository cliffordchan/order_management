sudo docker-compose build --no-cache
sudo docker-compose up
docker-compose run laravel php artisan migrate
exit 0