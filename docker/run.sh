#!/bin/sh

cd ${WEBROOT}/..

composer dump-autoload  --optimize

cd ${WEBROOT}

php assets.php

# Start supervisord and services
bash /start.sh