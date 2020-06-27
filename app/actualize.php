#!/usr/bin/php
<?php #UTF-8

// tester si le fichier déjà exécuté
// https://www.exakat.io/prevent-multiple-php-scripts-at-the-same-time/
function get_lock()
{
    // Create a new socket
    $stream = @stream_socket_server('tcp://127.0.0.1:7600', $errno, $errmg, STREAM_SERVER_BIND);

    if ($stream) {
        return '[lock] ok';
        // fclose($stream);
    } else {
        return "[lock]";
    }
}


// tête de connexion
// https://stackoverflow.com/questions/1239068/ping-site-and-return-result-in-php
function test_connection($host, $port, $timeout)
{
    $tB = microtime(true);
    $fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
    if (!$fP) {
        return 'down';
    }
    $tA = microtime(true);
    return round((($tA - $tB) * 1000), 0) . ' ms';
}

// déplacer dans la poubelle les anciennes vidéos
function purge($path, $days)
{
    foreach (glob($path . '/*/') as $dir) {

        if (in_array(basename($dir), array('trash', 'js', 'css'), true)) {
            continue;
        }

        if (strtotime('-' . $days . ' days') > strtotime(basename($dir) . "\n")) {
            rename($dir, $path . '/trash');
        }
    }
}

// https://andy-carter.com/blog/recursively-remove-a-directory-in-php
function removeDirectory($path)
{
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    is_dir($path) ? rmdir($path) : '';
    return;
}

function diff_dir($path, $path_diff)
{
    $result = 0;
    if (is_file($path_diff)) {
        $fil_list = file_get_contents($path_diff);
    } else {
        $fil_list = '';
    }
    $cur_files = join("\n", glob($path . '/*/*/'));
    if ($fil_list != $cur_files) {
        file_put_contents($path_diff, $cur_files);
        $result = 1;
    }
    return $result;
}

function main()
{
    if (is_file('../data/config.php')) {
        require '../data/config.php';
    } else {
        require '../config.default.php';
    }

    error_log(get_lock());

    // if (test_connection('www.youtube.com', 443, 60) == 'down') {
    //     error_log("[connect] Cannot connect to www.youtube.com");
    //     exit;
    // }

    purge($config['YOUTUBR_DL_WL'], $config['YOUTUBR_DL_WL_purge_days']);

    removeDirectory($config['YOUTUBR_DL_WL'] . '/trash');

    require_once 'Models/Feedparser.php';
    $feedparser = new Feedparser($config['feed']);
    $feedparser->track_flows($config['max_feed']);

    require_once 'Models/Waiting_list.php';
    $waiting_list = new Waiting_list($config['waiting_list']);
    $waiting_list->download_from_list(
        $config['max_downloads'],
        $config['errorspass'],
        $config['YOUTUBR_DL_WL'] . '/' . date("Y-m-d", time()) . '/%(id)s/%(id)s.%(ext)s',
        $config['cookiefile'],
        $config['download-archive']
    );

    if (diff_dir($config['YOUTUBR_DL_WL'], $config['diff'])) {
        include 'rss.php';
    }

    error_log('End.');
}

main();
