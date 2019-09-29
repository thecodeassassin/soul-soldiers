#!/bin/bash
branch=$1

echo "=> Branch: ${branch}"
env

# Authenticate with the Google Services
codeship_google authenticate

# Set the default zone to use
gcloud config set compute/zone europe-west1-a
