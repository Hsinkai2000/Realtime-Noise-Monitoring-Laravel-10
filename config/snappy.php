<?php
// config/snappy.php

return [
    'pdf' => [
        'enabled' => true,
        'binary' => env('WKHTMLTOPDF_BINARY'),
        'timeout' => false,
        'options' => [
            'enable-local-file-access' => true,
            'enable-javascript' => true,
            'quiet' => true,
        ],
        'env' => [
            'QTWEBENGINE_DISABLE_SANDBOX' => '1',
            'MALLOC_ARENA_MAX' => '2',
            'QT_QPA_PLATFORM' => 'offscreen',
        ],
    ],
];