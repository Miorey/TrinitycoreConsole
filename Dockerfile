FROM ubuntu:bionic
EXPOSE 8000
WORKDIR /var/symfony4
COPY . .

#RUN  apt-get update && apt install -y php7.2-soap
ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get update && apt-get install -y \
                        apache2 \
                        apache2-bin \
                        apache2-data \
                        apache2-utils  \
                        libapache2-mod-php7.2 \
                        php7.2 \
                        php7.2-cli \
                        php7.2-common \
                        php7.2-curl \
                        php7.2-fpm \
                        php7.2-json \
                        php7.2-mbstring \
                        php7.2-opcache \
                        php7.2-readline \
                        php7.2-soap \
                        php7.2-xml \
                        composer


#fix an apache2 bug on alpine
#RUN composer install --no-devl --ignore-platform-reqs --no-scripts
RUN composer install --no-dev --optimize-autoloader  --no-scripts

RUN mv .env.prod .env
RUN ./bin/console cache:clear

RUN rm -rf /var/www/html/
RUN ln -s /var/symfony4/public/ /var/www/html


#RUN composer require symfony/web-server-bundle
#CMD ["php", "./bin/console server:run 0.0.0.0:8000 --env=prod"]

# start http server
#CMD ["/usr/sbin/httpd", "-k", "start", "-X"]
CMD /usr/sbin/apache2ctl -D FOREGROUND
#CMD ["rc-service", "apache2", "start"]