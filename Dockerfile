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

FROM registry.gitlab.manzolo.it/manzolo/php8.1-apache-lite:latest

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
