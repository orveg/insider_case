cp .env.example .env;
composer install
npm install
php artisan migrate
php artisan db:seed
npm run dev
echo "Install Done!"
