<?php
$config['paypal'] = [

    # Define your application mode here
    'mode' => 'sandbox',

    # Account credentials from developer portal
    'account' => [
        'client_id' => 'AZZI7nBmRypZF4H7ajNM4G8JnbxGTor8OpxdwXmKUWh2R-zfKfYAX9Us1idmQ_6-zltNgcIeZCZWVakG',
        'client_secret' => 'EJ4gqjUBn0B8xf1SxlK5z4aFsxjOTaZ20AFn6BpVrX52BP1WYd-S7z0oqnYjFBHnEBYpNsKAdE4TWD_D',
    ],

    # Connection Information
    'http' => [
        'connection_time_out' => 30,
        'retry' => 1,
    ],

    # Logging Information
    'log' => [
        'log_enabled' => true,

        # When using a relative path, the log file is created
        # relative to the .php file that is the entry point
        # for this request. You can also provide an absolute
        # path here
        'file_name' => '../PayPal.log',

        # Logging level can be one of FINE, INFO, WARN or ERROR
        # Logging is most verbose in the 'FINE' level and
        # decreases as you proceed towards ERROR
        'log_level' => 'FINE',
    ],
];
