# Widget API

A simple Laravel application demonstrating various techniques for working with APIs.

## Requirements

* PHP 8.2
* A basic familiarity with Laravel
 
## Installation

~~~
git clone https://github.com/inveteratus/widget-api.git
cd widget-api
composer install
cp .env.example .env
php artisan key:generate

# edit .env to suit

php artisan migrate:fresh
php artisan serve
~~~

## License

The Widget API is open-sourced software licensed under the [MIT License](https://choosealicense.com/licenses/mit/).
