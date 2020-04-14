<?php
return [
    //-------------------------------
    // Timezone for insert dates in database
    // If you want Gateway not set timezone, just leave it empty
    //--------------------------------
    'timezone' => 'Asia/Tehran',
    //--------------------------------
    // Zarinpal gateway
    //--------------------------------
    'zarinpal' => [
        'merchant-id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
        'type' => 'zarin-gate',             // Types: [zarin-gate || normal]
        'callback-url' => '/',
        'server' => 'germany',                // Servers: [germany || iran || test]
        'email' => 'email@gmail.com',
        'mobile' => '09xxxxxxxxx',
        'description' => 'description',
    ],
    //--------------------------------
    // Mellat gateway
    //--------------------------------
    'mellat' => [
        'username' => 'aoaci',
        'password' => 'aoaci',
        'terminalId' => 969074,
        'callback-url' => env('APP_URL')."/callback"
    ],
    //--------------------------------
    // Saman gateway
    //--------------------------------
    'saman' => [
        'merchant' => '3013',
        'password' => '9542264',
        'callback-url' => env('APP_URL')."/callback/Saman"
    ],
    //--------------------------------
    // PayIr gateway
    //--------------------------------
    'payir' => [
        'api' => 'xxxxxxxxxxxxxxxxxxxx',
        'callback-url' => '/'
    ],
    //--------------------------------
    // Sadad gateway
    //--------------------------------
    'sadad' => [
        'merchant' => '000000140329876',
        'transactionKey' => 'RnszP6AYaaZCF4PoGnsCTQAOhAmMdYWZ',
        'terminalId' => '24042986',
        'callback-url' => '/callback/sadad'
    ],

    //--------------------------------
    // Parsian gateway
    //--------------------------------
    'parsian' => [
        'pin' => 'xxxxxxxxxxxxxxxxxxxx',
        'callback-url' => '/'
    ],
    //--------------------------------
    // Pasargad gateway
    //--------------------------------
    'pasargad' => [
        'terminalId' => 000000,
        'merchantId' => 000000,
        'certificate-path' => storage_path('gateway/pasargad/certificate.xml'),
        'callback-url' => '/gateway/callback/pasargad'
    ],
    //--------------------------------
    // Asan Pardakht gateway
    //--------------------------------
    'asanpardakht' => [
        'merchantId' => '',
        'merchantConfigId' => '',
        'username' => '',
        'password' => '',
        'key' => '',
        'iv' => '',
        'callback-url' => '/',
    ],
    //--------------------------------
    // Paypal gateway
    //--------------------------------
    'paypal' => [
        // Default product name that appear on paypal payment items
        'default_product_name' => 'My Product',
        'default_shipment_price' => 0,
        // set your paypal credential
        'client_id' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'secret' => 'xxxxxxxxxx_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'settings' => [
            'mode' => 'sandbox', //'sandbox' or 'live'
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal.log',
            /**
             * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
             *
             * Logging is most verbose in the 'FINE' level and decreases as you
             * proceed towards ERROR
             */
            'call_back_url' => '/gateway/callback/paypal',
            'log.LogLevel' => 'FINE'
        ]
    ],
    //-------------------------------
    // Tables names
    //--------------------------------
    'table' => 'gateway_transactions',
];
