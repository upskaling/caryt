<?php $title = 'Modifier le feed'; ?>
<?php ob_start(); ?>

<div class="container py-4">
    <div class="card card-body">

        <h1><?= $id->title ?? null ?></h1>

        <?php if (isset($error)) : ?>
            <div class="alert alert-warning">
                <pre><?= $error ?></pre>
            </div>
        <?php endif; ?>

        <?php if (isset($success)) : ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if (isset($feed->status)) : ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($feed->status) ?>
            </div>
        <?php endif; ?>

        <h1><?= $feed->title ?: 'title' ?></h1>

        <form action="#" method="post">

            <div class="form-group row">
                <label class="col-md-auto col-form-label" for="title">Titre</label>
                <div class="col-md">
                    <input class="form-control" type="text" name="title" id="title" value="<?= $feed->title ?: 'title' ?>">
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-md-auto col-form-label" for="xmlUrl">URL du flux</label>
                <div class="col-md">
                    <input type="url" class="form-control" name="xmlUrl" id="xmlUrl" value="<?= $feed->xmlurl ?: '' ?>">
                </div>
                <a class="col-auto btn btn-primary" target="_blank" rel="noreferrer" href="<?= $feed->xmlurl ?: '#' ?>">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                        <path fill-rule="evenodd" d="M10.5 5h-4a.5.5 0 0 0 0 1h2.793l-4.147 4.146a.5.5 0 0 0 .708.708L10 6.707V9.5a.5.5 0 0 0 1 0v-4a.5.5 0 0 0-.5-.5z" />
                    </svg>
                </a>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-md-auto col-form-label" for="siteUrl">URL du site</label>
                <div class="col-md">
                    <input type="url" class="form-control" name="siteUrl" id="siteUrl" value="<?= $feed->siteurl ?: '' ?>">
                </div>
                <a class="col-auto btn btn-primary" target="_blank" rel="noreferrer" href="<?= $feed->siteurl ?: '#' ?>">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                        <path fill-rule="evenodd" d="M10.5 5h-4a.5.5 0 0 0 0 1h2.793l-4.147 4.146a.5.5 0 0 0 .708.708L10 6.707V9.5a.5.5 0 0 0 1 0v-4a.5.5 0 0 0-.5-.5z" />
                    </svg>
                </a>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-md-auto col-form-label" for="category">Catégorie</label>
                <div>
                    <select class="form-control" name="category" id="category">
                        <?php foreach ($category as $key => $category_value) : ?>
                            <option value="<?= $category_value->category ?>" <?= ($feed->category == $category_value->category) ? 'selected="selected"' : '' ?>><?= htmlspecialchars($category_value->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-auto col-form-label">
                    <?php if (!empty($categories[0]->categories)) : ?>
                        Recommandé: <?= $categories[0]->categories ?>
                    <?php endif; ?>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label class="col-md-auto col-form-label" for="update_interval">Ne pas automatiquement rafraîchir plus souvent que</label>
                <div>
                    <select class="form-control" name="update_interval" id="update_interval" required="required" data-leave-validation="<?= $feed->update_interval ?>">
                        <?php foreach ([
                            'Par défaut',
                            '20min', '25min', '30min', '45min',
                            '1h', '1.5h',  '2h', '3h', '4h', '5h', '6h', '7h', '8h',
                            '10h', '12h', '18h',
                            '1d', '1.5d',  '2d',  '3d',  '4d', '5d', '6d',
                            '1wk'
                        ] as $key => $value) : ?>
                            <option value="<?= $key ?>" <?= ($feed->update_interval == $key ? 'selected' : '') ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="mute">
                        <input type="checkbox" name="mute" id="mute" value="1" <?= (empty($feed->mute)) ?: 'checked' ?>>
                        muet
                    </label>
                </div>
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Appliquer les changements</button>
                <button type="submit" class="btn btn-danger" value="TRUE" name="delete">Supprimer</button>
            </div>

            <hr>

            <div class="text-center">
                <?= $entry->count_entry($feed->xmlurl) ?> articles
                <a class="btn btn-primary" href="./?c=feed&a=actualize&id=<?= $_GET['id'] ?: 0 ?>">
                    <svg aria-hidden="true" class="bi bi-arrow-repeat" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M2.854 7.146a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L2.5 8.207l1.646 1.647a.5.5 0 0 0 .708-.708l-2-2zm13-1a.5.5 0 0 0-.708 0L13.5 7.793l-1.646-1.647a.5.5 0 0 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0 0-.708z" />
                        <path fill-rule="evenodd" d="M8 3a4.995 4.995 0 0 0-4.192 2.273.5.5 0 0 1-.837-.546A6 6 0 0 1 14 8a.5.5 0 0 1-1.001 0 5 5 0 0 0-5-5zM2.5 7.5A.5.5 0 0 1 3 8a5 5 0 0 0 9.192 2.727.5.5 0 1 1 .837.546A6 6 0 0 1 2 8a.5.5 0 0 1 .501-.5z" />
                    </svg>
                    Actualiser
                </a>
            </div>

        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>