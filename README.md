# CakePHP Application Example (CMS Tutorial) and Unit Test

This is a [CakePHP Cookbook's CMS tutorial](https://book.cakephp.org/3.0/en/tutorials-and-examples/cms/installation.html) with an additional unit test added to the application.

# Installation

```bash
$ docker-compose up -d
$ docker-compose run composer install --ignore-platform-reqs --no-interaction
$ docker-compose run php-cli bin/cake migrations migrate
$ docker-compose run php-cli ./vendor/bin/phpunit
```

When accessing http://localhost:8000/ in the browser the Welcome page will be displayed.

# shut down

```bash
docker-compose down
```
