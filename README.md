//Run the following commands to set up the project:

//Installation Steps
git clone <repository-url>
cd <project-folder>

composer install
cp .env.example to .env

php artisan key:generate

php artisan migrate
php artisan db:seed

php artisan serve

Note - please keep xampp server and mysql running

URL: http://127.0.0.1:8000/login
Username: superadmin@gmail.com
Password: 12345678