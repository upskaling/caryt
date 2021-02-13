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
        <h1><?= htmlentities($category->name ?? '') ?></h1>
        <form action="#" method="post">

            <div class="form-group row">
                <label class="col-md-auto col-form-label" for="title">Titre</label>
                <div class="col-md">
                    <input class="form-control" type="text" name="title" id="title" value="<?= htmlentities($category->name ?? '')  ?>" <?= ($category->category != 0) ? '' : 'readonly' ?>>
                </div>
            </div>

            <hr>

            <?= $feedparser->get_count_id($category->category) ?> flus

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Appliquer les changements</button>
                <?php if ($category->category != 0) : ?>
                    <button type="submit" class="btn btn-danger" value="TRUE" name="delete">Supprimer</button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>