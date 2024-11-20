<?php
// config/snappy.php

return [
    'pdf' => [
        'enabled' => true,
        'binary' => env('WKHTMLTOPDF_BINARY'),
        'timeout' => false,
        'options' => [
            'no-images' => true,
            'no-display' => true,
        ],
        'env' => [],
    ],
];
