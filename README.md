# Batteries volt watcher

## Homepage
![img](doc/images/homepage.jpg)

## Log detail
![img](doc/images/logdetail.jpg)


## Send record to webserver
    
    #Login to get token
    curl --request POST \
      --url https://webserver/api/login_check \
      --header 'Content-Type: application/json' \
      --data '{"username":"admin", "password":"userpassword"}'

    #Send data to webserver
    curl --request PUT \
      --url https://webserver/api/volt/record.json \
      --header 'Authorization: Bearer HERE_TOKEN_FROM_LOGIN' \
      --header 'Content-Type: application/json' \
      --data '{"device":"XX:YY:ZZ:99:88:77","data":"2021-10-02 14:29:00","volt":"12.56","temp":"18.3","batteryperc":"100","longitude":"11.333","latitude":"43.555"}'

## Hardware suggested
Usb bluetooth volt meter like https://sigrok.org/wiki/RDTech_UM_series

## Software suggested
- Android App https://github.com/manzolo/bluetooth-watcher/releases
- apache HTTP server 
- Composer (https://getcomposer.org/) 
- Symfony cli (https://symfony.com/download only for testing) 
- git
### Standalone
Php modules

- php7.*-xml  
- php7.*-intl  
- php7.*-mbstring  
- php7.*-sqlite3 | php7.*-pgsql | php7.*-mysql 
- php7.*-zip 
- php7.*-gd 
- php7.*-curl 
- php7.*-bz2 

#### Installation
    git clone https://github.com/manzolo/voltwatcherwebusbmeter.git
    cd voltwatcherwebusbmeter

#### Create jwt certificates
    $ mkdir -p config/jwt
    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

Put password in JWT_PASSPHRASE env (see below)

#### Configure environments (creating .env.local file)
	APP_ENV=prod
	#http://nux.net/secret
	APP_SECRET=yoursecretkeybyhttp://nux.net/secret
	DATABASE_URL="sqlite:///%kernel.project_dir%/var/cache/database.sqlite"
	#https://openweathermap.org/api/one-call-api
	OPENWEATHERMAP_APIKEY=""
	COMPOSER_HOME=/tmp
	MAILER_DNS=smtp://username:password@smtp.host.com:25
	MAILER_USER=admin@email.com
	LOCALE=en
	###> lexik/jwt-authentication-bundle ###
	JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
	JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
	JWT_PASSPHRASE=jwtpassword
	###< lexik/jwt-authentication-bundle ###

#### Continue installation
    composer install

#### Create database
    bin/console bicorebundle:install adminusername adminpassword admin@email.com
    bin/console voltwatcher:install
    
#### Test on local server
    symfony server:start --no-tls
Navigate to
    http://localhost:8000
    
    
### Docker
#### First time

    # From bash
    docker pull manzolo/voltwatcher_app
    wget https://raw.githubusercontent.com/manzolo/voltwatcherwebusbmeter/master/docker-compose.yml

    mkdir -p config/jwt

    # Api Password certificate (set password in JWT_PASSPHRASE .env entry)
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

    # Create .env file
    APP_ENV=prod
    # http://nux.net/secret

    APP_SECRET=yoursecretkeybyhttp://nux.net/secret

    # DATABASE INFORMATION
    MYSQL_DATABASE=voltwatcher
    MYSQL_USER=voltwatcher
    MYSQL_PASSWORD=voltwatcherpassword
    MYSQL_ROOT_PASSWORD=mysqlrootpasswordsecret

    # WEB SERVER LISTEN PORT
    APACHE_PORT=8001
    # PHPMYADMIN LISTEN PORT
    PHPMYADMIN_PORT=8002

    # https://openweathermap.org/api/one-call-api
    OPENWEATHERMAP_APIKEY=""

    MAILER_DNS=smtp://username:password@smtp.host.com:25
    MAILER_USER=admin@email.com
    LOCALE=en
    # Api Password certificate
    JWT_PASSPHRASE=jwtpassword

    # Start containers
    docker-compose up --no-build -d

    # Inside container
    docker exec -it voltwatcher_app /bin/bash
        
    bin/console bicorebundle:install adminuser adminpassword admin@email.com
    bin/console voltwatcher:install

    