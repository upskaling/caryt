<?php #UTF-8

require_once(__DIR__ . '/../Models/Entry.php');
require_once(__DIR__ . '/../Models/Feedparser.php');

$entry = new Entry($pdo);
$feedparser = new Feedparser($pdo);

$status = null;
if (!empty($_POST['delete'])) {
    $entry->delete($_POST['delete']);
    $status = htmlspecialchars($_POST['delete']) . ' a été supprimé avec succès';
}

(int) $page = $_GET['page'] ?? 0;
if ($page) {
    $page_start = $config['items_per_page'] * $page;
    $page_max = $page_start + $config['items_per_page'];
} else {
    $page_start = 0;
    $page_max = $config['items_per_page'];
}

if (!empty($_POST['download'])) {
    $entry->download(
        $_POST['download'],
        $config
    );
    $status = htmlspecialchars($_POST['download']) . ' download';
}

$let_waiting = $entry->sizeof($_GET['state'] ?? 2);


function addUrlParam(array $params = [])
{
    return basename($_SERVER['PHP_SELF']) . '?' . http_build_query(array_merge($_GET, $params));
}

require(__DIR__ . '/../views/waiting.php');
