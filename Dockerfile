ARG MYSQL_HOST
ARG MYSQL_PORT

FROM php:8.1-apache AS build

#Core
RUN apt update -y && \
apt install git wget unzip  -y 

#Dependencies for php extension
RUN apt update -yqq && \
apt install -yqq  \
#pgsql
libpq-dev \ 
#sqlite
libsqlite3-dev \
#curl
libcurl4-gnutls-dev \
#icu
libicu-dev \
#zip
libzip-dev \
#png - jpeg
libjpeg-dev libpng-dev \
#xml
libxml2-dev \
#bz2
libbz2-dev \
#ldap
libldap2-dev \
#mbstring
libonig-dev


RUN docker-php-ext-install pdo_mysql curl mbstring gd xml zip bz2 opcache intl

#APCU php extension
ENV APCU_VERSION 5.1.21
RUN pecl install apcu-${APCU_VERSION} && docker-php-ext-enable apcu

#Set Timezone Europe/Rome
RUN echo "date.timezone=Europe/Rome" > /usr/local/etc/php/php.ini
RUN php -m
RUN php -i

#Composer
RUN wget https://getcomposer.org/installer -O composer-setup.php && \
php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
php -r "unlink('composer-setup.php');" && \
composer config -g secure-http false

#Clean package
RUN apt clean && apt autoremove -y && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/src

#Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN symfony

COPY .docker/vhost.conf /etc/apache2/conf-enabled/vhost.conf

RUN a2enmod rewrite
RUN a2enmod remoteip
RUN a2enmod env

WORKDIR /home/wwwroot/voltwatcher

COPY . .

RUN rm -rf .git && \
    rm -rf config/jwt && \
    rm -rf node_modules && \
    rm -rf var && \
    rm -rf .env && \
    rm -rf .env.local && \
    rm -rf .env.local && \
    rm -rf .env.docker.local && \
    rm -rf vendor && \
    cp .env.dist .env && \
    mkdir var && \ 
    chmod 777 -R var && \ 
    composer install --no-dev --optimize-autoloader && \
    rm -rf yarn.lock && \
    rm -rf webpack.config.js && \
    rm -rf tools && \
    rm -rf nbproject && \
    rm -rf docker-compose.yml && \
    rm -rf doc && \
    rm -rf build.xml && \
    rm -rf assets && \
    rm -rf Dockerfile && \
    rm -rf .yarnrc.yml && \
    rm -rf .yarn && \
    rm -rf .vscode && \
    rm -rf .php-version && \
    rm -rf .gitlab-ci.yml && \
    rm -rf .gitignore && \
    rm -rf .dockerignore && \
    #rm -rf .docker && \
    rm -rf .composer_cache && \
    rm -rf .env  && \
    rm -rf .env.dist

RUN symfony security:check


FROM php:8.1-apache AS prod

#Dependencies for php extension
RUN apt update -yqq && \
apt install -yqq  \
#pgsql
libpq-dev \ 
#sqlite
libsqlite3-dev \
#curl
libcurl4-gnutls-dev \
#icu
libicu-dev \
#zip
libzip-dev \
#png - jpeg
libjpeg-dev libpng-dev \
#xml
libxml2-dev \
#bz2
libbz2-dev \
#ldap
libldap2-dev \
#mbstring
libonig-dev

RUN docker-php-ext-install pdo_mysql curl mbstring gd xml zip bz2 opcache intl

#APCU php extension
ENV APCU_VERSION 5.1.21
RUN pecl install apcu-${APCU_VERSION} && docker-php-ext-enable apcu

#Set Timezone Europe/Rome
RUN echo "date.timezone=Europe/Rome" > /usr/local/etc/php/php.ini
RUN php -m
RUN php -i

#Clean package
RUN apt clean && apt autoremove -y && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/src

#COPY ./vhost.conf /etc/apache2/conf-enabled/vhost.conf

RUN a2enmod rewrite
RUN a2enmod remoteip
RUN a2enmod env


WORKDIR /var/www/html
COPY --from=build --chown=www-data /home/wwwroot/voltwatcher /var/www/html
#RUN chown -R www-data:www-data /var/www/html

ENV MYSQL_HOST=mysqlhost
ENV MYSQL_PORT=3306
ENV APP_ENV=prod

COPY --from=build /home/wwwroot/voltwatcher/.docker/onlyapp/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY --from=build /home/wwwroot/voltwatcher/.docker/onlyapp/apache/start-apache /usr/local/bin/
COPY --from=build /home/wwwroot/voltwatcher/.docker/docker-entrypoint.d /docker-entrypoint.d
COPY --from=build /home/wwwroot/voltwatcher/.docker/docker-entrypoint.sh /docker-entrypoint.sh

RUN chmod +x /docker-entrypoint.sh

RUN ln -sf /dev/stdout /var/www/html/var/log/access.log && \
ln -sf /dev/stderr /var/www/html/var/log/error.log && \
ln -sf /dev/stdout /var/log/apache2/access.log && \
ln -sf /dev/stderr /var/log/apache2/error.log && \
rm -rf /var/www/html/.docker && \
apachectl configtest && \
touch .env

# https://docs.docker.com/develop/develop-images/dockerfile_best-practices/#entrypoint
ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["/usr/sbin/apache2ctl", "-DFOREGROUND"]
