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
      --data '{"username":"adminuser", "password":"adminpassword"}'

    #Send data to webserver
    curl --request PUT \
      --url https://webserver/api/volt/record.json \
      --header 'Authorization: Bearer HERE_TOKEN_FROM_LOGIN' \
      --header 'Content-Type: application/json' \
      --data '{"device":"XX:YY:ZZ:99:88:77","data":"2021-10-02 14:29:00","volt":"12.56","temp":"18.3","batteryperc":"100","longitude":"11.333","latitude":"43.555"}'

### Docker container
#### First time

    # From bash
    docker pull manzolo/voltwatcher
    wget https://raw.githubusercontent.com/manzolo/voltwatcherwebusbmeter/master/docker-compose.yml

    mkdir -p config/jwt

    # Api Password certificate (set password in JWT_PASSPHRASE .env entry)
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

    # Create .env file
    APP_ENV=prod
    APP_SECRET=yoursecretkey by http://nux.net/secret

    # DATABASE INFORMATION
    DATABASE_URL=mysql://voltwatcher_user:voltwatcher_pass@voltwatcher_db_host:33060/voltwatcher_db_name


    # https://openweathermap.org/api/one-call-api
    OPENWEATHERMAP_APIKEY=""

    MAILER_DSN=smtp://username:password@smtp.host.com:25
    MAILER_USER=admin@email.com
    LOCALE=en
    
    # Api Password certificate
    JWT_PASSPHRASE=jwtpassword
    
    # host port
    HTTP_PORT=8080
    
    # Start container
    docker-compose up -d

    # Inside container
    docker exec -it voltwatcher /bin/bash
        
    bin/console bicorebundle:install adminuser adminpassword admin@email.com
    bin/console voltwatcher:install

#### How to start container
    # Start container (after removing build section from docker-compose.yml)
    docker-compose up -d


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

- php8.*-xml  
- php8.*-intl  
- php8.*-mbstring  
- php8.*-sqlite3 | php8.*-pgsql | php8.*-mysql 
- php8.*-zip 
- php8.*-gd 
- php8.*-curl 
- php8.*-bz2 

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
        
