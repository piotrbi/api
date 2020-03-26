## Instalation

Clone repo to empty dir and run ```$ docker-compose up```. 

Also you need to init DB and install composer dependencies, to do so, run init script from the docker:
```
docker exec api_php /bin/sh -c "./docker/init.sh"
```

Also you need to add ```127.0.0.1 api.local``` to your **/etc/hosts** file.

After that you can visit http://api.local:8082/api page, where you can find swagger api documentation.

## Test it

Run tests:
```bash
docker exec api_php /bin/sh -c "bin/phpunit"
```
Test output
```bash
PHPUnit 8.3.5 by Sebastian Bergmann and contributors.

Testing Project Test Suite
...............                                                   15 / 15 (100%)

Time: 792 ms, Memory: 30.00 MB

OK (15 tests, 42 assertions)
```