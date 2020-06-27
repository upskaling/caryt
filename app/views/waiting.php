<?php #UTF-8

require_once __DIR__ . '/../Models/Waiting_list.php';
$waiting_list = new Waiting_list($config['waiting_list']);

$delete = $_POST["delete"] ?? '';
if ($delete) {
  $waiting_list->delete($delete - 1);
  $waiting_list->write();
}

$let_waiting = sizeof($waiting_list->videos);

$page = $_GET['page'] ?? 0;

if ($page) {
  $page_start = $config['items_per_page'] * $page;
  $page_max = $page_start + $config['items_per_page'];
} else {
  $page_start = 0;
  $page_max = $config['items_per_page'];
}

$download = $_POST["download"] ?? '';
if (!empty($download)) {
  $waiting_list->download(
    $download,
    $config['YOUTUBR_DL_WL'] . '/' . date("Y-m-d", time()) . '/%(id)s/%(id)s.%(ext)s',
    $config['cookiefile'],
    $config['download-archive']
  );
  $waiting_list->write();
}

?>
<?php $title = '(' . sizeof($waiting_list->videos) . ') Liste des vidéos en attente'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>
<div>

  <div class="container py-4">

    <?php if ($download) : ?>
      <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($download) ?> download
      </div>
    <?php endif; ?>

    <?php if ($delete) : ?>
      <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($delete) ?> a été supprimé avec succès
      </div>
    <?php endif; ?>

    <div class="pagination justify-content-center">
      <a href="./?c=add_url" class="btn btn-light btn-nt btn-sm">Ajout d'une URL</a>
    </div>

    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <?php if ($page >= 1) : ?>
          <li class="page-item">
            <a class="page-link" href="?c=waiting&page=<?= $page - 1 ?>">⬅️</a>
          </li>
        <?php endif; ?>
        <li class="page-item">
          <p class="page-link"><?= $page ?: '1' ?></p>
        </li>
        <li class="page-item">
          <p class="page-link"><?= ceil($let_waiting / $config['items_per_page']) - 1 ?></p>
        </li>
        <?php if (!($page_start + $config['items_per_page'] > $let_waiting)) : ?>
          <li class="page-item">
            <a class="page-link" href="?c=waiting&page=<?= $page + 1 ?>">➡️</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>

    <!-- link list -->
    <?php for ($i = $page_start; $i < $page_max; $i++) {
      if (empty($waiting_list->videos[$i])) {
        continue;
      } ?>
      <div class="card mb-4 shadow-sm">
        <?php if (!empty($waiting_list->videos[$i]['pass'])) : ?>
          <div class="card-body border-warning border-left">
          <?php else : ?>
            <div class="card-body">
            <?php endif; ?>
            <div>
              <?php if (!empty($waiting_list->videos[$i]['pass'])) : ?>
                <div class="alert alert-warning" role="alert">
                  pass: <?= $waiting_list->videos[$i]['pass'] ?>
                </div>
              <?php endif; ?>


              <a class="card-link" href="<?= ($waiting_list->videos[$i]['url'] ?? '000') ?> ">
                <?= ($waiting_list->videos[$i]["title"] ?? '000') ?></a>
              <form action="?c=waiting&page=<?= $page ?>" method="post">
                <button class="close" type="submit" name="delete" value="<?= $i + 1 ?>">
                  <span aria-hidden="true">
                    <svg class="bi bi-trash-fill text-danger" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z" />
                    </svg>
                  </span>
                </button>
                <button class="close" type="submit" name="download" value="<?= $i + 1 ?>">
                  <span class="text-muted">
                    <svg class="bi bi-download" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8z" />
                      <path fill-rule="evenodd" d="M5 7.5a.5.5 0 0 1 .707 0L8 9.793 10.293 7.5a.5.5 0 1 1 .707.707l-2.646 2.647a.5.5 0 0 1-.708 0L5 8.207A.5.5 0 0 1 5 7.5z" />
                      <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 1z" />
                    </svg>
                  </span>
                </button>
              </form>
            </div>

            <a href="<?= ($waiting_list->videos[$i]["uploader-url"] ?? '000') ?>">
              <small class="text-muted"><?= ($waiting_list->videos[$i]["uploader"] ?? '000') ?> </small>
            </a>


            </div>
          </div>
        <?php } ?>
      </div>

  </div>
</div>


<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>