FROM ubuntu:20.10

RUN apt-get update && \
    apt-get install -y locales

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apt-get update && \
    apt-get install -y curl zip unzip software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y php7.4-cli php7.4-common php7.4-curl php7.4-fpm php7.4-mbstring php7.4-pgsql php7.4-xml php-xdebug && \
    apt-get remove -y --purge software-properties-common && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN mkdir /run/php

ADD php-fpm.conf /etc/php/7.4/fpm/php-fpm.conf
ADD www.conf /etc/php/7.4/fpm/pool.d/www.conf

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --version=2.0.8 \
        --filename=composer \
        --install-dir=/usr/local/bin

RUN chmod 755 /usr/local/bin/composer

RUN composer clear-cache

WORKDIR /var/www/wheee/

EXPOSE 9000
CMD ["php-fpm7.4"]