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

- Requesting a selling book form order via this API service:
- I assume that the API service endpoint is `https://us-central1-webcrawler-325501.cloudfunctions.net/SPBookOrderHelper` after deploying this to Cloud Functions.

```
curl -X POST -H 'Content-Type: application/json' https://us-central1-webcrawler-325501.cloudfunctions.net/SPBookOrderHelper --data '{"name":"your-name","mobile_phone":"your-mobile-phone","contact_address":"contact-address","contact_email":"peter279k@gmail.com","bank_number":"your-bank-number","bank_account_number":"your-bank-account-number"}'
```

- Deleting a selling book form order with specific order number via this API service:

```

```

- The selling book form will be available on following URL:

```
https://www.spbook.com.tw/bookrecycle.php?step=3&v1=3&order={your-selling-book-order-number}&delivery=12
```

# Google Cloud Function Deployment

- Run `./local_deploy.sh`

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
- https://github.com/google-github-actions/deploy-cloud-functions
- https://cloud.google.com/vision/docs/ocr#vision_text_detection-drest
- https://cloud.google.com/vision/docs/setup

## Enjoy it :)!
