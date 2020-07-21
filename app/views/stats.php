<?php #UTF-8

require_once  __DIR__ . '/../Models/Entry.php';
require_once __DIR__ . '/../Models/Feedparser.php';

$feedparser = new Feedparser($pdo);

$flux = $pdo->query('SELECT "uploader_url", COUNT("uploader_url") AS "count_uploader"
FROM "admin_entry"
GROUP BY "uploader_url"
ORDER BY COUNT("uploader_url") DESC
LIMIT 10')->fetchAll();

$jour = [];
foreach ($pdo->query('SELECT "update", "rowid" FROM "admin_entry"')->fetchAll() as $key => $value) {

    $update = date('Y-m-d', $value->update);
    if (!isset($jour[$update])) {
        $jour[$update] = 1;
    } else {
        $jour[$update] += 1;
    }
}
arsort($jour);

$count_entry = $pdo->query('SELECT COUNT("url") AS "count_url"
FROM "admin_entry"
WHERE "is_read" IS NULL ORDER BY "update"
LIMIT 1')->fetch()->count_url;
$count_jour = count($jour);
?>


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
                    <td><?= $pdo->query('SELECT COUNT("url") AS "count_url"
                    FROM "admin_entry"
                    LIMIT 1')->fetch()->count_url; ?></td>
                </tr>
                <tr>
                    <td>non lus</td>
                    <td><?= $count_entry; ?></td>
                </tr>
                <tr>
                    <td>lus</td>
                    <td><?= $pdo->query('SELECT COUNT("url") AS "count_url"
                    FROM "admin_entry"
                    WHERE "is_read" IS NOT NULL ORDER BY "update"
                    LIMIT 1')->fetch()->count_url; ?></td>
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
</div>

<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>