<?php

require_once __DIR__ . '/../Models/Feedparser.php';
require_once __DIR__ . '/../Models/Category.php';

$category = new category($config['category']);
$feedparser = new Feedparser($config['feed']);

(int) $Get_id = $_GET['id'] ?? '';

if (isset($_POST['new-category'])) {
    $category->add_category(htmlspecialchars($_POST['new-category']));
    header('Location: ?c=channels#');
}


if (isset($_POST['title'])) {
    $category->category[$Get_id] = $_POST['title'];
    $category->write();
    header('Location: ?c=channels#');
}

$delete = $_POST['delete'] ?? '';
if ($delete) {

    $feedparser->delete_category($Get_id);

    $category->delete($Get_id);
    $category->write();
    // header('Location: ?c=channels');
}

$category->write();

?>


<?php $title = 'modifier la catégorie'; ?>
<?php ob_start(); ?>

<div class="container py-4">
    <div class="card card-body">
        <h1><?= $category->category[$Get_id] ?></h1>
        <form action="#" method="post">

            <div class="form-group row">
                <label class="col-md-auto col-form-label" for="title">Titre</label>
                <div class="col-md">
                    <input class="form-control" type="text" name="title" id="title" value="<?= $category->category[$Get_id] ?? null ?>" <?= ($Get_id != 0) ? '' : 'readonly' ?>>
                </div>
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Appliquer les changements</button>
                <?php if ($Get_id != 0) : ?>
                    <button type="submit" class="btn btn-danger" value="TRUE" name="delete">Supprimer</button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>