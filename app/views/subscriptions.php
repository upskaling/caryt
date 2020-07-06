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
          <a class="dropdown-item" href="?c=subscriptions&page=<?= $filename ?>#date-<?= $filename ?>"><?= $filename ?>
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

  <div id="date-<?= $filename ?>">

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
          $hebergeur = '<svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-hdd" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M14 9H2a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1zM2 8a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1a2 2 0 0 0-2-2H2z"/>
          <path d="M5 10.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm-2 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
          <path fill-rule="evenodd" d="M4.094 4a.5.5 0 0 0-.44.26l-2.47 4.532A1.5 1.5 0 0 0 1 9.51v.99H0v-.99c0-.418.105-.83.305-1.197l2.472-4.531A1.5 1.5 0 0 1 4.094 3h7.812a1.5 1.5 0 0 1 1.317.782l2.472 4.53c.2.368.305.78.305 1.198v.99h-1v-.99a1.5 1.5 0 0 0-.183-.718L12.345 4.26a.5.5 0 0 0-.439-.26H4.094z"/>
        </svg>';
        } else {
          $hebergeur = '<svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-globe" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M1.018 7.5h2.49c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5zM2.255 4H4.09a9.266 9.266 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.024 7.024 0 0 0 2.255 4zM8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm-.5 1.077c-.67.204-1.335.82-1.887 1.855-.173.324-.33.682-.468 1.068H7.5V1.077zM7.5 5H4.847a12.5 12.5 0 0 0-.338 2.5H7.5V5zm1 2.5V5h2.653c.187.765.306 1.608.338 2.5H8.5zm-1 1H4.51a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm1 2.5V8.5h2.99a12.495 12.495 0 0 1-.337 2.5H8.5zm-1 1H5.145c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12zm-2.173 2.472a6.695 6.695 0 0 1-.597-.933A9.267 9.267 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM1.674 11H3.82a13.651 13.651 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm8.999 3.472A7.024 7.024 0 0 0 13.745 12h-1.834a9.278 9.278 0 0 1-.641 1.539 6.688 6.688 0 0 1-.597.933zM10.855 12H8.5v2.923c.67-.204 1.335-.82 1.887-1.855A7.98 7.98 0 0 0 10.855 12zm1.325-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm.312-3.5h2.49a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.91 4a9.277 9.277 0 0 0-.64-1.539 6.692 6.692 0 0 0-.597-.933A7.024 7.024 0 0 1 13.745 4h-1.834zm-1.055 0H8.5V1.077c.67.204 1.335.82 1.887 1.855.173.324.33.682.468 1.068z"/>
        </svg>';
        }

        if ($info['duration'] >= 3600) {
          $info['duration'] = strftime("%H:%M:%S", $info['duration']);
        } else {
          $info['duration'] = strftime("%M:%S", $info['duration']);
        }

        $thumbnail_basename = $InfoVideo->ThumbnailBasename();
        if (is_file($config['YOUTUBR_DL_WL'] . '/' . $filename . '/' .  $name . '/' . $name . '.' . $thumbnail_basename)) {
          $info['thumbnail'] = 'f.php?' . $filename . '/' . $name . '.' . $thumbnail_basename;
        }

        if ($info['thumbnail']) {
          $poster = '<img src="' . htmlspecialchars($info['thumbnail']) . '" loading="lazy" alt="miniature" width="100%">';
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