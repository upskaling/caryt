<?php $title = 'install'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">

    <?php if (!$dir_bin) : ?>
        <div class="alert alert-danger" role="alert">
            Le répertoire n'existe pas ../bin
        </div>
    <?php endif; ?>

    <?php if (!$dir_data) : ?>
        <div class="alert alert-danger" role="alert">
            Le répertoire n'existe pas ../data
        </div>
    <?php endif; ?>

    <?php if (!empty($version_youtube_dl)) : ?>
        <div class="alert alert-success" role="alert">
            Votre version de YouTube-DL <?= $version_youtube_dl ?>
        </div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            YouTube-DL n'est pas installé
        </div>
    <?php endif; ?>

    <?php if (!empty($sql_status)) : ?>
        <div class="alert alert-success" role="alert">
            <?= $sql_status ?>
        </div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            impossible de créer la base de données
        </div>
    <?php endif; ?>

</div>


<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>