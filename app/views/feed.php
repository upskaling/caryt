<?php #UTF-8

require_once __DIR__ . '/../Models/Feedparser.php';
$feedparser = new Feedparser($config['feed']);

(int) $Get_id = $_GET['id'] ?? '';
$add_url = filter_var($_POST['add_url'] ?? '', FILTER_VALIDATE_URL);
$resulta = null;
if ($add_url) {
  $feedparser->add_feeds(
    htmlspecialchars($add_url),
    $resulta,
    $Get_id
  );
  $feedparser->write();
}

$get_id = htmlspecialchars($Get_id);
$id = $feedparser->feeds[$get_id];

(string) $xmlUrl = $_POST["xmlUrl"] ?? '';
if ($xmlUrl) {
  $feedparser->feeds[$get_id]['xmlUrl'] = filter_var($_POST['xmlUrl'], FILTER_VALIDATE_URL);
  $feedparser->feeds[$get_id]['siteUrl'] = filter_var($_POST['siteUrl'], FILTER_VALIDATE_URL);
  $feedparser->feeds[$get_id]['title'] = (string) $_POST['title'];
  $feedparser->feeds[$get_id]['update_interval'] = (int) $_POST['update_interval'];
  if (empty($_POST['mute'])) {
    unset($feedparser->feeds[$get_id]['mute']);
  } else {
    $feedparser->feeds[$get_id]['mute'] = 1;
  }
  $feedparser->write();
  header('Location: ?c=channels#i' . $Get_id);
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

    <h1><?= $id['title'] ?? null ?></h1>

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

    <form action="#" method="post">

      <div class="form-group row">
        <label class="col-md-auto col-form-label" for="title">Titre</label>
        <div class="col-md">
          <input class="form-control" type="text" name="title" id="title" value="<?= $id['title'] ?? null ?>">
        </div>
      </div>

      <hr>

      <div class="form-group row">
        <label class="col-md-auto col-form-label" for="xmlUrl">URL du flux</label>
        <div class="col-md">
          <input type="url" class="form-control" name="xmlUrl" id="xmlUrl" value="<?= $id['xmlUrl'] ?? null ?>">
        </div>
        <a class="col-auto btn btn-primary" target="_blank" rel="noreferrer" href="<?= $id['xmlUrl'] ?? null ?>">
          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
            <path fill-rule="evenodd" d="M10.5 5h-4a.5.5 0 0 0 0 1h2.793l-4.147 4.146a.5.5 0 0 0 .708.708L10 6.707V9.5a.5.5 0 0 0 1 0v-4a.5.5 0 0 0-.5-.5z" />
          </svg>
        </a>
      </div>

      <hr>

      <div class="form-group row">
        <label class="col-md-auto col-form-label" for="siteUrl">URL du site</label>
        <div class="col-md">
          <input type="url" class="form-control" name="siteUrl" id="siteUrl" value="<?= $id['siteUrl'] ?? null ?>">
        </div>
        <a class="col-auto btn btn-primary" target="_blank" rel="noreferrer" href="<?= $id['siteUrl'] ?? null ?>">
          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-right-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
            <path fill-rule="evenodd" d="M10.5 5h-4a.5.5 0 0 0 0 1h2.793l-4.147 4.146a.5.5 0 0 0 .708.708L10 6.707V9.5a.5.5 0 0 0 1 0v-4a.5.5 0 0 0-.5-.5z" />
          </svg>
        </a>
      </div>

      <hr>

      <div class="form-group row">
        <label class="col-md-auto col-form-label" for="ttl">Ne pas automatiquement rafraîchir plus souvent que</label>
        <div class="col-md">
          <input name="update_interval" type="number" id="ttl" value="<?= $id['update_interval'] ?? null ?>">seconde<br>
          <label for="mute">
            <input type="checkbox" name="mute" id="mute" value="1" <?= (empty($id['mute'])) ?: 'checked' ?>>
            muet
          </label>
        </div>
      </div>

      <hr>

      <div class="text-center">
        <button type="submit" class="btn btn-primary">Appliquer les changements</button>
        <button type="submit" class="btn btn-danger" value="TRUE" name="delete">Supprimer</button>
      </div>

      <hr>

      <div class="text-center">
        <a class="btn btn-primary" href="./?c=feed&a=actualize&id=<?= $get_id ?>">
          <svg aria-hidden="true" class="bi bi-arrow-repeat" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
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