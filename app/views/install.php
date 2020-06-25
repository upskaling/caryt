<?php

require_once __DIR__ . '/../Models/Youtube_dl.php';
$youtube_dl = new Youtube_dl();

if (!is_file('install')) {
    if ($version = $youtube_dl->version()) {
        // $youtube_dl->install();
    } else {
        $youtube_dl_n = 1;
    }
    // touch('install');
} else {
    header('Location: ./');
}

?>
<?php $title = 'install'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div>
    <?php if (empty($youtube_dl_n)) : ?>
        <div class="alert alert-success" role="alert">
            Votre version de YouTube-DL <?= $version ?>
        </div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            YouTube-DL n'est pas installé
        </div>
    <?php endif; ?>

</div>


<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>