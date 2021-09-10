#!/bin/bash

if [[ ! -f "./settings.txt" ]]; then
    echo "./settings.txt file is not existed. Please create it."
    exit 1;
fi;

id_token=$(cat settings.txt | grep "^token" | awk '{split($1,a,"="); print a[2]}')
firebase_host=$(cat settings.txt | grep "^firebase_host" | awk '{split($1,a,"="); print a[2]}')

if [[ $id_token == "" ]]; then
    echo "Cannot find token value on ./settings.txt file."
    exit 1;
fi;

if [[ $firebase_host == "" ]]; then
    echo "Cannot find firebase_host value on ./settings.txt file."
    exit 1;
fi;


gcloud functions deploy SPBookOrderHelper --update-env-vars firebase_host="$firebase_host",firebase_id_token="$id_token" --runtime php74 --trigger-http --allow-unauthenticated
