FROM richarvey/nginx-php-fpm:php5

RUN apk update && apk add php5-sockets memcached

WORKDIR /tmp
# Run build process on one line to avoid generating bloat via intermediate images

# Add php5-memcached
RUN apk --no-cache add ca-certificates && \
curl -Ls -o /etc/apk/keys/sgerrand.rsa.pub https://raw.githubusercontent.com/sgerrand/alpine-pkg-php5-memcached/master/sgerrand.rsa.pub && \
curl -Ls -o php5-memcached-2.2.0-r0.apk https://github.com/sgerrand/alpine-pkg-php5-memcached/releases/download/2.2.0-r0/php5-memcached-2.2.0-r0.apk && \
apk add php5-memcached-2.2.0-r0.apk 

ADD docker/phalcon.so /usr/lib/php5/modules/phalcon.so
RUN /bin/echo 'extension=phalcon.so' > /etc/php5/conf.d/phalcon.ini

WORKDIR /var/www/html

COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-scripts --no-dev --no-autoloader && rm -rf /root/.composer

ADD docker/supervisord.conf /tmp/supervisord.conf
RUN cat /tmp/supervisord.conf >> /etc/supervisord.conf
ADD docker/nginx.conf /etc/nginx/sites-enabled/default.conf
ADD docker/start_chat.sh /srv/start_chat.sh

ADD . /var/www/html

RUN chmod +x /srv/start_chat.sh

# Finish composer
RUN composer dump-autoload  --optimize && cat vendor/autoload.php

# Generate minified assets (for the website)
WORKDIR /var/www/html/public
RUN php assets.php

ENV WEBROOT=/var/www/html/public

# ADD docker/run.sh /run.sh
# RUN chmod +x /run.sh

EXPOSE 8080
EXPOSE 8081