<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MQTT Broker Connection
    |--------------------------------------------------------------------------
    */

    'host'      => env('MQTT_HOST', '127.0.0.1'),
    'port'      => (int) env('MQTT_PORT', 1883),
    'username'  => env('MQTT_USERNAME', ''),
    'password'  => env('MQTT_PASSWORD', ''),
    'client_id' => env('MQTT_CLIENT_ID', 'laravel-jamur-iot'),

    /*
    |--------------------------------------------------------------------------
    | TLS / SSL
    |--------------------------------------------------------------------------
    | Set to true when using HiveMQ Cloud or any broker with TLS (port 8883)
    */

    'tls_enabled' => env('MQTT_TLS_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Topics
    |--------------------------------------------------------------------------
    */

    'topics' => [
        'subscribe_status' => 'jamur/device/+/status',
        'publish_config'   => 'jamur/device/{device_id}/config',
    ],

];
