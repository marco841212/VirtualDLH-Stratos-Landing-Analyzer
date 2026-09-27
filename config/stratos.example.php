<?php

/**
 * Example Stratos Landing Analyzer configuration.
 *
 * Never place production credentials in this file.
 */
return [
    'stratos' => [
        'api_url' => getenv('STRATOS_API_URL') ?: null,
        'api_token' => getenv('STRATOS_API_TOKEN') ?: null,
    ],

    'analysis' => [
        'enabled' => true,
    ],
];
