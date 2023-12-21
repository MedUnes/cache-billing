ARG PHP_VERSION=8.0

FROM php:${PHP_VERSION}-cli-bullseye

ARG PHP_EXTENSION_DIR="/usr/local/lib/php/extensions"

ENV TZ="Europe/Berlin"
ENV LC_ALL="C.UTF-8"
ENV LANG="C.UTF-8"

# Setup common linux tools
RUN apt update && \
    apt install -y --no-install-recommends \
    procps \
    git \
    wget \
    bash-completion \
    sudo \
    libicu-dev \
    pkg-config \
    libpng-dev \
    zip \
    unzip \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev;

# Setup Symfony CLI
RUN wget https://github.com/symfony-cli/symfony-cli/releases/download/v5.7.6/symfony-cli_5.7.6_amd64.deb && \
    dpkg -i symfony-cli_5.7.6_amd64.deb && \
    rm symfony-cli_5.7.6_amd64.deb;

# Some needed PHP extensions
ENV PHP_EXTENTIONS="intl bcmath gd"
RUN docker-php-ext-install $PHP_EXTENTIONS

# docker user setup
RUN set -ex; \
    echo "PS1='\h:\w\$ '" >> /etc/bash.bashrc; \
    echo "alias ls='ls --color=auto'" >> /etc/bash.bashrc; \
    echo "alias grep='grep --color=auto'" >> /etc/bash.bashrc;

RUN useradd --create-home --shell /bin/bash docker \
    && passwd docker -d \
    && adduser docker sudo;

RUN usermod -aG www-data docker

# Don't require a password for sudo
RUN echo "docker ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN mkdir /app
ENTRYPOINT []
WORKDIR /app
STOPSIGNAL SIGQUIT

CMD tail -f /dev/null
