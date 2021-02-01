## How to install:

1. Clone the repo
   
   `git clone https://github.com/prohalexey/wheee.git`
2. Run containers
   
    `cd wheee && docker-compose up -d`
3. Install composer libraries 
   
    `docker exec -it wheee-app composer install`
4. Add host `127.0.0.1 wheee.app` into
   
   Linux - `/etc/hosts`
   
   Windows - `C:/Windows/System32/drivers/etc/hosts`
5. Init application
    
    `docker exec -it wheee-app php ./init`
6. Put into common/config/main-local.php

    ```
       'db' => [
           'class' => 'yii\db\Connection',
           'dsn' => 'pgsql:host=db;dbname=wheee',
           'username' => 'wheee',
           'password' => 'wheeepwd',
           'charset' => 'utf8',
       ],
    ```
   
7. Fill database with seeds via
    
    `docker exec -it wheee-app php yii seed/index`

8. Run the command `docker exec -it wheee-app php yii materialization/index` to refresh materialized View

9. Open `wheee.app` website in the browser

10. Put `php yii materialization/index` to the crontab and run each 10 minutes

    `10 * * * *`


## Generating certificates
 - Remove old certificates
   
    `
    rm -Rf .docker/nginx/certs/wheee.app.*
    `
 - Generate new certificates
   
    `
    docker-compose run --rm nginx sh -c "cd /etc/nginx/certs && touch openssl.cnf && cat /etc/ssl/openssl.cnf > openssl.cnf && echo \"\" >> openssl.cnf && echo \"[ SAN ]\" >> openssl.cnf && echo \"subjectAltName=DNS.1:wheee.app,DNS.2:*.wheee.app\" >> openssl.cnf && openssl req -x509 -sha256 -nodes -newkey rsa:4096 -keyout wheee.app.key -out wheee.app.crt -days 3650 -subj \"/CN=*.wheee.app\" -config openssl.cnf -extensions SAN && rm openssl.cnf"
    `

## Installing certs

 - ### Linux

    `
    sudo ln -s "$(pwd)/.docker/nginx/certs/wheee.app.crt" /usr/local/share/ca-certificates/wheee.app.crt
    sudo update-ca-certificates
    `

 - ### Windows

    Just import `docker/nginx/certs/wheee.app.crt` certificate into the trusted root CA in the browser and reload the browser
