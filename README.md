# Sp Book Order Helper

# Introduction

The SPBook selling order helper service and integrating Firebase Realtime Database and Google Cloud Functions.

# Prerequisites

- `gcloud` command is available
- Firebase authentication has been set with e-mail auth anonymously
- Firebase Realtimedatabase is available

# Usage

- Using `git clone https://gitlab.com/peter279k/sp_book_order_helper.git` to clone this repository.
- Set the `settings.txt`. This file contents should be:

```
email=your-email
password=your-password
token=your-firebase-token
api_key=firebase_project_api_key
firebase_host=your-firebase-host
```

- If token is expired, using following command to get new token:

```
./request_token.sh
```

# Google Cloud Function Deployment

- Run `./deploy.sh`

# References

- https://cloud.google.com/sdk/docs/downloads-snap
- https://cloud.google.com/functions/docs/configuring/env-var
- https://cloud.google.com/functions/docs/first-php
- https://console.firebase.google.com
- https://firebase.google.com/docs/reference/rest/auth
- https://firebase.google.com/docs/firestore/use-rest-api
- https://firebase.google.com/docs/rules/rules-and-auth
- https://www.spbook.com.tw/bookrecycle.php?step=1&v1=3
- https://www.spbook.com.tw/bookrecycle.php?step=2&v1=3
- https://www.spbook.com.tw/spbook_helloworld.php


## Enjoy it :)!
