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
There are 2 types of Roles <code>USER</code> and <code>ADMIN</code> and there are middlewares that are checking for the user logged in.
I am using [Laravel Passport](https://laravel.com/docs/11.x/passport) to handle the OAuth Authorization for users.
This is helpful in this example to create clients/providers (OAuth tokens) for users using default Passport endpoints which in this case are very similar as the [TrackTick flow](https://smoke.staffr.net/rest/v1/2020-01-01/system/oauth2)
This project is using [Laravel Sail](https://laravel.com/docs/11.x/sail) for setup simplicity.

# How to set up project

Clone this repository.
After that create <code>.env</code> file on the root directory and add the content of <code>.env.example</code> there.
On the terminal on the project root directory run:
```
git clone git@github.com:aokshtuni-ritech/sample-laravel-rest-api.git
composer install
./vendor/bin/sail up
```

The last command will create docker containers and start them.
This will take some time to complete, go get some coffee or do something else in the meantime.
Application will be running on [localhost](http://localhost) after this command.

If you are running this on MacOS with Apple chip, this maybe will not run very well.
You have to replace the content of <code>docker-compose.yml</code> with the content of <code>docker-compose-m1.yml</code>

To proceed with the steps forward on setting up the application run the following commands:

```
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan passport:keys --force
./vendor/bin/sail artisan passport:client --personal
```

These commands will Migrate the database and seed it.

There are already some users created at this point:

### Admin
```
email: admin@test.local
password: AdminLocal1
```

### User1
```
email: provider1@test.local
password: Provider1
```

### User2
```
email: provider2@test.local
password: Provider2
```

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

# From Admin side

You can manage User and EntityMapping entities using the Laravel API Resource.
List all Employees created by different providers and set the Integration token for <code>TrackTik</code> in this example.


### Integration Token
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

### Employees
List all employees created by all providers.
```
GET @ /api/v1/admin/employees
```

### User
Manage all User's of the application
```
GET @ /api/v1/admin/users
GET @ /api/v1/admin/users/{user}
POST @ /api/v1/admin/users
PUT @ /api/v1/admin/users/{user}
DELETE @ /api/v1/admin/users/{user}
```

### EntityMapping
Manage all EntityMapping's of the application
```
GET @ /api/v1/admin/entity-mappings
GET @ /api/v1/admin/entity-mappings/{entity-mapping}
POST @ /api/v1/admin/entity-mappings
PUT @ /api/v1/admin/entity-mappings/{entity-mapping}
DELETE @ /api/v1/admin/entity-mappings/{entity-mapping}
```
You can create a single entity mapping for the user, integration and entity_type.
There are a set of rule for that.

# From User/Provider side

### Employees
Manage all employees created by the current logged-in User/Provider
```
GET @ /api/v1/employees
GET @ /api/v1/employees/{employee}
POST @ /api/v1/employees
PUT @ /api/v1/employees/{employee}
DELETE @ /api/v1/employees/{employee}
```
When creating and updating an Employee record there will be a HTTP Request to TrackTik API and a log history of the request.
Ideally this should be handled by a queue system and the code is already there to handle such case.

This is an example of creating an employee while logged in as the first user provider.
```
curl --request POST \
--url http://localhost/api/v1/employees \
--header 'Accept: application/json' \
--header 'Authorization: Bearer TOKEN_HERE' \
--header 'Content-Type: application/json' \
--header 'User-Agent: insomnia/9.2.0' \
--data '{
"name_first": "Employee11",
"name_last": "Last11",
"email": "employee11@test.local",
"tags_list": [
"abc",
"def"
],
"work_position": "Manager",
"phone": "12435466"
}'
```

# Tests
There are also some simple tests included. To run these tests follow the steps:

```
./vendor/bin/sail artisan migrate:fresh --env=testing
./vendor/bin/sail artisan passport:keys --force
./vendor/bin/sail artisan test
```


