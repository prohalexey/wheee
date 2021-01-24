# Generating certificates
 - Remove old certificates
   
    `
    rm -Rf .docker/nginx/certs/wheee.app.*
    `
 - Generate new certificates
   
    `
    docker-compose run --rm nginx sh -c "cd /etc/nginx/certs && touch openssl.cnf && cat /etc/ssl/openssl.cnf > openssl.cnf && echo \"\" >> openssl.cnf && echo \"[ SAN ]\" >> openssl.cnf && echo \"subjectAltName=DNS.1:wheee.app,DNS.2:*.wheee.app\" >> openssl.cnf && openssl req -x509 -sha256 -nodes -newkey rsa:4096 -keyout wheee.app.key -out wheee.app.crt -days 3650 -subj \"/CN=*.wheee.app\" -config openssl.cnf -extensions SAN && rm openssl.cnf"
    `

# Installing certs

 - ### Linux

    `
    sudo ln -s "$(pwd)/.docker/nginx/certs/wheee.app.crt" /usr/local/share/ca-certificates/wheee.app.crt
    sudo update-ca-certificates
    `

 - ### Windows

    Just import `docker/nginx/certs/wheee.app.crt` certificate into the trusted root CA in the browser and reload the browser