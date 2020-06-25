<?php #UTF-8
foreach (glob($GLOBALS['config']['YOUTUBR_DL_WL'] . '/*/') as $filename) {
  if (in_array(basename($filename), array('trash', '.', '..'), true)) {
    continue;
  }
  $youtubr_dl_wl_dir[] = basename($filename);
}

?>


<?php $title = '(' . count($youtubr_dl_wl_dir) . ') Abonnements'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">
  <div>
    <?php
    foreach ($youtubr_dl_wl_dir as $filename) {
      if (!is_dir($dir_video = $GLOBALS['config']['YOUTUBR_DL_WL'] . '/' . $filename)) {
        continue;
      }
      if (in_array($filename, array('trash', '.', '..'), true)) {
        continue;
      }
    ?>
      <div class="d-flex justify-content-center bd-highlight mb-2 text-center">
        <div class="card">
          <a class="dropdown-item" href="?c=subscriptions&page=<?= $filename ?>#<?= $filename ?>"><?= $filename ?>
            <span class="badge badge-dark"><?= sizeof(scandir($dir_video)) - 2 ?></span>
          </a>
        </div>
      </div>
    <?php } ?>
  </div>

  <?php
  $filename = isset($_GET['page']) ? $_GET['page'] : end($youtubr_dl_wl_dir);
  $dir_video = $GLOBALS['config']['YOUTUBR_DL_WL'] . '/' . $filename;
  ?>

  <div id="<?= $filename ?>">

    <div class="d-flex justify-content-center bd-highlight mb-2 text-center">
      <div class="card">
        <a href="#<?= $filename ?>" class="p-2 bd-highlight dropdown-item">
          <?= $filename ?>
          <span class="badge badge-dark"><?= sizeof(scandir($dir_video)) - 2 ?></span>
        </a>
      </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3">

      <?php
      foreach (scandir($dir_video) as $name) :
        if (in_array($name, array(".", ".."))) {
          continue;
        }
        $jsonb = json_decode(file_get_contents($dir_video . '/' . $name . '/' . $name . ".info.json"), true);

        $jsonb['_filename'] = $dir_video . '/' . $name . '/' . pathinfo($jsonb['_filename'], PATHINFO_BASENAME);
        if (is_file($jsonb['_filename'])) {
          $hebergeur = '<img src="favicon.png" title="local" width="16" height="16">';
        } else {
          $hebergeur = '<img src="youtube-logo.png" title="YouTube" width="16" height="16">';
        }

        $url_video = '?c=watch&v=' . $filename . '/' . $name;

        if ($jsonb['duration'] >= 3600) {
          $jsonb['duration'] = strftime("%H:%M:%S", $jsonb['duration']);
        } else {
          $jsonb['duration'] = strftime("%M:%S", $jsonb['duration']);
        }

        $thumbnail_basename = pathinfo($jsonb['thumbnail'], PATHINFO_EXTENSION);
        if (is_file($GLOBALS['config']['YOUTUBR_DL_WL'] . '/' . $filename . '/' .  $name . '/' . $name . '.' . $thumbnail_basename)) {
          $jsonb['thumbnail'] = 'f.php?' . $filename . '/' . $name . '.' . $thumbnail_basename;
        }

        if ($jsonb['thumbnail']) {
          $poster = '<img src="' . htmlspecialchars($jsonb['thumbnail']) . '" loading="lazy" alt="" width="100%">';
        }

      ?>

        <div class="col mb-4">
          <div class="card h-100 shadow-sm">
            <a href="<?= $url_video ?>">
              <div class="thumbnail">
                <?= $poster ?>
                <p class="length"><?= htmlspecialchars($jsonb['duration']) ?></p>
              </div>
            </a>
            <div class="card-body row row-cols-1">
              <div class="col align-self-center">
                <p><?= htmlspecialchars($jsonb['title']) ?></p>
              </div>
              <div class="col align-self-end">
                <a href="<?= htmlspecialchars($jsonb['uploader_url']) ?>" rel="author" class="card-text">
                  <small class="text-muted">
                    <?= htmlspecialchars($jsonb['uploader']) ?>
                  </small>
                </a>
                <div class="d-flex justify-content-between">
                  <div><?= $hebergeur ?></div>
                  <div><?= htmlspecialchars($jsonb['view_count']) ?> vues</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>