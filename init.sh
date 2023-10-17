composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed --force
php artisan serve
