<?php

return [

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
