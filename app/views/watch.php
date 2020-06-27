<?php

include  __DIR__ . '/../Models/InfoVideo.php';

$name = basename($_GET["v"]);
$InfoVideo = new InfoVideo($config['YOUTUBR_DL_WL'] . '/' . $_GET["v"] . '/' . $name . '.info.json');
$info = $InfoVideo->info;

$thumbnail_basename = $wl->ThumbnailBasename;
if (is_file($config['YOUTUBR_DL_WL'] . '/' . $_GET["v"] . '/' . $name . '.' . $thumbnail_basename)) {
    $info['thumbnail'] = 'f.php?' . $_GET["v"] . '.' . $thumbnail_basename;
}

if ($info['thumbnail']) {
    $poster = '<img src="' . htmlspecialchars($info['thumbnail']) . '" loading="lazy" alt="" width="100%">';
}

if (is_file($info['_filename'])) {
    $url_video = 'f.php?' . $_GET["v"] . '.' . pathinfo($info['_filename'], PATHINFO_EXTENSION);
    $LSbasename = $info['_filename'];
    $LSmimetypes = mime_content_type($info['_filename']);
    $html_video = '<div class="embed-responsive embed-responsive-16by9">
    <video class="embed-responsive-item" width="100%" preload="none" controls poster="' . $info['thumbnail'] . '">
        <source src="' . $url_video . '" type="' . $LSmimetypes . '"/>
        Votre navigateur ne permet pas de lire les vidéos HTML5.
    </video>
    </div>';
} else {
    $html_video = '<a href="' . htmlspecialchars($info['webpage_url']) . '">' . $poster . '</a>';
}

?>

<?php $title = htmlspecialchars($info['title']); ?>
<?php ob_start(); ?>

<div class="card">
    <div class="card-body">
        <?= $html_video ?>
        <a title="<?= htmlspecialchars($info['title']) ?>" href="<?= htmlspecialchars($info['webpage_url']) ?>"><?= htmlspecialchars($info['title']) ?></a>
        <p><?= htmlspecialchars($info['view_count']) ?> vues · <?= date('j M Y', strtotime($info['upload_date'])) ?></p>
        <hr>
        <a href="<?= htmlspecialchars($info['uploader_url']) ?>" rel="author"><?= htmlspecialchars($info['uploader']) ?></a>
        <p><?= nl2br(htmlspecialchars($info['description']), false) ?></p>
    </div>

</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>