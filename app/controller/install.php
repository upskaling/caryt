<?php

require_once(__DIR__ . '/../Models/Youtube_dl.php');

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

require(__DIR__ . '/../views/install.php');
