<?php #UTF-8

require_once(__DIR__ . '/../Models/Entry.php');
$entry = new Entry($pdo);

(string) $url = filter_var($_POST["url"] ?? '', FILTER_VALIDATE_URL);
if (!empty($url)) {
    $entry->add_video($url);
}

require(__DIR__ . '/../views/add_url.php');
