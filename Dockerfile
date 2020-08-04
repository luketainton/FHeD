FROM composer as build
COPY app /srv
RUN composer --working-dir=/srv install

FROM php:apache
LABEL maintainer="Luke Tainton <luke@tainton.uk>"
RUN docker-php-ext-install pdo_mysql && a2enmod rewrite remoteip
COPY vhost.conf /etc/apache2/sites-enabled/000-default.conf
COPY --from=build /srv /srv
