<?php #UTF-8

require_once  __DIR__ . '/../Models/Waiting_list.php';
$waiting_list = new Waiting_list($config['waiting_list']);

$flux = [];
$jour = [];
foreach ($waiting_list->videos as $value) {
    $uploader = htmlspecialchars($value['uploader']);
    if (in_array($uploader, $flux)) {
        $flux[$uploader] = 1;
    } else {
        $flux[$uploader] += 1;
    }

    $update = date('Y-m-d', strtotime($value['update']));
    if (in_array($update, $jour)) {
        $jour[$update] = 1;
    } else {
        $jour[$update] += 1;
    }
}

arsort($flux);
arsort($jour);

$count_waiting_list = count($waiting_list->videos);
$count_jour = count($jour);
?>


<?php $title = 'Statistiques'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">
    <div class="card card-body">

        <h2>info</h2>

        <table class="table table-striped">
            <tr>
                <td><?= $count_waiting_list ?> vidéo</td>
            </tr>
            <tr>
                <td><?= $count_jour ?> jour</td>
            </tr>
            <tr>
                <td><?= $count_waiting_list / $count_jour ?> par jour</td>
            </tr>
        </table>
    </div>
    <div class="card card-body">
        <h2>Les dix plus gros flux</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td scope="col">uploader</td>
                    <td scope="col">Nombre d’articles</td>
                </tr>
            </thead>
            <tbody>

                <?php
                $i = 0;
                foreach ($flux as $key => $value) :
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
        <h2>Nombre d’articles par jour</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td scope="col">jour</td>
                    <td scope="col"> articles</td>
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