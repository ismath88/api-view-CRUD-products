FROM xlazer/api-platform

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
	; \
	\
	pecl install \
		ds \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		ds \
	; \
	\
	apk del .build-deps

ENV CONFD_VERSION 0.11.0

RUN curl -fSL https://github.com/kelseyhightower/confd/releases/download/v$CONFD_VERSION/confd-$CONFD_VERSION-linux-amd64 -o /usr/local/bin/confd \
	&& chmod +x /usr/local/bin/confd

# COPY docker/app/php.ini /usr/local/etc/php/php.ini
# COPY docker/app/php-fpm.d/child-processes.conf /usr/local/etc/php-fpm.d/child-processes.conf

WORKDIR /srv/api

# build for production
ARG APP_ENV=prod

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock ./
RUN set -eux; \
	composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --no-suggest; \
	composer clear-cache

COPY . ./

RUN set -eux; \
	composer dump-autoload --classmap-authoritative --no-dev

RUN set -eux; \
	mkdir -p public/uploads \
	&& mkdir -p var/cache var/log var/tmp \

VOLUME /srv/api/public/uploads

COPY docker/app/confd /usr/local/etc/confd/
COPY docker/app/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint
