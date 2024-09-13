<?php

use HXM\LaravelDatabaseEmailTemplate\Mail\DemoDatabaseEmailTemplate;

return [
    /**
     * add Maiables list
     */
    'mailables' => [
        // DemoDatabaseEmailTemplate::class
    ],

    /**
     * Admin route configs
     */
    'route' => [
        'enable' => true,
        'prefix' => 'database-email-templates',
        'as' => 'database-email-templates',
        'middleware' => ['web', 'auth'],
    ],

    /**
     * view configs
     */
    'view' => [
        'namespace' => 'database_email_template'
    ]
];
