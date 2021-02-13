<?php
$valid_users = array_keys($config['login']);

$validated = (in_array($_SERVER['PHP_AUTH_USER'], $valid_users) and password_verify($_SERVER['PHP_AUTH_PW'], $config['login'][$_SERVER['PHP_AUTH_USER']]));
if (!$validated) {
    header('WWW-Authenticate: Basic realm="Login"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'User pressed Cancel <br>';
    exit;
}
