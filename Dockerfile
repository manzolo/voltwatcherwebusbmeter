ARG MYSQL_HOST
ARG MYSQL_PORT

FROM registry.gitlab.manzolo.it/manzolo/php8.1-apache-full as build

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




FROM registry.gitlab.manzolo.it/manzolo/php8.1-apache-lite

WORKDIR /var/www/html
COPY --from=build /home/wwwroot/voltwatcher /var/www/html
RUN chown -R www-data:www-data /var/www/html

ENV MYSQL_HOST=mysqlhost
ENV MYSQL_PORT=3306
ENV APP_ENV=prod

COPY --from=build /home/wwwroot/voltwatcher/.docker/onlyapp/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY --from=build /home/wwwroot/voltwatcher/.docker/onlyapp/apache/start-apache /usr/local/bin/
RUN chmod +x /usr/local/bin/start-apache
RUN rm -rf /etc/apache2/conf-enabled/vhost.conf
RUN rm -rf /var/log/apache2/access.log && touch /var/log/apache2/access.log
RUN rm -rf /var/log/apache2/error.log && /var/log/apache2/error.log
RUN apachectl configtest

RUN rm -rf /var/www/html/.env
RUN rm -rf /var/www/html/.env.docker.local
RUN rm -rf /var/www/html/.env.dist
RUN rm -rf /var/www/html/.env.local
RUN touch .env

EXPOSE 80

CMD ["/usr/local/bin/start-apache"]
