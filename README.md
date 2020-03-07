# ALA - Automatic Lumen API
API Rest Full created in lumen that auto generate base code for simple crud (with unit tests)

## Context

I'm using SQL Server on this project for a reason. I fad to compatibilize the API code with an oldest database, for this reason, i've choose using the database manager with a querie layer instead the eloquent aproach. To inserts and updates i'm using the query builder.

## Architecture

Multiple **Routes** files, each route has your separeted **Controler** with calls a **Business** (with business rules). 

Each **Business** call one or more **Repository** (to save or load the data from database or others external sources).

One **Business** can also call a **Service** to deal with some especific group of rules or additional data processing.

There are some **Helpers** to, obviously helper in some matters like paginator, upload, generating XML or CSV files, deal with dates and etc...

Each database **Repository** call one or more **Queries** to get the correct queries to execute.

Also there's an optional feature called **Refiner** to pass optional parameters to queries, making easier to filter data.

I've try do everything with dependency injection to simplify the unit test process and tried so hard to respect the S.O.L.I.D. and PSR best pratices.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Most of prerequisites are fulfill by the docker itself (so you must have at least composer, docker and docker-compose), but in order to have a better experience on running i recommend these minimum settings:
```
php > 7.2
composer > 1.5.5
xdebug > 2.6 (I recommend Xdebug v2.6.1)
docker (recommended >= 18.09.0)
docker-compose (recommended >= 1.21.2)
```

### Dependencies

The composer do the job for you, don't worry, but these are the packages ALA depends on:
```
php: >=7.2
laravel/lumen-framework: 5.6.*
vlucas/phpdotenv: ~2.2
pda/pheanstalk: ^3.1
aws/aws-sdk-php: ^3.64
ramsey/uuid: ^3.8
moontoast/math: ^1.1
guzzlehttp/guzzle: ^6.3
squizlabs/php_codesniffer: 3.2
phpmd/phpmd: ^2.6
fzaninotto/faker": ~1.4
phpunit/phpunit": ~7.0
mockery/mockery": ~1.0
```

### Installing

1 - Clone from repository
```
https://github.com/leodisarli/automatic-lumen-api.git
```

2 - Install dependencies
```
composer install --prefer-dist
```

3 - Run project in docker-compose
```
docker-compose up
```

4 - Initialize the database
```
composer db-init
```

5 - Initialize enviroment
```
composer env-init
```

6 - Create your entity
```
php artisan create:entity sample
```

7 - Run migrate to populate database
```
composer migrate
```

### Running your new crud

1 - Import to postman the file
```
ala.postman_collection.json
```

2 - Use the endpoints to add, list, edit, see details, delete and see deleted details

### Automatic unit tests

All new created code have 100% unit test coverage, of course, if you change the code, you'll must change the tests. :)

### Other usefull commands

There are others usefull commands like:
```
composer lint -> check php sintax on your code
composer cs -> check your code design with code Sniffer
composer mess -> check your architeture and design with Mess Detector
composer test -> check your tests and coverate (coverage needs Xdebug)
composer check -> run all verifications above at one time
```

## Deployment

All docker structure as built-in on docker folder

I'm using Jenkins to deploy the project and there is a Jenkinsfile sample to guide you:
```
Jenkinsfile
```

Replace all follow variables values before run on Jenkins
```
{{your-repository/nginx-image}}
{{your-repository/php-image}}
{{your-uuid-credential-here}}
```

## Contributing

Please read [CONTRIBUTING.md](https://github.com/leodisarli/automatic-lumen-api/blob/master/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

When you try to commit, a hook will run the "composer check" command and stop your commit command if anything are wrong.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/leodisarli/automatic-lumen-api/tags). 


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Authors

* **Leonardo Di Sarli** - *Initial work* - [leodisarli](https://github.com/leodisarli)

## Colaborators

* **Diego Rego** - *Colaborator* - [diegorego](https://github.com/diegorego)
* **Luan Menezes** - *Colaborator* - [luanmnz](https://github.com/luanmnz)

## Builded with

* **AWS PHP SDK** - *AWS PHP SDK* - [awsphpsdk](https://aws.amazon.com/pt/sdk-for-php/)
* **Code Sniffer** - *Tool for PHP coding standards* - [codesniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* **Composer** - *Composer dependecy manager* - [composer](https://getcomposer.org/)
* **Docker Compose** - *Tool for running multi-container Docker* - [dockercompose](https://docs.docker.com/compose/)
* **Docker** - *Docker container platform* - [docker](https://www.docker.com/)
* **Faker** - *Faker data* - [faker](https://github.com/fzaninotto/Faker)
* **Guzzle** - *Guzzle HTTP Client* - [guzzle](http://docs.guzzlephp.org/en/stable/)
* **Lumen** - *Lumen framework* - [lumen](https://lumen.laravel.com/)
* **Mess Detector** - *Tool for PHP coding design* - [messdetector](https://phpmd.org/)
* **Mockery** - *Mockery PHP simple Mock* - [mokery](https://github.com/mockery/mockery)
* **PHP DotEnv** - *Load enviroments* - [phpdotenv](https://github.com/vlucas/phpdotenv)
* **PHP Unit** - *Testing framework* - [phpunit](https://phpunit.de/)
* **PHP** - *PHP language* - [php](http://www.php.net/)
* **UUID** - *UUID* - [uuid](https://github.com/ramsey/uuid)




