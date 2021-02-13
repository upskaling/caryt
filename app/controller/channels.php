<?php #UTF-8

require_once(__DIR__ . '/../Models/Feedparser.php');
require_once(__DIR__ . '/../Models/Category.php');

$feedparser = new Feedparser($pdo);
$category = new category($pdo);

$error = null;
try {
    $feed = $feedparser->feed();
    $category = $category->get()->fetchAll();
} catch (PDOException $e) {
    $error = $e->getMessage();
}

$category_len = sizeof($category);
require(__DIR__ . '/../views/channels.php');
