ARG MYSQL_HOST
ARG MYSQL_PORT

FROM $CI_REGISTRY/$CI_PROJECT_NAMESPACE/php8.1-apache-full as build

WORKDIR /home/wwwroot/voltwatcher

COPY . .

RUN rm -rf .git && \
    rm -rf config/jwt && \
    rm -rf node_modules && \
    rm -rf var && \
    rm -rf .env.local && \
    rm -rf .env && \
    rm -rf vendor && \
    cp .env.dist .env && \
    mkdir var && \ 
    chmod 777 -R var && \ 
    composer install --no-dev --optimize-autoloader

FROM $CI_REGISTRY/$CI_PROJECT_NAMESPACE/php8.1-apache-lite

WORKDIR /var/www/html
COPY --from=build /home/wwwroot/voltwatcher /var/www/html
RUN chown -R www-data:www-data /var/www/html

ENV MYSQL_HOST=mysqlhost
ENV MYSQL_PORT=3306
ENV APP_ENV=prod

RUN apachectl configtest
#RUN a2enmod rewrite
#RUN a2enmod remoteip

RUN rm -rf /var/www/html/.env
RUN rm -rf /var/www/html/.env.docker.local
RUN rm -rf /var/www/html/.env.dist
RUN rm -rf /var/www/html/.env.local
RUN touch .env

EXPOSE 80

CMD ["/usr/local/bin/start-apache"]
