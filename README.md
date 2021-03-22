# Pizza API


### Tools / Frameworks used

* Lumen
* Docker
* MySQL

## Install & Start

* Install docker for your OS
* All other dependencies are installed automatically
* Start using:

```bash
$ docker-compose up
```

## Migrate database

```bash
$ docker-compose exec php artisan migrate
```


## Run test suite

```bash
$ docker-compose exec php phpunit
```

## Regenerate OpenApi Docs

```bash
$ docker-compose exec php openapi app/ -o public/api.json
```

## Accessing API and docs

* The api is accessible under `http://localhost`
* The docs are accessible under `http://localhost/docs`

### Trying out the API in swagger-ui

*Before you try to execute any requests, make sure to authenticate using the green authorize button in the upper right. Paste the API-Key from you `api/.env` file*


