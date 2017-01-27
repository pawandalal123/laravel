<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'       => false, // set false to total remove
            'description' => false, //'A Platform where you can buy/book and sell tickets for events online.
        // Explore latest upcoming events near by you. Register Now!', // set false to total remove
            'separator'   => ' - ',
            'keywords'    => [],
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null
        ]
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => false, // set false to total remove
            'description' => false, // set false to total remove
            'url'         => false,
            'type'        => false,
            'site_name'   => false,
            'images'      => [],
        ]
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
          //'card'        => 'summary',
          //'site'        => '@LuizVinicius73',
        ]
    ]
];
