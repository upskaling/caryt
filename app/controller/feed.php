<?php

require_once(__DIR__ . '/../Models/Feedparser.php');
require_once(__DIR__ . '/../Models/Entry.php');
require_once(__DIR__ . '/../Models/Category.php');

$feedparser = new Feedparser($pdo);
$entry = new Entry($pdo);
$category = new category($pdo);


$error = null;
$success = null;
try {

    if (isset($_GET['a'])) {

        if ($_GET['a'] == 'actualize') {
            $feedparser->refresh_a_stream($_GET['id'], $config['ttl_default']);
            $feedparser->write();
            $success = 'le flux a été rafraîchi avec succès';
        }

        if ($_GET['a'] == 'add') {
            $feedparser->add_feeds(filter_var($_POST['add_url'], FILTER_VALIDATE_URL), $resulta);
            if ($resulta === 0) {
                $success = 'le nouveau flux été ajouté';
            } else {
                $success = $resulta;
            }
            header('Location: ?c=feed&id=' . $pdo->lastInsertId());
            exit();
        }
    }

    if (isset($_POST['delete'])) {

        $feedparser->delete($_GET['id']);
        header('Location: #');
        exit();
    }

    if (isset($_POST['xmlUrl'])) {
        $mute = 0;
        if (isset($_POST['mute'])) {
            $mute = 1;
        }
        $feedparser->update(
            $_POST['xmlUrl'],
            $_POST['siteUrl'],
            $_POST['title'],
            $_POST['update_interval'],
            $_POST['category'],
            $mute,
            $_GET['id']
        );
        $success = 'modification enregistrée avec succès';
    }

    $feed = $feedparser->get_id($_GET['id'])->fetch();

    $categories = $category->get_top($feed->xmlurl)->fetchAll();

    $category = $category->get()->fetchAll();
} catch (PDOException $e) {
    $error = $e->getMessage();
}


require(__DIR__ . '/../views/feed.php');
