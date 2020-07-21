<?php

require_once __DIR__ . '/../Models/Youtube_dl.php';
$youtube_dl = new Youtube_dl();

$version = $youtube_dl->version();

$a = $_GET['a'] ?? '';
if ($a === 'apply') {
    $update = htmlspecialchars($youtube_dl->update());
}

?>
<?php $title = 'update'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">

    <?php if (!empty($update)) : ?>
        <pre>
            <?= $update ?>
        </pre>
    <?php endif; ?>

    <?php if (!empty($version)) : ?>
        <div class="alert alert-success" role="alert">
            Votre version de YouTube-DL <?= $version ?>
        </div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            YouTube-DL n'est pas installé
        </div>
    <?php endif; ?>

    <a href="./?c=update&a=apply" class="btn btn-secondary">
        Appliquer la mise à jour
    </a>
</div>


<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>