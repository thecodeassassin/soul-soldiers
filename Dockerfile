FROM eboraas/apache-php
MAINTAINER Stephen Hoogendijk <stephen@tca0.nl>

RUN /usr/sbin/a2enmod socache_shmcb || true

# install phalcon
RUN apt-get -y install php5-dev git libpcre3-dev gcc make && \
	git clone --branch phalcon-v2.0.5 --depth 1 https://github.com/phalcon/cphalcon.git && \
	cd cphalcon/build/64bits && \
	phpize && \
	./configure CFLAGS="-O2 -g" && \
	make -B && \
	make install && \
	apt-get -y purge git php5-dev git libpcre3-dev gcc make && \
	apt-get -y autoremove && \
	apt-get clean

RUN echo 'extension=phalcon.so' > /etc/php5/mods-available/phalcon.ini && \
	php5enmod phalcon

# Add the correct apache vhost files
ADD docker/default /etc/apache2/sites-enabled/000-default.conf
ADD docker/default-ssl /etc/apache2/sites-enabled/001-default-ssl.conf

# remove the symlinks as they are not needed
RUN rm -f /etc/apache2/sites-enabled/001-default-ssl /etc/apache2/sites-available/default-ssl /etc/apache2/sites-available/default

# install additional php extensions
RUN apt-get -y install curl php5-memcached php5-mcrypt php5-curl php5-gd php5-mysql php5-intl

# add the source code
ADD . /var/www/soul-soldiers

#install composer
RUN curl -sS https://getcomposer.org/installer | php

WORKDIR /var/www/soul-soldiers

RUN chown -R www-data:www-data .

# run composer
RUN php composer.phar self-update
RUN php composer.phar install

# set rwx rights to the cache and log folders
RUN chmod 777 app/cache
RUN chmod 777 app/log

EXPOSE 80
EXPOSE 443

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
