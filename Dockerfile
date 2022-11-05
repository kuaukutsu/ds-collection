FROM php:7.4-cli-alpine3.16

################################
###    CLI BASE LAYER        ###
################################

# https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# make sure you can use HTTPS
RUN apk --update add ca-certificates

# persistent / runtime deps
RUN apk update \
    && apk add --no-cache --virtual .persistent-deps \
      git

# install and remove building packages
RUN install-php-extensions \
    opcache
