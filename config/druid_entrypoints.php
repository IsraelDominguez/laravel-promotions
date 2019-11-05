<?php

return [
    'default' => [
        'app' => '',
        'key' => '',
        'description' => '',
        'url' => '',
        'main' => false,
        'restrict_login_based_on_typology' => false,
        'register_in_two_steps' => false,
        'register_assisted' => false,
        'passwordless_register' => false,
        'live_event' => false,
        'platform' => env('DRUID_REST_HOST').'/platforms/1',
        'workTypology' => env('DRUID_REST_HOST').'/typologyes/1',
        'registerTypology' => env('DRUID_REST_HOST').'/typologyes/3'
    ],

    'simple' => [
        'config_id' => [
            [
            'mandatory' => true,
            'main' => true,
            'need_confirmation' => true,
            'used_as_validation_field_in_two_step_registration' => false,
            'field_type' => 'email'
            ]
        ],
        'config_field' => [
            // Name
            [
                'mandatory' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'extra_field_in_two_step_registration' => false,
                'typologyField' => env('DRUID_REST_HOST').'/typologyFields/1'
            ],
            // Surname
            [
                'mandatory' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'extra_field_in_two_step_registration' => false,
                'typologyField' => env('DRUID_REST_HOST').'/typologyFields/2',
            ],
            // Birthday
            [
                'mandatory' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'extra_field_in_two_step_registration' => false,
                'typologyField' => env('DRUID_REST_HOST').'/typologyFields/11'
            ]
        ]
    ],

    'complete' => [
        'config_id' => [
            [
                'mandatory' => true,
                'main' => true,
                'need_confirmation' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'field_type' => 'email'
            ]
        ],
        'config_field' => [
            // Name
            [
                'mandatory' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'extra_field_in_two_step_registration' => false,
                'typologyField' => env('DRUID_REST_HOST').'/typologyFields/1'
            ],
            // Surname
            [
                'mandatory' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'extra_field_in_two_step_registration' => false,
                'typologyField' => env('DRUID_REST_HOST').'/typologyFields/2'
            ],
            // Gender
            [
                'mandatory' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'extra_field_in_two_step_registration' => false,
                'typologyField' => env('DRUID_REST_HOST').'/typologyFields/3'
            ],
            // Postal Code
            [
                'mandatory' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'extra_field_in_two_step_registration' => false,
                'typologyField' => env('DRUID_REST_HOST').'/typologyFields/10'
            ],
            // Birthday
            [
                'mandatory' => true,
                'used_as_validation_field_in_two_step_registration' => false,
                'extra_field_in_two_step_registration' => false,
                'typologyField' => env('DRUID_REST_HOST').'/typologyFields/11'
            ]
        ]
    ]
];
