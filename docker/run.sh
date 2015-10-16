#!/usr/bin/env bash

export DOCKER_HOST_IP=$(route -n | awk '/UG[ \t]/{print $2}')

echo "Docker host IP: $DOCKER_HOST_IP"

. /config/bootstrap.sh
