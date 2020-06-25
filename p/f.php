<?php

if (empty($_SERVER['QUERY_STRING'])) {
    print('non');
    http_response_code(404);
    exit;
}

$path_parts = pathinfo($_SERVER['QUERY_STRING']);

if ($path_parts['extension'] == 'json') {
    print('non');
    http_response_code(500);
    exit;
}

if (is_file('../data/config.php')) {
    require '../data/config.php';
} else {
    require '../config.default.php';
}

$file = [
    $GLOBALS['config']['YOUTUBR_DL_WL'],
    $path_parts['dirname'],
    $path_parts['filename'],
    $path_parts['basename']
];

$file = join('/', $file);
header('content-Type: ' . mime_content_type($file));
header('content-length: ' . filesize($file));
header('cache-control: max-age=2592000');

readfile($file);
