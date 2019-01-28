# build command : docker build . --tag miorey/trinityconsole -e TRINITY_USERNAME=admin -e TRINITY_PASSWORD=admin -e TRINITY_ADDRESS=arthas

FROM composer:1.8
EXPOSE 8000
WORKDIR /var/symfony4
COPY . .

RUN apk update
RUN apk add --no-cache libxml2-dev \
                        openrc \
                        apache2 \
                        curl \
                        php7-apache2 \
                        php7-ctype \
                        php7-json \
                        php7-soap \
                        php7-xml \
                        php7-pcntl \
                        php7-curl

RUN docker-php-ext-install \
        ctype \
        json \
        soap \
        xml \
        pcntl

#fix an apache2 bug on alpine
RUN composer install --no-dev --optimize-autoloader  --no-scripts
RUN mkdir /run/apache2
RUN mkdir /run/openrc
RUN touch /run/openrc/softlevel

RUN mv .env.prod .env
ENV TRINITY_USERNAME admin
ENV TRINITY_PASSWORD admin
ENV TRINITY_ADDRESS localhost
RUN ./bin/console cache:clear

RUN rm -rf /var/www/localhost/htdocs
RUN ln -s /var/symfony4/public/ /var/www/localhost/htdocs

# start http server
CMD ["/usr/sbin/httpd", "-k", "start", "-X"]