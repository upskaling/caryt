<?php #UTF-8

require_once __DIR__ . '/../Models/Feedparser.php';
$feedparser = new Feedparser($config['feed']);

$error = $_GET['error'] ?? '';
if ($error > 0) {
  foreach ($feedparser->feeds as $kes => $value) {
    if (!empty($value['status'])) {
      $feeds[$kes] = $value;
    }
  }
  $feedparser->feeds = $feeds;
}

$let_feeds = sizeof($feedparser->feeds);
?>


<?php $title = '(' . $let_feeds . ') Liste des chaînes'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>
<div>

  <div class="container py-4">

    <form action="?c=feed" method="post" class="form-inline">
      <div class="container">
        <div class="form-group mx-sm-3 mb-2 justify-content-center">
          <input type="url" name="add_url" class="long form-control" placeholder="Ajouter un flux RSS" />
          <input type="submit" value="✚" class="btn btn-primary"></input>
        </div>
      </div>
    </form>

    <?php if ($error > 0) : ?>
      <a href="./?c=channels">Montrer tous les flux</a>
    <?php else : ?>
      <a href="./?c=channels&error=1">Montrer seulement les flux en erreur</a>
    <?php endif; ?>

    <?php foreach ($feedparser->feeds as $key => $value) : ?>
      <div class="card mb-4 shadow-sm" id="<?= $key ?>">
        <div class="card-body">
          <div>

            <?php if (!empty($value['status'])) : ?>
              <div class="alert alert-warning" role="alert">
                ⚠ <?= $value['status'] ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($value['mute'])) : ?>
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-volume-mute" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06zM6 5.04L4.312 6.39A.5.5 0 0 1 4 6.5H2v3h2a.5.5 0 0 1 .312.11L6 10.96V5.04zm7.854.606a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708l4-4a.5.5 0 0 1 .708 0z" />
                <path fill-rule="evenodd" d="M9.146 5.646a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0z" />
              </svg>
            <?php endif; ?>

            <a class="card-link" href="?c=feed&id=<?= $key ?>">
              <svg class="bi bi-gear-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 0 0-5.86 2.929 2.929 0 0 0 0 5.858z" />
              </svg>
            </a>

            <?php if (strpos($value['xmlUrl'], 'www.youtube.com')) : ?>
              <img src="youtube-logo.png" loading="lazy" title="YouTube" width="16" height="16">
            <?php else : ?>
              <img src="favicon.png" loading="lazy" title="local" width="16" height="16">
            <?php endif; ?>

            <a class="card-link" href="<?= isset($value['siteUrl']) ? $value['siteUrl'] : $value['xmlUrl']  ?>">
              <?= $value['title'] ?>
            </a>

            <span class="card-subtitle mb-2 text-muted">
              <br><?= $value['update'] ?: 'off' ?>
            </span>

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