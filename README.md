# Batteries volt watcher

![img](doc/images/homepage.jpg)

## Hardware Prerequisites
Usb bluetooth volt meter like https://sigrok.org/wiki/RDTech_UM_series

## Software Prerequisites
- Android App https://github.com/manzolo/bluetooth-watcher/releases
- apache HTTP server 
- Composer (https://getcomposer.org/) 
- Symfony cli (https://symfony.com/download only for testing) 
- git

Php modules

- php7.*-xml  
- php7.*-intl  
- php7.*-mbstring  
- php7.*-sqlite3 | php7.*-pgsql | php7.*-mysql 
- php7.*-zip 
- php7.*-gd 
- php7.*-curl 
- php7.*-bz2 

## Installation
    git clone https://github.com/manzolo/voltwatcherwebusbmeter.git
    cd voltwatcherwebusbmeter

### Create jwt certificates
    $ mkdir -p config/jwt
    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

Put password in JWT_PASSPHRASE env (see below)

### Configure environments (creating .env.local file)
	APP_ENV=prod
	#http://nux.net/secret
	APP_SECRET=yoursecretkeybyhttp://nux.net/secret
	DATABASE_URL="sqlite:///%kernel.project_dir%/var/cache/database.sqlite"
	#https://openweathermap.org/api/one-call-api?gclid=CjwKCAjw_Y_8BRBiEiwA5MCBJiDy96IitiR_ct7RXRGTdu7RAC3evkv0VxJs3B6Zw2GQO42EwrzYMhoCepUQAvD_BwE
	openweathermap_apikey=""
	COMPOSER_HOME=/tmp
	MAILER_URL=smtp://username:password@smtp.host.com:25
	mailer_user=admin@email.com
	locale=en
	###> lexik/jwt-authentication-bundle ###
	JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
	JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
	JWT_PASSPHRASE=jwtpassword
	###< lexik/jwt-authentication-bundle ###

### Continue installation
    composer install

### Create database
    bin/console bicorebundle:install adminusername adminpassword admin@email.com
    
## Test on local server
    symfony server:start --no-tls
Navigate to
    http://localhost:8000
    
    
