#!/bin/sh

if [[ "$INTRANET" == "true" ]]; then
    php /var/www/html/chat_server.php
fi