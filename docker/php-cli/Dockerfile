FROM phpdockerio/php:7.4-cli
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install nodejs curl php7.4-mysql php-xdebug php7.4-bcmath php7.4-gd php-imagick php7.4-intl php-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN rm -rf /usr/bin/php && ln -s /usr/bin/php7.4 /usr/bin/php
