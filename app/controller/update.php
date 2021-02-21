<?php

require_once(__DIR__ . '/../Models/Youtube_dl.php');
require_once(__DIR__ . '/../Models/Feedparser.php');

$youtube_dl = new Youtube_dl();
$feedparser = new Feedparser($pdo);

$version = $youtube_dl->version();

$a = $_GET['a'] ?? '';
if ($a === 'apply') {
    $update = htmlspecialchars($youtube_dl->update());
}

if ($a === 'rss_update') {
    include __DIR__ . '/../rss.php';
}

if ($a === 'flux_update') {
    $flux_update = $feedparser->update_siteurl();
}

require(__DIR__ . '/../views/update.php');
