<?php

$get_a = $_GET['a'] ?? '';
if ($get_a == 'reading') {

    $config['quality_default'] = (int) $_POST['quality_default'];
    $config['ttl_default'] = (int) $_POST['ttl_default'];
    $config['errorspass'] = (int) $_POST['errorspass'];
    $config['items_per_page'] = (int) $_POST['items_per_page'];
    $config['max_downloads'] = (int) $_POST['max_downloads'];
    $config['YOUTUBR_DL_WL_purge_days'] = (int) $_POST['YOUTUBR_DL_WL_purge_days'];
    $config['url'] = (string) $_POST['url'];

    file_put_contents('../data/config.php', '<?php $config = ' . var_export($config, true) . ';');
}

$get_a = $_GET['a'] ?? '';
if ($get_a == 'profile') {

    $config['login'][$_SERVER['PHP_AUTH_USER']] = password_hash($_POST['newPasswordPlain'], PASSWORD_ARGON2I);

    file_put_contents('../data/config.php', '<?php $config = ' . var_export($config, true) . ';');
}

require(__DIR__ . '/../views/configure.php');