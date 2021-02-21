<?php $title = 'update'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">

    <h2>YouTube-DL</h2>
    <?php if (!empty($version)) : ?>
        <div class="alert alert-success" role="alert">
            Votre version de YouTube-DL <?= $version ?>
        </div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            YouTube-DL n'est pas installé
        </div>
    <?php endif; ?>

    <?php if (!empty($update)) : ?>
        <pre>
            <?= $update ?>
        </pre>
    <?php endif; ?>

    <a href="./?c=update&a=apply" class="btn btn-secondary">
        Mise à jour
    </a>

    <hr>

    <h2>rss</h2>
    <a href="./?c=update&a=rss_update" class="btn btn-secondary">
        Mise à jour
    </a>

    <hr>

    <h2>Rafraîchir les flux</h2>
    <br>
    <?php if (!empty($flux_update)) : ?>
        <lu>
            <?php foreach ($flux_update as $key => $value) : ?>
                <li class="list-group-item">
                    <div>
                        <a class="card-link" href="?c=feed&id=<?= $value['id'] ?>">
                            <svg aria-hidden="true" class="bi bi-gear-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 0 0-5.86 2.929 2.929 0 0 0 0 5.858z"></path>
                            </svg>
                        </a>
                        <a class="card-link" target="_blank" rel="noreferrer" href="<?= $value['xmlurl'] ?>">
                            <?= $value['title'] ?>
                        </a>
                        <?php if (!empty($value['results'])) : ?>
                            <span class="alert alert-warning" role="alert">
                                ⚠ <?= $value['results'] ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </lu>
        <br>
    <?php endif; ?>
    <a href="./?c=update&a=flux_update" class="btn btn-secondary">
        Rafraîchir les <?= $feedparser->count_update()->COUNT ?> flux
    </a>


</div>


<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>