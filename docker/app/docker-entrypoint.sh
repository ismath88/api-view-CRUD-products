#!/bin/sh
set -e

confd --onetime --confdir /usr/local/etc/confd --backend env

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
	mkdir -p public/uploads
	mkdir -p var/cache var/log var/tmp
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var public/uploads
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var public/uploads

	composer install --prefer-dist --no-progress --no-suggest --no-interaction
	>&2 echo "Waiting for Postgres to be ready..."
        until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
		sleep 1
	done

	if [ "$(ls -A src/Migration)" ]; then
		php bin/console doctrine:migrations:migrate --no-interaction 
	fi
fi

exec docker-php-entrypoint "$@"
