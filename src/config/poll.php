<?php

return [
    
    'restrict-usage' => false,
    
    'allowed-users' => [
        'admin@mail.io',
    ],

    'guard' => [
        // 'driver' => 'session',
        // 'provider' => 'users',
    ],

    'default-group' => 'question-all',

    'voter-type' => 'App\Models\User',

    'voter-guard' => 'web',

    'record-vote' => [
        // whether to use queue for recording votes.
        'use-queue' => false,
        
        // which queue to use.
        'queue' => 'default',

        'tries' => 3,
        'timeout' => 30,
    ],

];
