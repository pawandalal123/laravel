<?php
return array(
    // set your paypal credential
    'client_id' => 'AU85CAeEjsqWzMtdrlW57pxCS-hApBD3jEo4uPJeysD68VcrXx9tI4ljGANnOueBkDc1j_-97ZzfDt6L',
    'secret' => 'EADeGFsLCnwE_tC4GQGF4g8vbAxYGZDfVnxcPCyXStbWTNYZHJO_0Y5MEtWewsa1ILZ_-G-kzq9IJ2Bl',

    // 'client_id' => 'ATVA-sINDdmvcceOtG3ERb4Tg3Qr7xi7ipTSX2LaGvYN_Y-tige4kOZkuBh_jynTG_KRALs6RCBfuadd',//'AeGusX4bLwR-Hhc1DyJki-tm_Ul1UMJulRdUz1rcDed_E3ArrCPrxhiEBfyrYlZevEh-SsWRD56LMGW2',
    // 'secret' => 'EAEalYVfqS0eRDCXFfVnWcQbyuLGwvUuO5pEn1PBlShSAqq6jDTS6PofHVhRWQG44c1--GQ5cYt9gQqZ',//'ELkFrIsSSYAkzhqMk1yijE2RgTbhXmnh7YZRc-0d0Lp5BIcEDA5HEvYpgDiGDYVb3wuN15Z8UtzfiS9F',
    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'live',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
