<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## About the project

This is a simple REST Api project which has the basics of Laravel and it is used as a proxy service to send data to another REST Api which in this case is [TrackTik](https://smoke.staffr.net/rest/v1/2020-01-01/core/entities#tag/employees/operation/createOneEmployees)
There are 2 types of Roles USER and ADMIN and there are middlewares that are checking for the user logged in.
I am using [Laravel Passport](https://laravel.com/docs/11.x/passport) to handle the OAuth Authorization for users.
This is helpful in this example to create clients/providers for users using default Passport endpoints.

## How to set up project

Clone this repository and from the project root directory run in terminal:
```
git clone git@github.com:aokshtuni-ritech/sample-laravel-rest-api.git
composer install
./vendor/bin/sail up
```
The last command will create docker containers and start them.
This will take some time to complete, go get some coffee or do something else in the meantime.
Application will be running on [localhost](http://localhost) after this command.

If you are running this on MacOS with Apple chip, this maybe will not run very well.

To proceed with the steps forward on setting up the application run the following commands:

```
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan passport:keys --force
./vendor/bin/sail artisan passport:client --personal
```

These commands will Migrate the database and seed it.

Now to start testing the REST Api you must do the login action as the admin created from the seeder.

cUrl for login action here:
```
curl --location 'http://localhost/api/v1/login' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
"email": "admin@test.local",
"password": "AdminLocal1"
}'
```

Then add the tokens to the DB since there I store the Integration tokens needed.
For this action you must pass the Authorization header to fill in the data (must be logged in as admin).
```
curl --location 'http://localhost/api/v1/admin/integration/1/set-token' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0e' \
--data '{
"access_token": "TOKEN",
"refresh_token": "REFRESH_TOKEN",
"client_id": "CLIENT_ID",
"client_secret": "CLIENT_SECRET"
}'
```

There are also some simple tests included. To run these tests follow the steps:

```
./vendor/bin/sail artisan migrate:fresh --env=testing
./vendor/bin/sail artisan passport:keys --force
./vendor/bin/sail artisan test
```


