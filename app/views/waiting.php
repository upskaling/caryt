<?php $title = '(' . $let_waiting . ') Liste des vidéos en attente'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">

  <?php if ($status) : ?>
    <div class="alert alert-success" role="alert">
      <?= $status ?>
    </div>
  <?php endif; ?>

  <div class="pagination justify-content-center">
    <a href="./?c=add_url" class="btn btn-light btn-nt btn-sm">Ajout d'une URL</a>
  </div>

  <div class="pagination justify-content-center">
    <a href="<?= addUrlParam(array('state' => 2)) ?>" class="btn btn-light btn-nt btn-sm <?= ($_GET['state'] ?? '2') !== '2' ?: 'active' ?>">
      <svg class="text-danger" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z" />
      </svg>
    </a>
    <a href="<?= addUrlParam(array('state' => 1)) ?>" class="btn btn-light btn-nt btn-sm <?= ($_GET['state'] ?? '') !== '1' ?: 'active' ?>">
      <svg class="danger" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope-open" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.818l5.724 3.465L8 8.917l1.276.766L15 6.218V5.4a1 1 0 0 0-.53-.882l-6-3.2zM15 7.388l-4.754 2.877L15 13.117v-5.73zm-.035 6.874L8 10.083l-6.965 4.18A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738zM1 13.117l4.754-2.852L1 7.387v5.73zM7.059.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765l6-3.2z" />
      </svg>
    </a>
  </div>

  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <?php if ($page >= 1) : ?>
        <li class="page-item">
          <a class="page-link" href="<?= addUrlParam(array('page' => $page - 1)) ?>">⬅️</a>
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
          <a class="page-link" href="<?= addUrlParam(array('page' => $page + 1)) ?>">➡️</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- link list -->
  <?php foreach ($entry->pager($page_start, $page_max, $_GET['state'] ?? 2) as $key => $value) : ?>
    <div class="card mb-4 shadow-sm">
      <div class="justify-content-between <?= !empty($value->is_read) ?: 'border-danger border-left' ?>">

        <div class="card-body row">
          <div class="col">
            <?php if (empty($value->is_read)) : ?>
              <form action="./?c=entry&a=read" method="post">
                <button type="submit" class="btn btn-sm" name="id" value="<?= $value->rowid ?>">
                  <svg class="text-danger" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z" />
                  </svg>
                </button>
                <a class="card-link text-break" target="_blank" rel="noreferrer" href="<?= ($value->url ?: '#') ?> ">
                  <?= htmlspecialchars($value->title) ?: 'title' ?>
                </a>
              </form>
            <?php else : ?>
              <form action="./?c=entry&a=read&is_read=1" method="post">
                <button type="submit" class="btn btn-sm" name="id" value="<?= $value->rowid ?>">
                  <svg class="danger" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope-open" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.818l5.724 3.465L8 8.917l1.276.766L15 6.218V5.4a1 1 0 0 0-.53-.882l-6-3.2zM15 7.388l-4.754 2.877L15 13.117v-5.73zm-.035 6.874L8 10.083l-6.965 4.18A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738zM1 13.117l4.754-2.852L1 7.387v5.73zM7.059.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765l6-3.2z" />
                  </svg>
                </button>
                <a class="card-link text-break" target="_blank" rel="noreferrer" href="<?= ($value->url ?: '#') ?> ">
                  <?= htmlspecialchars($value->title) ?: 'title' ?>
                </a>
              </form>
            <?php endif; ?>


            <?php if (!empty($value->pass)) : ?>
              <form action="./?c=entry&a=pass" method="post">
                <div class="alert alert-warning" role="alert">
                  pass: <?= $value->pass ?>
                  <button type="submit" class="close" name="id" value="<?= $value->rowid ?>">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </form>
            <?php endif; ?>

            <?php
            if (isset($value->uploader_url)) :
              $uploader_url = $feedparser->get_info_feed($value->uploader_url);
            ?>
              <a href="./?c=feed&id=<?= $uploader_url->rowid ?>">
                <small class="text-muted"><?= $uploader_url->title ? htmlspecialchars($uploader_url->title) : 'uploader' ?></small>
              </a>
            <?php endif; ?>

          </div>

          <?php if (!empty($value->thumbnail)) : ?>
            <div class="col-auto">
              <img src="<?= filter_var($value->thumbnail, FILTER_VALIDATE_URL) ?>" referrerpolicy="no-referrer" class="card-img" loading="lazy" alt="thumbnail" height="90">
            </div>
          <?php endif; ?>

        </div>



        <div class="card-footer">
          <form action="?c=waiting&page=<?= $page ?>" method="post">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
              <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
            </svg>
            <small class="text-muted"><?= date("Y-m-d H:i:s", $value->get_date ?: $value->update) ?: 'off' ?></small>
            <br>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
              <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
              <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z" />
            </svg>
            <a class="card-link" target="_blank" rel="noreferrer" href="<?= ($value->url ?: '#') ?> ">
              <small class="text-muted"><?= $value->url ?></small>
            </a>
            <button class="close" type="submit" name="download" value="<?= $value->rowid ?>">
              <span class="text-muted mr-2">
                <svg alt="supprimer" role="presentation" class="bi bi-download" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M.5 8a.5.5 0 0 1 .5.5V12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8.5A.5.5 0 0 1 .5 8z" />
                  <path fill-rule="evenodd" d="M5 7.5a.5.5 0 0 1 .707 0L8 9.793 10.293 7.5a.5.5 0 1 1 .707.707l-2.646 2.647a.5.5 0 0 1-.708 0L5 8.207A.5.5 0 0 1 5 7.5z" />
                  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 1z" />
                </svg>
              </span>
            </button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>