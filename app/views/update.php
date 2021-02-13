<?php $title = 'update'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">

    <h2>YouTube-DL</h2>
    <?php if (!empty($version)) : ?>
        <div class="alert alert-success" role="alert">
            Votre version de YouTube-DL <?= $version ?>
        </div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            YouTube-DL n'est pas installé
        </div>
    <?php endif; ?>

    <?php if (!empty($update)) : ?>
        <pre>
            <?= $update ?>
        </pre>
    <?php endif; ?>

    <a href="./?c=update&a=apply" class="btn btn-secondary">
        Mise à jour
    </a>

    <hr>

    <h2>rss</h2>
    <a href="./?c=update&a=rss_update" class="btn btn-secondary">
        Mise à jour
    </a>

</div>


<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>