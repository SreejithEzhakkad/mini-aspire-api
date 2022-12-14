## About this App

  
This is a mini Aspire API(Aspire Lite). Customers can register, log in, request loans, view their loans, and repay the approved loan. Admin can register, log in, view all loans, and approve loan requests.

## Installation

Clone the repository.

`$ git clone git@github.com:SreejithEzhakkad/mini-aspire-api.git`

  Move to app folder `mini-aspire-api`.

`$ cd mini-aspire-api`

  Install required packages.

`$ composer install`

  Copy `.env.example` to `.env` and change required values values including Database.

`$ cp .env.example .env`

  Run the migration.

`$ php artisan migrate`

  To seed some test values(customer account, admin account, and loan).

`$ php artisan db:seed`

  Start the application in local server.

`$ php artisan serve`

To view the APIs and its documentation, import the postman API collection. Review the collection variables. 
If required change the collection variable `base_url` value. 
The `customer_api_token` and `admin_api_token` varaiable will be automatically updated on register, login and logout.

Postman Collection: https://www.getpostman.com/collections/e8df6361d759c4cc615a

## Testing

I recommend creating a separate database for testing because I have enabled the  'database resetting' after testing. 

Create a test enviornment file using the following command.

`$ cp .env .env.testing`

Update test database credentials in `.env.testing`

Clear config cache to detect new test environment.

`$ php artisan config:clear`

  Run the migration in test database.

`$ php artisan migrate --env=testing`

Run the test

`$ php artisan test`