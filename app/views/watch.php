<?php

$jsonb = json_decode(file_get_contents($config['YOUTUBR_DL_WL'] . '/' . $_GET["v"] . '/' . basename($_GET["v"]) . '.info.json'), true);

$thumbnail_basename = pathinfo($jsonb['thumbnail'], PATHINFO_EXTENSION);
$name = basename($_GET["v"]);

if (is_file($config['YOUTUBR_DL_WL'] . '/' . $_GET["v"] . '/' . $name . '.' . $thumbnail_basename)) {
    $jsonb['thumbnail'] = 'f.php?' . $_GET["v"] . '.' . $thumbnail_basename;
}

if ($jsonb['thumbnail']) {
    $poster = '<img src="' . htmlspecialchars($jsonb['thumbnail']) . '" loading="lazy" alt="" width="100%">';
}

if (is_file($jsonb['_filename'])) {
    $url_video = 'f.php?' . $_GET["v"] . '.' . pathinfo($jsonb['_filename'], PATHINFO_EXTENSION);
    $LSbasename = $jsonb['_filename'];
    $LSmimetypes = mime_content_type($jsonb['_filename']);
    $html_video = '<div class="embed-responsive embed-responsive-16by9">
    <video class="embed-responsive-item" width="100%" preload="none" controls poster="' . $jsonb['thumbnail'] . '">
        <source src="' . $url_video . '" type="' . $LSmimetypes . '"/>
        Votre navigateur ne permet pas de lire les vidéos HTML5.
    </video>
    </div>';
} else {
    $html_video = '<a href="' . htmlspecialchars($jsonb['webpage_url']) . '">' . $poster . '</a>';
}

?>

<?php $title = htmlspecialchars($jsonb['title']); ?>
<?php ob_start(); ?>

<div class="card">
    <div class="card-body">
        <?= $html_video ?>
        <a title="<?= htmlspecialchars($jsonb['title']) ?>" href="<?= htmlspecialchars($jsonb['webpage_url']) ?>"><?= htmlspecialchars($jsonb['title']) ?></a>
        <p><?= htmlspecialchars($jsonb['view_count']) ?> vues · <?= date('j M Y', strtotime($jsonb['upload_date'])) ?></p>
        <hr>
        <a href="<?= htmlspecialchars($jsonb['uploader_url']) ?>" rel="author"><?= htmlspecialchars($jsonb['uploader']) ?></a>
        <p><?= nl2br(htmlspecialchars($jsonb['description']), false) ?></p>
    </div>

</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>