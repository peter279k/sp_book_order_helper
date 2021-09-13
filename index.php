<?php

use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

function SPBookOrderHelper(ServerRequestInterface $request): string
{
    $requestBody = (string)$request->getBody();
    $parsedJson = json_decode($requestBody, true);
    if (gettype($parsedJson) !== 'array') {
        return json_encode(['error' => 'You have wrong request body!']);
    }

    $name = $parsedJson['name'] ?? '';
    $contactMobile = $parsedJson['mobile_phone'] ?? '';
    $contactAddress = $parsedJson['contact_address'] ?? '';
    $contactEmail = $parsedJson['contact_email'] ?? '';
    $emailSkip = false;
    $bankNumber = $parsedJson['bank_number'] ?? '';
    $bankAccountNumber = $parsedJson['bank_account_number'] ?? '';

 
    $client = new Client(['cookies' => true]);

    $nonMemberSellBookUrl = 'https://www.spbook.com.tw/bookrecycle.php?step=1&v1=3';
    $response = $client->request('GET', $nonMemberSellBookUrl);

    $responseString = (string)$response->getBody();

    $crawler = new Crawler($responseString);
    $hiddenCsString = '';
    $crawler->filter('input[name="cs"]')->reduce(function(Crawler $node, $i) {
        global $hiddenCsString;
        $hiddenCsString = $node->attr('value');
    });

    $sellBookNumberUrl = 'https://www.spbook.com.tw/bookrecycle.php?step=2&v1=3';
    $formParams = [
        'form_params' => [
            'cs' => $hiddenCsString,
            'qty' => '1',
            'delivery' => '12',
        ],
    ];
    $response = $client->request('POST', $sellBookNumberUrl, $formParams);
    $responseStr = (string)$response->getBody();

    preg_match('/(headers).*/', $responseStr, $matched);
    $headerJSJson = $matched[0] ?? '';
    $headerJSJson = str_replace('"n', 'o', $headerJSJson);
    $headerJSJson = str_replace('\'', '"', $headerJSJson);

    $encodedJsonStr = substr($headerJSJson, 8, -2);
    $decodedJson = json_decode($encodedJsonStr, true);
    $csrfToken = $decodedJson['X-Csrf-Token'];


    // Fill the sell book form
    $bookFormUrl = 'https://www.spbook.com.tw/spbook_helloworld.php';

    $jsonArray = [
        'action' => 302,
        'data' => [
            'v1' => '3',
            'v2' => '',
            'bst' => '',
            'delivery_charge_refund' => 0,
            'delivery_type' => 'ｉ郵箱自費',
            'contact_name' => $name,
            'contact_mobile' => $contactMobile,
            'contact_address' => $contactAddress,
            'contact_email' => $contactEmail,
            'transfer_type' => '1',
            'bank_number' => $bankNumber,
            'bank_account_number' => $bankAccountNumber,
            'lattice_size' => '',
            'email_skip' => $emailSkip,
        ],
    ];

    $requestForm = [
        'body' => urlencode(json_encode($jsonArray)),
        'headers' => [
            'X-Csrf-Token' => $csrfToken,
            'Referer' => $sellBookNumberUrl,
        ],
    ];
    $response = $client->request('POST', $bookFormUrl, $requestForm);

    // Store order to Firebase Realtime Database
    $firebaseIdToken = getenv('firebase_id_token');
    $firebaseHost = getenv('firebase_host');
    $firebaseEndPoint = sprintf($firebaseHost . '/spbook_order_number.json?auth=%s', $firebaseIdToken);
    $responseBody = (string)$response->getBody();
    $responseJson = json_decode($responseBody, true);
    if ($responseJson !== null && $responseJson !== '' && $responseJson['status'] === 'success') {
        $client = new Client();
        $orderLists = ['order_numbers' => []];
        $responseOrderJson = (string)$client->request('GET', $firebaseEndPoint)->getBody();
        if ($responseOrderJson !== 'null') {
            $orderLists['order_numbers'] = array_merge(json_decode($responseOrderJson, true)['order_numbers'], [$responseJson['data']['order']]);
        } else {
            $orderLists['order_numbers'][] = $responseJson['data']['order'];
        }
        $requestOrderJson = [
            'json' => $orderLists,
        ];
        $response = $client->request('PATCH', $firebaseEndPoint, $requestOrderJson);
    }
    return $responseBody;
}
