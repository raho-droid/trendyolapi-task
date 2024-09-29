This application is to pull a large amount of data from a trendyol api using laravel job and queue and save this data to the database and list it.
I did not add css because there is no evaluation about the appearance in the task document.
Sorry for that.

Please run the following commands after cloning the repository.
(Don't forget to customize the env file.)

// to create database
php artisan migrate

// to work server
php artisan serve

//to work queue
php artisan queue:work --daemon
