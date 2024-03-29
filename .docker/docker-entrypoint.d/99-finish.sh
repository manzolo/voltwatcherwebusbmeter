#!/bin/bash

echo "+----------------------------------------------------------+"
echo "|   Setting Apache Server Name to '${APACHE_SERVER_NAME:-localhost}'"
echo "+----------------------------------------------------------+"
echo

#sed -ri -e "s!^#(ServerName)\s+\S+!\1 ${APACHE_SERVER_NAME:-localhost}:80!g" /etc/apache2/apache2.conf
echo "ServerName ${APACHE_SERVER_NAME:-localhost}" >> /etc/apache2/apache2.conf

sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-enabled/*

rm -rf /var/www/html/var/log/prod
/var/www/html/bin/console cache:clear --env=prod

chmod -R 777 /var/www/html/var/cache
chmod -R 777 /var/www/html/var/log

#/var/www/html/bin/console --no-interaction doctrine:migrations:migrate
#/var/www/html/bin/console --no-interaction --allow-empty-diff doctrine:migrations:diff || echo "To show difference with your database execute:\nbin/console doctrine:migrations:diff\nTo apply changes execute:\nbin/console doctrine:migrations:migrate"
echo
/var/www/html/bin/console --no-interaction doctrine:migrations:status
echo
#echo "To apply database migrations execute:"
#echo "bin/console --no-interaction doctrine:migrations:migrate"
#echo
/var/www/html/bin/console --no-interaction doctrine:migrations:migrate

# Remove temp files
echo "+--------------------------------+"
echo "|   Removing not used files...   |"
echo "+--------------------------------+"
echo

echo "+----------------------------------------------------------+"
echo "|                 OK, prepare finshed!                     |"
echo "|                                                          |"
echo "|      Starting Voltwatcher Management Docker...           |"
echo "+----------------------------------------------------------+"
echo
