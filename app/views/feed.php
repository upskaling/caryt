<?php #UTF-8

require_once __DIR__ . '/../Models/Feedparser.php';
$feedparser = new Feedparser($GLOBALS['config']['feed']);

$add_url = $_POST['add_url'] ?? '';
$resulta = null;
if ($add_url) {
  $feedparser->add_feeds(
    htmlspecialchars($add_url),
    $resulta,
    $_GET['id']
  );
  $feedparser->write();
}

$get_id = htmlspecialchars($_GET['id']);
$id = $feedparser->feeds[$get_id];

$xmlUrl = $_POST["xmlUrl"] ?? '';
if ($xmlUrl) {
  $feedparser->feeds[$get_id]['xmlUrl'] = $_POST['xmlUrl'];
  $feedparser->feeds[$get_id]['siteUrl'] = $_POST['siteUrl'];
  $feedparser->feeds[$get_id]['title'] = $_POST['title'];
  $feedparser->feeds[$get_id]['update_interval'] = $_POST['update_interval'];
  $feedparser->write();
  header('Location: ?c=channels#' . $_GET['id']);
}

$delete = $_POST['delete'] ?? '';
if ($delete) {
  $feedparser->delete($get_id);
  $feedparser->write();
  // header('Location: ?c=channels');
}

$get_a = $_GET['a'] ?? '';
if ($get_a == 'actualize') {
  $feedparser->refresh_a_stream($get_id);
  $feedparser->write();
}

?>

<?php $title = 'Modifier le feed'; ?>
<?php ob_start(); ?>

<div class="container py-4">
  <div class="card card-body">

    <?php if ($resulta === 0) : ?>
      <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_POST["add_url"]) ?>
      </div>
    <?php elseif ($resulta) : ?>
      <div class="alert alert-warning" role="alert">
        n'a pas été ajouté :
        <?= $resulta ?>
      </div>
    <?php endif; ?>

    <?php if ($delete) : ?>
      <div class="alert alert-success" role="alert">
        suppression<?= $feedparser->feeds[$get_id]['status'] ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($feedparser->feeds[$get_id]['status'])) : ?>
      <div class="alert alert-warning" role="alert">
        ⚠ <?= $feedparser->feeds[$get_id]['status'] ?>
      </div>
    <?php endif; ?>

    <form action="" method="post">
      <div class="form-group">
        <label for="xmlUrl">URL du flux</label>
        <input type="url" class="form-control" name="xmlUrl" id="xmlUrl" value="<?= $id['xmlUrl'] ?>">
      </div>
      <div class="form-group">
        <label for="siteUrl">URL du site</label>
        <input type="url" class="form-control" name="siteUrl" id="siteUrl" value="<?= $id['siteUrl'] ?>">
      </div>
      <div class="form-group">
        <label for="title">Titre</label>
        <input class="form-control" name="title" id="title" value="<?= $id['title'] ?>">
      </div>
      <div class="form-group">
        <label>Ne pas automatiquement rafraîchir plus souvent que</label>
        <input name="update_interval" value="<?= $id['update_interval'] ?>">seconde<br>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Appliquer les changements</button>
        <button type="submit" class="btn btn-danger" value="TRUE" name="delete">Supprimer</button>
      </div>
      <div class="text-center">
        <a class="btn btn-primary" href="./?c=feed&a=actualize&id=<?= $get_id ?>">
          <svg class="bi bi-arrow-repeat" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M2.854 7.146a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L2.5 8.207l1.646 1.647a.5.5 0 0 0 .708-.708l-2-2zm13-1a.5.5 0 0 0-.708 0L13.5 7.793l-1.646-1.647a.5.5 0 0 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0 0-.708z" />
            <path fill-rule="evenodd" d="M8 3a4.995 4.995 0 0 0-4.192 2.273.5.5 0 0 1-.837-.546A6 6 0 0 1 14 8a.5.5 0 0 1-1.001 0 5 5 0 0 0-5-5zM2.5 7.5A.5.5 0 0 1 3 8a5 5 0 0 0 9.192 2.727.5.5 0 1 1 .837.546A6 6 0 0 1 2 8a.5.5 0 0 1 .501-.5z" />
          </svg>
          Actualiser
        </a>
      </div>
    </form>
  </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>