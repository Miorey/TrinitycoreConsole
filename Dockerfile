FROM composer:1.8
EXPOSE 8000
COPY . /var/www/html/
WORKDIR /var/www/html/

#RUN  apt-get update && apt install -y php7.2-soap
RUN apk add --no-cache libxml2-dev  openrc apache2 && docker-php-ext-install xml soap pcntl

#fix an apache2 bug on alpine
RUN mkdir /run/apache2

RUN composer install --ignore-platform-reqs --no-scripts
RUN mv .env.prod .env
#RUN composer require symfony/web-server-bundle
#CMD ["php", "./bin/console server:run 0.0.0.0:8000 --env=prod"]

# start http server
CMD ["/usr/sbin/httpd", "-k", "start", "-X"]