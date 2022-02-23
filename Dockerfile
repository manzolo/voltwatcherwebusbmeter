ARG MYSQL_HOST
ARG MYSQL_PORT

FROM registry.gitlab.manzolo.it/manzolo/php8.1-apache-full:latest as build

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
    rm -rf .env  && \
    rm -rf .env.dist


FROM registry.gitlab.manzolo.it/manzolo/php8.1-apache-lite:latest

WORKDIR /var/www/html
COPY --from=build /home/wwwroot/voltwatcher /var/www/html
RUN chown -R www-data:www-data /var/www/html

ENV MYSQL_HOST=mysqlhost
ENV MYSQL_PORT=3306
ENV APP_ENV=prod

COPY --from=build /home/wwwroot/voltwatcher/.docker/onlyapp/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY --from=build /home/wwwroot/voltwatcher/.docker/onlyapp/apache/start-apache /usr/local/bin/

RUN chmod +x /usr/local/bin/start-apache && \
rm -rf /var/log/apache2/access.log && touch /var/log/apache2/access.log && \
rm -rf /var/log/apache2/error.log && touch /var/log/apache2/error.log && \
apachectl configtest && \
touch .env

CMD ["/usr/local/bin/start-apache"]
