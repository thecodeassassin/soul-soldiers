from centos:latest

RUN yum -y remove iputils

RUN yum -y install yum-plugin-priorities yum-plugin-fastestmirror
RUN yum -y update

RUN yum -y install httpd

RUN yum -y install php-devel php-mysql php-memcached php-curl php-sockets php-mcrypt php-gd php-pdo php-mbstring  gcc libtool git

ADD docker/default /etc/apache2/sites-available/
ADD docker/default-ssl /etc/apache2/sites-available/

WORKDIR /tmp
# Run build process on one line to avoid generating bloat via intermediate images
RUN /usr/bin/git clone git://github.com/phalcon/cphalcon.git && \
    cd cphalcon/build/ && \
    ./install && \
    cd /tmp && \
    /bin/rm -rf /tmp/cphalcon/ && \
    /usr/bin/apt-get -y purge git php5-dev libpcre3-dev gcc make && apt-get -y autoremove && apt-get clean
RUN /bin/echo 'extension=phalcon.so' >/etc/php5/mods-available/phalcon.ini
RUN /usr/sbin/php5enmod phalcon

RUN /usr/sbin/a2enmod rewrite

ADD . /var/www/phalcon

EXPOSE 80 
EXPOSE 443

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
