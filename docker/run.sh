#!/bin/sh

cd ${WEBROOT}

php assets.php

# Start supervisord and services
bash /start.sh