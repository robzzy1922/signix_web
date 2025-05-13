<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary'  => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"',
        'timeout' => false,
        'options' => [
            'enable-javascript' => true,
            'javascript-delay' => 5000,
            'no-stop-slow-scripts' => true,
            'enable-smart-shrinking' => false,
            'window-status' => 'ready',
            'images' => true,
            'enable-local-file-access' => true,
            'dpi' => 300,
            'margin-top' => '25mm',
            'margin-right' => '25mm',
            'margin-bottom' => '25mm',
            'margin-left' => '25mm',
            'print-media-type' => true,
            'debug-javascript' => true,
        ],
        'env'     => [],
    ],
    'image' => [
        'enabled' => true,
        'binary'  => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage.exe"',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
];