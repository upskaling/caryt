<?php

require_once __DIR__ . '/../Models/Youtube_dl.php';
$youtube_dl = new Youtube_dl();

$dir_data = $dir_bin = true;

if (!is_file('../data/install')) {

    if (!is_dir('../data')) {
        if (mkdir('../data', 0774)) {
            $dir_data = false;
        }
    }
    if (!is_dir('../bin')) {
        if (mkdir('../bin', 0774)) {
            $dir_bin = false;
        }
    }

    $version_youtube_dl = $youtube_dl->version();

    if (empty($version_youtube_dl)) {
        $youtube_dl->install();
        $version_youtube_dl = $youtube_dl->version();
    }

    if (!is_file('../data/data.db')) {
        touch('../data/data.db');

        $pdo = new PDO(
            $config['db'],
            null,
            null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]
        );

        $pdo->exec(file_get_contents('../app/install/data-db.sql'));
        $sql_status = 'nouvelle base de données';
    } else {
        $sql_status = 'base de données existante';
    }

    if (!empty($version_youtube_dl) and !empty($sql_status)) {
        touch('../data/install');
    }
} else {
    header('Location: ./');
    exit();
}

?>
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