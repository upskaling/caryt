<?php

if (is_file('../data/config.php')) {
    require '../data/config.php';
} else {
    require '../config.default.php';
}

$get_a = $_GET['a'] ?? '';
if ($get_a == 'reading') {

    $config['quality_default'] = (int) $_POST['quality_default'];
    $config['ttl_default'] = (int) $_POST['ttl_default'];
    $config['errorspass'] = (int) $_POST['errorspass'];
    $config['items_per_page'] = (int) $_POST['items_per_page'];
    $config['max_downloads'] = (int) $_POST['max_downloads'];
    $config['YOUTUBR_DL_WL_purge_days'] = (int) $_POST['YOUTUBR_DL_WL_purge_days'];
    $config['url'] = (string) $_POST['url'];

    file_put_contents('../data/config.php', '<?php $config = ' . var_export($config, true) . ';');
}

$get_a = $_GET['a'] ?? '';
if ($get_a == 'profile') {

    $config['login'][$_SERVER['PHP_AUTH_USER']] = password_hash($_POST['newPasswordPlain'], PASSWORD_ARGON2I);

    file_put_contents('../data/config.php', '<?php $config = ' . var_export($config, true) . ';');
}

?>
<?php $title = 'configure'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <?php if ($get_a == 'reading') : ?>
                <div class="alert alert-success" role="alert">
                    modifications enregistrées
                </div>
            <?php endif; ?>

            <form action="./?c=configure&a=reading" method="post">
                <div class="form-group">
                    <label for="errorspass">nombre de tentatives de téléchargement</label>
                    <input type="number" id="errorspass" name="errorspass" value="<?= $config['errorspass'] ?>" min="0" max="50" data-leave-validation="3">
                </div>
                <div class="form-group">
                    <label for="items_per_page">nombre d'articles par page</label>
                    <input type="number" id="items_per_page" name="items_per_page" value="<?= $config['items_per_page'] ?>" min="5" max="500" data-leave-validation="20">
                </div>
                <div class="form-group">
                    <label for="max_downloads">nombre max de téléchargement</label>
                    <input type="number" id="max_downloads" name="max_downloads" value="<?= $config['max_downloads'] ?>" min="0" max="500" data-leave-validation="5">
                </div>
                <div class="form-group">
                    <label for="YOUTUBR_DL_WL_purge_days">nombre de jours après lesquels il faut supprimer les media</label>
                    <input type="number" id="YOUTUBR_DL_WL_purge_days" name="YOUTUBR_DL_WL_purge_days" value="<?= $config['YOUTUBR_DL_WL_purge_days'] ?>" min="0" data-leave-validation="6">
                </div>
                <div class="form-group">
                    <label for="url">URL de l'instance (important pour le flux RSS)</label>
                    <input type="url" id="url" name="url" value="<?= $config['url'] ?>">
                </div>

                <div class="form-group row">
                    <label class="col-md-auto col-form-label" for="ttl_default">Ne pas automatiquement rafraîchir plus souvent que</label>
                    <div>
                        <select class="form-control" name="ttl_default" id="ttl_default" required="required" data-leave-validation="<?= $config['ttl_default'] ?>">
                            <?php foreach ([
                                1200 => '20min', 1500 => '25min', 1800 => '30min', 2700 => '45min',
                                3600 => '1h', 5400 => '1.5h', 7200 => '2h', 10800 => '3h', 14400 => '4h', 18800 => '5h', 21600 => '6h', 25200 => '7h', 28800 => '8h',
                                36000 => '10h', 43200 => '12h', 64800 => '18h',
                                86400 => '1d', 129600 => '1.5d', 172800 => '2d', 259200 => '3d', 345600 => '4d', 432000 => '5d', 518400 => '6d',
                                604800 => '1wk'
                            ] as $key => $value) : ?>
                                <option value="<?= $key ?>" <?= ($config['ttl_default'] == $key ? 'selected' : '') ?>><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>(Par défaut)
                        <label for="mute">
                            <input type="checkbox" name="mute" id="mute" value="1" <?= (empty($feed->mute)) ?: 'checked' ?>>
                            muet
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-auto col-form-label" for="ttl_default">Qualité</label>
                    <div>
                        <select class="form-control" name="quality_default" id="quality_default" required="required" data-leave-validation="<?= $config['quality_default'] ?>">
                            <?php foreach ([
                                144 => '144p', 240 => '240p',
                                360 => '360p', 480 => '480p',
                                720 => '720p', 1080 => '1080p'
                            ] as $key => $value) : ?>
                                <option value="<?= $key ?>" <?= ($config['quality_default'] == $key ? 'selected' : '') ?>><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class=" text-center">
                    <button type="submit" class="btn btn-primary">Valider</button>
                    <button type="reset" class="btn btn-secondary">Annuler</button>
                </div>

            </form>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <?php if ($get_a == 'profile') : ?>
                <div class="alert alert-success" role="alert">
                    nouveau mot de passe enregistrées
                </div>
            <?php endif; ?>

            <form action="./?c=configure&a=profile" method="post">
                <div class="form-group">
                    <label for="newPasswordPlain">Mot de passe<br><small>(pour connexion par formulaire)</small></label>
                    <input type="password" id="newPasswordPlain" name="newPasswordPlain" autocomplete="new-password">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>