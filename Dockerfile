FROM composer:1.8
COPY . /var/www/html/
WORKDIR /var/www/html/

#RUN  apt-get update && apt install -y php7.2-soap
RUN apk add --no-cache libxml2-dev && docker-php-ext-install xml soap pcntl
RUN composer install --ignore-platform-reqs --no-scripts
RUN mv .env.prod .env
RUN composer require symfony/web-server-bundle
RUN php ./bin/console server:run --env=prod