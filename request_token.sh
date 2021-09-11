#!/bin/bash

if [[ ! -f "./settings.txt" ]]; then
    echo "./settings.txt file is not existed. Please create it."
    exit 1;
fi;

email=$(cat settings.txt | grep "^email" | awk '{split($1,a,"="); print a[2]}')
password=$(cat settings.txt | grep "^password" | awk '{split($1,a,"="); print a[2]}')
api_key=$(cat settings.txt | grep "^api_key" | awk '{split($1,a,"="); print a[2]}')

if [[ $email == "" ]]; then
    echo "Cannot find email value on ./settings.txt file."
    exit 1;
fi;

if [[ $password == "" ]]; then
    echo "Cannot find password value on ./settings.txt file."
    exit 1;
fi;

if [[ $api_key == "" ]]; then
    echo "Cannot find api_key value on ./settings.txt file."
    exit 1;
fi;


curl --silent -X POST "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=$api_key" --data-binary "{\"email\":"\"$email\"",\"password\":"\"$password\"",\"returnSecureToken\":true}"
