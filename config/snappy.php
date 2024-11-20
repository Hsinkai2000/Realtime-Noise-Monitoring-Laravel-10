<?php
// config/snappy.php

return [
    'pdf' => [
        'enabled' => true,
        'binary' => env('WKHTMLTOPDF_BINARY'),
        'timeout' => false,
        'options' => [],
        'env' => [],
    ],
];