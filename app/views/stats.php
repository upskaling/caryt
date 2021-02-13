<?php $title = 'Statistiques'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">
    <div class="card card-body">

        <h2>info</h2>
        <p>dans la file d'attente</p>

        <table class="table table-striped">
            <caption>Répartition des articles</caption>
            <tbody>
                <tr>
                    <td>total</td>
                    <td><?= $total_count_url ?></td>
                </tr>
                <tr>
                    <td>non lus</td>
                    <td><?= $count_entry; ?></td>
                </tr>
                <tr>
                    <td>lus</td>
                    <td><?= $count_entry_lus ?></td>
                </tr>
            </tbody>
        </table>

        <?= $count_jour ?> jour</br>
        <?= $count_entry / $count_jour ?> nombre de vidéos par jour</br>

    </div>
    <div class="card card-body">
        <h2>Les dix plus gros flux dans la file d'attente</h2>
        <table class="table table-striped">
            <caption> Les dix plus gros flux dans la file d'attente</caption>
            <thead>
                <tr>
                    <td>uploader</td>
                    <td>Nombre d’articles</td>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($flux as $key => $value) :
                    $uploader_url = $feedparser->get_info_feed($value->uploader_url);
                ?>
                    <tr>
                        <td><?= htmlentities($uploader_url->title) ?></td>
                        <td><?= $value->count_uploader ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card card-body">
        <h2>Nombre d’articles par jour dans la file d'attente</h2>
        <table class="table table-striped">
            <caption>Nombre d’articles par jour dans la file d'attente</caption>
            <thead>
                <tr>
                    <td>jour</td>
                    <td>articles</td>
                </tr>
            </thead>
            <tbody>
                <?php

                $i = 0;
                foreach ($jour as $key => $value) :
                    $i += 1;
                    if ($i > 10) {
                        break;
                    }
                ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td><?= $value ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <div class="card card-body">
        <h2>Nombre d'articles dans les catégories</h2>
        <table class="table table-striped">
            <caption>Nombre d'articles dans les catégories</caption>
            <thead>
                <tr>
                    <td>catégories</td>
                    <td>articles</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $key => $value) : ?>
                    <tr>
                        <td><?= $value->categories ?></td>
                        <td><?= $value->count ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>

        </table>
    </div>
</div>

<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>