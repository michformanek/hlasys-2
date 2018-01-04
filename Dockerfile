FROM php:7.1-apache

# install system dependencies for composer, etc.
RUN apt-get update \
        && apt-get install -y zip git \
        && rm -r /var/lib/apt/lists/*

# install PHP extensions (PDO mysql)
RUN docker-php-ext-install pdo pdo_mysql

# install PHP Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
        && php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet

# enable mod_rewrite
RUN a2enmod rewrite

# apache configuration
COPY apache-virtual-host.conf /etc/apache2/sites-available/000-default.conf

COPY composer.json /app/

WORKDIR /app

RUN composer install

COPY . /app

RUN chmod a+rwx log temp -R && ls -al &&\
    sed -i "s/DocumentRoot \/var\/www\/html/DocumentRoot \/app\/www/g" /etc/apache2/sites-available/000-default.conf
