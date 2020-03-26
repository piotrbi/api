/bin/sh

cd /var/www/html && \
composer global require hirak/prestissimo --no-plugins --no-scripts && \
composer install && \
chown -R 1000:1000 /var/www/html/vendor /var/www/html/var && \

bin/console doctrine:migrations:migrate -q && \
bin/console doctrine:fixtures:load -q && \

bin/console doctrine:database:create --env=test --if-not-exists -q && \
bin/console doctrine:migrations:migrate --env=test -q
