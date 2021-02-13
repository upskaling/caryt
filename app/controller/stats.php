<?php #UTF-8

require_once(__DIR__ . '/../Models/Entry.php');
require_once(__DIR__ . '/../Models/Feedparser.php');

$feedparser = new Feedparser($pdo);
$entry = new Entry($pdo);

try {
    $flux = $entry->flux();

    $jour = [];
    foreach ($entry->get_update() as $key => $value) {

        $update = date('Y-m-d', $value->update);
        if (!isset($jour[$update])) {
            $jour[$update] = 1;
        } else {
            $jour[$update] += 1;
        }
    }
    arsort($jour);
    $count_jour = count($jour);

    $count_entry = $entry->count_entry_r()->count_url;

    $categories = $entry->get_categories();

    $total_count_url = $entry->total_count_url();
    $count_entry_lus = $entry->count_entry_lus();
} catch (PDOException $e) {
    $error = $e->getMessage();
}
require(__DIR__ . '/../views/stats.php');
