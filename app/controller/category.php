<?php
require_once(__DIR__ . '/../Models/Category.php');
require_once(__DIR__ . '/../Models/Feedparser.php');

$category = new Category($pdo);
$feedparser = new Feedparser($pdo);

$error = null;
$success = null;
try {

    if (isset($_POST['title'])) {
        $category->update($_POST['title'], $_GET['id']);
        $success = 'modification enregistrée avec succès';
    }

    if (isset($_POST['new-category'])) {
        $category->add_category($_POST['new-category']);
        $success = 'la nouvelle catégorie a été enregistrée avec succès';
        // header('Location: ?c=category&id=' . $pdo->lastInsertId());
        header('Location: ?c=channels');
        exit();
    }

    if (isset($_POST['delete'])) {
        $category->delete($_GET['id']);
        header('Location: ?c=channels');
        exit();
    }

    $category = $category->GetCategory($_GET['id']);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

require(__DIR__ . '/../views/category.php');
