<?php
require_once __DIR__ . '/../Models/Category.php';

$category = new Category($pdo);

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
    }

    if (isset($_POST['delete'])) {
        $category->delete($_GET['id']);
        header('Location: ?c=channels');
    }

    $query =  $pdo->prepare('SELECT * FROM "admin_category"
    WHERE "rowid" = :id');
    $query->execute([
        'id' => $_GET['id']
    ]);
    $category = $query->fetch();
} catch (PDOException $e) {
    $error = $e->getMessage();
}

?>


<?php $title = 'modifier la catégorie'; ?>
<?php ob_start(); ?>

<div class="container py-4">
    <div class="card card-body">

        <?php if ($error) : ?>
            <div class="alert alert-warning">
                <pre><?= $error ?></pre>
            </div>
        <?php endif; ?>

        <?php if ($success) : ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <h1><?= htmlentities($category->name) ?></h1>
        <form action="#" method="post">

            <div class="form-group row">
                <label class="col-md-auto col-form-label" for="title">Titre</label>
                <div class="col-md">
                    <input class="form-control" type="text" name="title" id="title" value="<?= htmlentities($category->name) ?? null ?>" <?= ($_GET['id'] ?: 1 != 1) ? '' : 'readonly' ?>>
                </div>
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Appliquer les changements</button>
                <?php if ($_GET['id'] ?: 1 != 1) : ?>
                    <button type="submit" class="btn btn-danger" value="TRUE" name="delete">Supprimer</button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>