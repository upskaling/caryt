<?php $title = 'Ajout d\'une URL'; ?>
<?php ob_start(); ?>

<?php if (!empty($url)) : ?>
    <div class="alert alert-success" role="alert">
        <?= $url ?>
    </div>
<?php endif; ?>
<!-- add url -->
<form action="?c=add_url" method="post" class="form-inline">
    <div class="custom-file">
        <div class="form-group mx-sm-3 mb-2 justify-content-center">
            <input type="url" id="url" name="url" class="form-control" placeholder="Add url" />
            <input type="submit" value="Submit" class="btn btn-primary" />
        </div>
    </div>
</form>

<?php include(__DIR__ . '/../template/footer.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>