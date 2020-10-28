ARG app_env=prod
ARG database_url=mysql://root:manzolo@172.17.0.1:3306/voltwatcher
ARG default_locale=it
ARG mailer_dsn=smtp://localhost

# Dockerfile
FROM php:7.4-cli as build

ARG app_env
ARG database_url
ARG default_locale
ARG mailer_dsn

ENV APP_ENV=$app_env
ENV MAILER_DSN=$mailer_dsn
ENV DATABASE_URL=$database_url
ENV locale=$default_locale

RUN apt-get update -y && apt-get install -y libmcrypt-dev libonig-dev zlib1g-dev libpng-dev libzip-dev libpq-dev git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip

WORKDIR /app
COPY . /app

RUN rm -rf /app/var/cache/dev && \
rm -rf /app/var/cache/prod && \
rm -rf /app/.git && \
rm -rf .env.local

RUN composer install

FROM php:7.4-cli

ENV APP_ENV=$app_env
ENV DATABASE_URL=$database_url
ENV locale=$default_locale
ENV MAILER_DSN=$mailer_dsn

RUN apt-get update -y && apt-get install -y libmcrypt-dev libonig-dev zlib1g-dev libpng-dev libzip-dev
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip

WORKDIR /app
COPY --from=build /app /app
COPY --from=build /app/docker/nodb/init.sh /usr/local/bin/init.sh

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN chmod +x /usr/local/bin/init.sh
 
EXPOSE 8001
CMD ["/usr/local/bin/init.sh"]

#docker build -t voltwatcher .
#docker run -it -p 8010:8010 voltwatcher
