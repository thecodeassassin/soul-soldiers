FROM richarvey/nginx-php-fpm:php5

ENV WEBROOT=/var/www/html/public

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
RUN composer install --no-scripts --no-dev --no-autoloader && rm -rf /root/.composer && mv /var/www/html/vendor /tmp

ADD docker/supervisord.conf /tmp/supervisord.conf
RUN cat /tmp/supervisord.conf >> /etc/supervisord.conf
ADD docker/nginx.conf /etc/nginx/sites-enabled/default.conf
ADD docker/run.sh /run.sh
ADD docker/start_chat.sh /srv/start_chat.sh

ADD . /var/www/html

RUN chmod +x /srv/start_chat.sh /run.sh

# Finish composer and generate minified assets (for the website)
RUN  mv /tmp/vendor /var/www/html && ls -lha /var/www/html/vendor/ && composer dump-autoload  --optimize && cd public/ && php assets.php

EXPOSE 8080
EXPOSE 8081

# CMD ["/run.sh"]