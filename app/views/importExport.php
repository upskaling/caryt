<?php $title = 'importer / exporte'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">
    <div class="card mb-4 shadow-sm card-body">
        <legend>importer</legend>
        <p>non fonctionnel</p>
        <form action="./?c=importExport&a=import" method="post" enctype="multipart/form-data">
            <input id="file" type="file" name="file">
            <button class="btn btn-primary">importer</button>
        </form>
        <hr>
        <legend>exporte</legend>
        <form action="./?c=importExport&a=export" method="post">
            <button class="btn btn-primary">exporte</button>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>