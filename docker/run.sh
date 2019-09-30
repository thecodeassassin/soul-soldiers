#!/bin/sh

cd ${WEBROOT}

php assets.php

# Start supervisord and services
exec /usr/bin/supervisord -n -c /etc/supervisord.conf