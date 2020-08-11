<?php

return [
    'app_version' => [
        'current' => 2,
        'min' => 1
    ],
    'order_types' => [
        'BUY' => 'b',
        'SELL' => 's'
    ],
    'order' => [
        'create' => [
            'except' => [
                '1' => [
                    'option_type',
                    'strike_price',
                    'expiry_date'
                ],
                '2' => [
                    'option_type',
                    'strike_price'
                ]
            ]
        ]
    ]
];