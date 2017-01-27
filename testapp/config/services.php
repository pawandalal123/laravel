<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */
	

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => 'hIyn1FsHVFyYH1BLL8nGow',
    ],

    'ses' => [
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],



    'stripe' => [
    // 'model'  => 'User',
    'model'  => App\User::class,
    'secret' => env('STRIPE_API_SECRET'),
],
    'facebook' => [
        'client_id'     => env('FB_CLIENT_ID'),
        'client_secret' => env('FB_SECRET_KEY'),
        'redirect'      => env('FB_RETURN_URL'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_SECRET_KEY'),
        'redirect'      => env('GOOGLE_RETURN_URL'),
    ],
	
	'twitter' => [
       'client_id' => env('TWITTER_CLIENT_ID'),
       'client_secret' => env('TWITTER_CLIENT_SECRET'),
       'redirect' => 'http://dev.goeventz.com/sociallogin/callback/twitter',
	],

];
