<?php $title = '(' . sizeof($feed) . ') Liste des chaînes'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>


<div class="container py-4">
  <div class="container mb-4">
    <form action="?c=feed&a=add" method="post" class="form-inline justify-content-center my-2 my-lg-0">
      <input class="form-control" style="width: 75%" type="url" name="add_url" placeholder="Ajouter un flux RSS" aria-label="Ajouter un flux RSS" />
      <input class="btn btn-primary my-2 my-sm-0" type="submit" value="✚"></input>
      <a class="btn btn-primary" href="./?c=importExport">import/export</a>
    </form>
  </div>

  <div class="card mb-4">
    <div class="card-header text-center h5">
      <label for="new-category">Ajouter une catégorie</label>
    </div>
    <form action="./?c=category&a=create" method="post">

      <div class="text-center m-sm-3 m-2 justify-content-center">

        <div class="form-group">
          <input placeholder="Nouvelle catégorie" type="text" class="form-control" name="new-category" id="new-category">
        </div>

        <button type="submit" class="btn btn-primary">Valider</button>

      </div>

    </form>
  </div>

  <?php if ($_GET['error'] ?? 0 > 0) : ?>
    <a href="./?c=channels">Montrer tous les flux</a>
  <?php else : ?>
    <a href="./?c=channels&error=1">Montrer seulement les flux en erreur</a>
  <?php endif; ?>

  <?php foreach ($category as $category_value) : ?>
    <div class="card mb-2">
      <ul class="list-group list-group-flush">

        <li class="list-group-item list-group-item-secondary">

          <a href="./?c=category&id=<?= $category_value->category ?>">
            <svg aria-hidden="true" class="bi bi-gear-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 0 0-5.86 2.929 2.929 0 0 0 0 5.858z" />
            </svg>
          </a>

          <samp class="h5"><?= htmlspecialchars($category_value->name) ?>
          </samp>

        </li>

        <?php foreach ($feedparser->FunctionFeedGet($category_value->category, $_GET['error'] ?? 0) as $value) : ?>

          <li class="list-group-item">
            <?php if (!empty($value->status)) : ?>
              <div class="alert alert-warning" role="alert">
                ⚠ <?= htmlspecialchars($value->status) ?>
              </div>
            <?php endif; ?>

            <div id="i<?= $value->rowid ?>" class="row">

              <div class="col">

                <?php if (!empty($value->mute)) : ?>
                  <svg aria-hidden="true" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-volume-mute" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06zM6 5.04L4.312 6.39A.5.5 0 0 1 4 6.5H2v3h2a.5.5 0 0 1 .312.11L6 10.96V5.04zm7.854.606a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708l4-4a.5.5 0 0 1 .708 0z" />
                    <path fill-rule="evenodd" d="M9.146 5.646a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0z" />
                  </svg>
                <?php endif; ?>

                <a class="card-link" href="?c=feed&id=<?= $value->rowid ?>">
                  <svg aria-hidden="true" class="bi bi-gear-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 0 0-5.86 2.929 2.929 0 0 0 0 5.858z" />
                  </svg>
                </a>

                <?php if (strpos($value->xmlurl, 'www.youtube.com')) : ?>
                  <img src="youtube-logo.png" alt="YouTube" loading="lazy" width="16" height="16">
                <?php else : ?>
                  <svg width="16" height="16" viewBox="0 0 16 16" class="bi bi-globe" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M1.018 7.5h2.49c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5zM2.255 4H4.09a9.266 9.266 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.024 7.024 0 0 0 2.255 4zM8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm-.5 1.077c-.67.204-1.335.82-1.887 1.855-.173.324-.33.682-.468 1.068H7.5V1.077zM7.5 5H4.847a12.5 12.5 0 0 0-.338 2.5H7.5V5zm1 2.5V5h2.653c.187.765.306 1.608.338 2.5H8.5zm-1 1H4.51a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm1 2.5V8.5h2.99a12.495 12.495 0 0 1-.337 2.5H8.5zm-1 1H5.145c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12zm-2.173 2.472a6.695 6.695 0 0 1-.597-.933A9.267 9.267 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM1.674 11H3.82a13.651 13.651 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm8.999 3.472A7.024 7.024 0 0 0 13.745 12h-1.834a9.278 9.278 0 0 1-.641 1.539 6.688 6.688 0 0 1-.597.933zM10.855 12H8.5v2.923c.67-.204 1.335-.82 1.887-1.855A7.98 7.98 0 0 0 10.855 12zm1.325-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm.312-3.5h2.49a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.91 4a9.277 9.277 0 0 0-.64-1.539 6.692 6.692 0 0 0-.597-.933A7.024 7.024 0 0 1 13.745 4h-1.834zm-1.055 0H8.5V1.077c.67.204 1.335.82 1.887 1.855.173.324.33.682.468 1.068z" />
                  </svg>
                <?php endif; ?>

                <a class="card-link" target="_blank" rel="noreferrer" href="<?= isset($value->siteurl) ? $value->siteurl : $value->xmlurl  ?>">
                  <?= $value->title ?>
                </a>
              </div>

              <div class="col-md-auto">
                <small class="text-muted">
                  <?= date('Y-m-d H:i:s', $value->update) ?: 'off' ?>
                </small>
              </div>

            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  <?php
  endforeach;
  ?>
</div>

<?php include(__DIR__ . '/../template/footer.php'); ?>
<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>