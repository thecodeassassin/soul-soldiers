#!/bin/bash

echo "=> Branch: ${CI_BRANCH}"

if [[ "${CI_BRANCH}" == "develop" ]]; then
image="eu.gcr.io/soul-soldiers/legacy:${CI_BUILD_ID}"
else
image="eu.gcr.io/soul-soldiers/legacy:latest"
fi

echo "=> Image name: ${image}"

# Authenticate with the Google Services
codeship_google authenticate

# Set the default zone to use
gcloud config set compute/zone europe-west1-a
