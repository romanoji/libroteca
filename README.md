# Libroteca - Simple, DDD based library management app

It's a simple demo of **DDD** project created for learning purposes.

You can find here some uses of **CQS** approach and **Hexagonal Architecture**.

Project has been split into two parts - first concerns business logic ([Application](./src/Application) and [Domain](./src/Domain)) and the second is infrastructure.

The infrastructure part was created on two independent environments.

One is based on [Symfony framework](http://symfony.com/) with [PostgreSQL](https://www.postgresql.org/) relational database.

The other one works on [Lumen framework](https://lumen.laravel.com/) and [MongoDB](https://www.mongodb.com/) NoSQL database.

Both environments are using one business logic to achieve almost the same result.

## Requirements

* [PHP ^7.1](http://php.net/)

* [Docker](https://docs.docker.com/engine/installation/)

* [Composer](https://getcomposer.org/) - `curl -sS https://getcomposer.org/installer | php` to download.

## Quick setup

Both applications use Docker with Docker Compose to help with the setup.

Below you can find instructions for each of them.

### Before starting

Run `composer install --ignore-platform-reqs` to install all required dependencies.

_*(required if you only want to run specs)_

Application is running on [localhost:8080](http://localhost:8080) by default.

You can import [this file](./docs/Libroteca.postman_collection.json) in the [Postman](https://www.getpostman.com/) to check all available endpoints.

### Symfony + PostgreSQL

`bin/symfony-app up` - to build & start Symfony app.

### Lumen + MongoDB

`bin/lumen-app up` - to build & start Lumen app.

### Running specs

`bin/behat` to run all Behat scenarios.

`bin/phpspec run` to run all PHPSpec specifications.
