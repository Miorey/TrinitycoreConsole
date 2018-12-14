FROM composer:1.8
EXPOSE 8000
WORKDIR /var/symfony4
COPY . .

#RUN  apt-get update && apt install -y php7.2-soap
RUN apk add --no-cache libxml2-dev  openrc apache2 php7-apache2 && docker-php-ext-install xml soap pcntl

#fix an apache2 bug on alpine
#RUN composer install --no-devl --ignore-platform-reqs --no-scripts
RUN composer install --no-dev --optimize-autoloader
RUN mkdir /run/apache2
RUN mkdir /run/openrc
RUN touch /run/openrc/softlevel

RUN mv .env.prod .env

RUN rm -rf /var/www/localhost/htdocs
RUN ln -s /var/symfony4/public/ /var/www/localhost/htdocs

#RUN composer require symfony/web-server-bundle
#CMD ["php", "./bin/console server:run 0.0.0.0:8000 --env=prod"]

# start http server
CMD ["/usr/sbin/httpd", "-k", "start", "-X"]
#CMD ["rc-service", "apache2", "start"]