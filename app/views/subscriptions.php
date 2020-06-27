<?php #UTF-8
include  __DIR__ . '/../Models/InfoVideo.php';

$count_video = 0;
foreach (scandir($config['YOUTUBR_DL_WL']) as $fileDate) {
  if (in_array($fileDate, ['.', '..'])) continue;
  $youtubr_dl_wl_dir[] = $fileDate;
  foreach (scandir($config['YOUTUBR_DL_WL'] . '/' . $fileDate) as $video_id) {
    if (in_array($video_id, ['.', '..'])) continue;
    $count_video += 1;
  }
}

?>


<?php $title = '(' . $count_video . ') Abonnements'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">
  <div>
    <?php
    foreach ($youtubr_dl_wl_dir as $filename) :
      if (!is_dir($dir_video = $config['YOUTUBR_DL_WL'] . '/' . $filename)) {
        continue;
      }
    ?>
      <div class="d-flex justify-content-center bd-highlight mb-2 text-center">
        <div class="card">
          <a class="dropdown-item" href="?c=subscriptions&page=<?= $filename ?>#<?= $filename ?>"><?= $filename ?>
            <span class="badge badge-dark"><?= count(scandir($dir_video)) - 2 ?></span>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php
  $filename = isset($_GET['page']) ? $_GET['page'] : end($youtubr_dl_wl_dir);
  $dir_video = $config['YOUTUBR_DL_WL'] . '/' . $filename;
  ?>

  <div id="<?= $filename ?>">

    <div class="d-flex justify-content-center bd-highlight mb-2 text-center">
      <div class="card">
        <a href="#<?= $filename ?>" class="p-2 bd-highlight dropdown-item">
          <?= $filename ?>
          <span class="badge badge-dark"><?= count(scandir($dir_video)) - 2 ?></span>
        </a>
      </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3">

      <?php
      foreach (scandir($dir_video) as $name) :
        if (in_array($name, array(".", ".."))) continue;

        $InfoVideo = new InfoVideo($dir_video . '/' . $name . '/' . $name . '.info.json');
        $info = $InfoVideo->info;

        $info['_filename'] = $dir_video . '/' . $name . '/' . pathinfo($info['_filename'], PATHINFO_BASENAME);
        if (is_file($info['_filename'])) {
          $hebergeur = '<img src="favicon.png" title="local" width="16" height="16">';
        } else {
          $hebergeur = '<img src="youtube-logo.png" title="YouTube" width="16" height="16">';
        }
        
        if ($info['duration'] >= 3600) {
          $info['duration'] = strftime("%H:%M:%S", $info['duration']);
        } else {
          $info['duration'] = strftime("%M:%S", $info['duration']);
        }
        
        $thumbnail_basename = $InfoVideo->ThumbnailBasename;
        if (is_file($config['YOUTUBR_DL_WL'] . '/' . $filename . '/' .  $name . '/' . $name . '.' . $thumbnail_basename)) {
          $info['thumbnail'] = 'f.php?' . $filename . '/' . $name . '.' . $thumbnail_basename;
        }
        
        if ($info['thumbnail']) {
          $poster = '<img src="' . htmlspecialchars($info['thumbnail']) . '" loading="lazy" alt="" width="100%">';
        }
        
        $url_video = '?c=watch&v=' . $filename . '/' . $name;
      ?>

        <div class="col mb-4">
          <div class="card h-100 shadow-sm">
            <a href="<?= $url_video ?>">
              <div class="thumbnail">
                <?= $poster ?>
                <p class="length"><?= htmlspecialchars($info['duration']) ?></p>
              </div>
            </a>
            <div class="card-body row row-cols-1">
              <div class="col align-self-center">
                <p><?= htmlspecialchars($info['title']) ?></p>
              </div>
              <div class="col align-self-end">
                <a href="<?= htmlspecialchars($info['uploader_url']) ?>" rel="author" class="card-text">
                  <small class="text-muted">
                    <?= htmlspecialchars($info['uploader']) ?>
                  </small>
                </a>
                <div class="d-flex justify-content-between">
                  <div><?= $hebergeur ?></div>
                  <div><?= htmlspecialchars($info['view_count']) ?> vues</div>
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