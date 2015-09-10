from centos:latest

RUN yum -y remove iputils

RUN yum -y install yum-plugin-priorities yum-plugin-fastestmirror

# install epel-release before installing nginx
RUN yum -y install epel-release

RUN yum -y update

RUN yum -y install nginx php-fpm php-devel php-mysql php-memcached php-curl php-sockets php-mcrypt php-gd php-pdo php-mbstring gcc libtool git make

# add the soul-soldiers
ADD docker/site-http /etc/nginx/conf.d/soul-soldiers.conf

#ADD docker/default-ssl /etc/apache2/sites-available/

WORKDIR /tmp
# Run build process on one line to avoid generating bloat via intermediate images
RUN /usr/bin/git clone git://github.com/phalcon/cphalcon.git && \
    cd cphalcon/ && \
    /usr/bin/git checkout phalcon-v1.3.4 && \
    cd build/ && \
    ./install && \
    cd /tmp && \
    /bin/rm -rf /tmp/cphalcon/

RUN /bin/echo 'extension=phalcon.so' > /etc/php.d/phalcon.ini

#RUN /usr/sbin/php5enmod phalcon

#RUN /usr/sbin/a2enmod rewrite

ADD . /var/www/soul-soldiers

EXPOSE 80
EXPOSE 443

CMD ["/usr/bin/nginx"]
