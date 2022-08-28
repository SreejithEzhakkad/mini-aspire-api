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

  Start the application in local server.

`$ php artisan serve`

Import the post man collection and start testing - https://www.getpostman.com/collections/e8df6361d759c4cc615a


