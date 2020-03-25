## Instalation

* Clone git clone https://github.com/eko/docker-symfony.git into some empty directory
* In the docker-symfony dir clone clone this repository .... 

## Run it
Run docker from docker-symfony dir
```
$ docker-compose up
```


## Test it
Create test database:
```bash
docker exec -it php-fpm sh -c \
"bin/console doctrine:database:create --env=test --if-not-exists -q && \
bin/console doctrine:migrations:migrate --env=test -q"
```
Run tests:
```bash
docker exec -it php-fpm sh -c "bin/phpunit"
```
Test output
```bash
PHPUnit 8.3.5 by Sebastian Bergmann and contributors.

Testing Project Test Suite
...............                                                   15 / 15 (100%)

Time: 3.53 seconds, Memory: 50.50 MB

OK (15 tests, 42 assertions)
```