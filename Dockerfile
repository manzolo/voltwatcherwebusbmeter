ARG MYSQL_HOST
ARG MYSQL_PORT

FROM php:8.1.0-fpm as build

RUN apt-get update \
    && apt-get install -y --no-install-recommends vim curl debconf subversion git apt-transport-https apt-utils \
    build-essential locales acl mailutils wget zip unzip libzip-dev \
    gnupg gnupg1 gnupg2 \
    zlib1g-dev \
    libpng-dev \
    sudo

RUN docker-php-ext-install pdo pdo_mysql zip gd

COPY .docker/onlyapp/php/php.ini /usr/local/etc/php/php.ini
#COPY .docker/onlyapp/php/php-fpm-pool.conf 	/usr/local/etc/php/pool.d/www.conf

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
	mv composer.phar /usr/local/bin/composer

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m
RUN passwd -d dev

RUN rm -rf /var/lib/apt/lists/*
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "it_IT.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

RUN echo "dev ALL=(ALL) ALL" > /etc/sudoers

WORKDIR /home/wwwroot/voltwatcher

COPY . .

RUN rm -rf .git && \
    rm -rf config/jwt && \
    rm -rf node_modules && \
    rm -rf var && \
    rm -rf .env.local && \
    rm -rf .env && \
    cp .env.dist .env && \
    mkdir var && \ 
    chmod 777 -R var && \ 
    composer install --no-dev --optimize-autoloader

FROM php:8.1-apache

RUN apt update && apt install -y \
    apt-transport-https \
    software-properties-common \
    ca-certificates \
    curl \
    libonig-dev \
    zlib1g-dev \
    libpng-dev \
    libzip-dev \
    gnupg

RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip

WORKDIR /var/www/html
COPY --from=build /home/wwwroot/voltwatcher /var/www/html
RUN chown -R www-data:www-data /var/www/html

ENV MYSQL_HOST=mysqlhost
ENV MYSQL_PORT=3306

COPY --from=build /home/wwwroot/voltwatcher/.docker/onlyapp/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY --from=build /home/wwwroot/voltwatcher/.docker/onlyapp/apache/start-apache /usr/local/bin/
RUN chmod +x /usr/local/bin/start-apache
RUN apachectl configtest
RUN a2enmod rewrite
RUN a2enmod remoteip
RUN a2enmod env

RUN rm -rf /var/www/html/.env
RUN rm -rf /var/www/html/.env.docker.local
RUN rm -rf /var/www/html/.env.dist
RUN rm -rf /var/www/html/.env.local
RUN echo "APP_ENV=prod" > .env

EXPOSE 80

CMD ["/usr/local/bin/start-apache"]

# *** Symfony CLI *** 
#
#FROM php:8.1-cli
#
#RUN apt-get update -y && apt-get install -y \
#    libmcrypt-dev \
#    libonig-dev \
#    zlib1g-dev \
#    libpng-dev \
#    libzip-dev \
#    wget
#
#RUN docker-php-ext-install pdo pdo_mysql zip gd
#
#
#RUN docker-php-ext-install pdo mbstring
#
#WORKDIR /app
#COPY --from=build /home/wwwroot/voltwatcher /app
#
#ENV MYSQL_HOST=mysqlhost
#ENV MYSQL_PORT=3306
#
## Symfony tool
#RUN wget https://get.symfony.com/cli/installer -O - | bash && \
#  mv /root/.symfony/bin/symfony /usr/local/bin/symfony
#RUN symfony server:ca:install
#EXPOSE 80
#CMD symfony serve --port=80