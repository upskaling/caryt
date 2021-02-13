<?php

require_once(__DIR__ . '/../Models/Youtube_dl.php');

$youtube_dl = new Youtube_dl();

$version = $youtube_dl->version();

$a = $_GET['a'] ?? '';
if ($a === 'apply') {
    $update = htmlspecialchars($youtube_dl->update());
}

if ($a === 'rss_update') {
    include __DIR__ . '/../rss.php';
}

require(__DIR__ . '/../views/update.php');
