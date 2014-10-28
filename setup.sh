#!/bin/bash

echo "=> Installing all dependend packages..."

if [ ! -w /etc/hosts ] ; then
    echo '=> !!! Please run this script with root rights'
    exit
fi

if [ ! -r /etc/apache2 ] ; then
    echo '=> !!! This script is only compatible with apache, make sure you have apache2 installed'
    exit
fi

apt-add-repository -y ppa:phalcon/stable
apt-get update
apt-get install -y php5-dev libapache2-mod-php5
apt-get install -y php5-curl php5-gd memcached php5-memcached php5-memcache php5-mcrypt php5-mysql php5-phalcon

echo "=> All dependencies have been installed"
    
