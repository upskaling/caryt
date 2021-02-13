<?php

require_once(__DIR__ . '/../Models/InfoVideo.php');

$Get_v = (string) $_GET["v"] ?? '';
$name = basename($Get_v);
$InfoVideo = new InfoVideo($config['YOUTUBR_DL_WL'] . '/' . $Get_v . '/' . $name . '.info.json');
$info = $InfoVideo->info;

$thumbnail_basename = $InfoVideo->ThumbnailBasename();
if (is_file($config['YOUTUBR_DL_WL'] . '/' . $Get_v . '/' . $name . '.' . $thumbnail_basename)) {
    $info['thumbnail'] = 'f.php?' . $Get_v . '.' . $thumbnail_basename;
}

if ($info['thumbnail']) {
    $poster = '<img src="' . htmlspecialchars($info['thumbnail']) . '" loading="lazy" width="100%">';
}

if (is_file($info['_filename'])) {
    $url_video = 'f.php?' . $Get_v . '.' . pathinfo($info['_filename'], PATHINFO_EXTENSION);
    $LSbasename = $info['_filename'];
    $LSmimetypes = mime_content_type($info['_filename']);
    $html_video = '<div class="embed-responsive embed-responsive-16by9">
    <video class="embed-responsive-item" preload="none" controls poster="' . $info['thumbnail'] . '">
        <source src="' . $url_video . '" type="' . $LSmimetypes . '"/>
        Votre navigateur ne permet pas de lire les vidéos HTML5.
    </video>
    </div>';
} else {
    $html_video = '<a href="' . htmlspecialchars($info['webpage_url']) . '">' . $poster . '</a>';
}

require(__DIR__ . '/../views/watch.php');
