<?php

if (is_file('../data/config.php')) {
    require '../data/config.php';
} else {
    require '../config.default.php';
}

$get_a = $_GET['a'] ?? '';
if ($get_a == 'reading') {

    $GLOBALS['config']['errorspass'] = (int) $_POST['errorspass'];
    $GLOBALS['config']['items_per_page'] = (int) $_POST['items_per_page'];
    $GLOBALS['config']['max_downloads'] = (int) $_POST['max_downloads'];
    $GLOBALS['config']['YOUTUBR_DL_WL_purge_days'] = (int) $_POST['YOUTUBR_DL_WL_purge_days'];
    $GLOBALS['config']['url'] = (string) $_POST['url'];

    file_put_contents('../data/config.php', '<?php $GLOBALS[\'config\'] = ' . var_export($config, true) . ';');
}

?>
<?php $title = 'configure'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <form action="./?c=configure&a=reading" method="post">
                <div class="form-group">
                    <label for="">nombre de tentatives de téléchargement</label>
                    <input type="number" id="errorspass" name="errorspass" value="<?= $GLOBALS['config']['errorspass'] ?>" min="0" max="50" data-leave-validation="3">
                </div>
                <div class="form-group">
                    <label for="">nombre d'articles par page</label>
                    <input type="number" id="items_per_page" name="items_per_page" value="<?= $GLOBALS['config']['items_per_page'] ?>" min="5" max="500" data-leave-validation="20">
                </div>
                <div class="form-group">
                    <label for="">nombre max de téléchargement</label>
                    <input type="number" id="max_downloads" name="max_downloads" value="<?= $GLOBALS['config']['max_downloads'] ?>" min="0" max="500" data-leave-validation="5">
                </div>
                <div class="form-group">
                    <label for="">nombre de jours après lesquels il faut supprimer</label>
                    <input type="number" id="YOUTUBR_DL_WL_purge_days" name="YOUTUBR_DL_WL_purge_days" value="<?= $GLOBALS['config']['YOUTUBR_DL_WL_purge_days'] ?>" min="0" max="500" data-leave-validation="6">
                </div>
                <div class="form-group">
                    <label for="">URL de l'instance (important pour le flux RSS)</label>
                    <input type="url" id="url" name="url" value="<?= $GLOBALS['config']['url'] ?>">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Valider</button>
                    <button type="reset" class="btn btn-secondary">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>