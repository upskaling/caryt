<?php

require_once(__DIR__ . '/../Models/Entry.php');

$entry = new Entry($pdo);

if ($_GET['a'] == 'read' and isset($_POST['id'])) {
    $query = $entry->update_is_read($_POST['id'], ($_GET['is_read'] == 1) ? null : 1);
    header('Location: ./?c=waiting');
}
