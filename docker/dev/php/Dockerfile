# First, declare all ARGs at the top with default values
ARG UBUNTU_IMAGE=ubuntu:jammy
ARG COMPOSER_IMAGE=composer:2.8.6

# Create composer stage first
FROM ${COMPOSER_IMAGE} AS composer

# Then create the base cli image
FROM ${UBUNTU_IMAGE} AS cli

ARG WWWUSER
ARG WWWGROUP

# Fixes some weird terminal issues such as broken clear / CTRL+L
ENV TERM=linux

# Ensure apt doesn't ask questions when installing stuff
ENV DEBIAN_FRONTEND=noninteractive

# Install Ondrej repos for Ubuntu jammy, PHP, composer and selected extensions - better selection than
# the distro's packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends gnupg \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ondrej-php.list \
    && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 4F4EA0AAE5267A6C \
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        ca-certificates \
        curl \
        unzip \
        php8.3-apcu \
        php8.3-cli \
        php8.3-curl \
        php8.3-mbstring \
        php8.3-opcache \
        php8.3-readline \
        php8.3-xml \
        php8.3-zip \
        php8.3-gd \
        php8.3-redis \
        php8.3-bcmath \
        php8.3-intl \
        php8.3-pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Copy composer from the dedicated stage
COPY --from=composer /usr/bin/composer /usr/bin/composer

CMD ["php", "-a"]

RUN groupadd --force -g $WWWGROUP web
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER web

USER web

WORKDIR "/application"
