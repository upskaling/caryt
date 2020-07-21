<?php

if ($_GET['a'] == 'read' and isset($_POST['id'])) {
    $query = $pdo->prepare('UPDATE "admin_entry" SET "is_read" = :is_read
    WHERE "rowid" = :id');    
    $query->execute([
        'is_read' => ($_GET['is_read'] == 1) ? null : 1,
        'id' => $_POST['id']
    ]);

    header('Location: ./?c=waiting');
}
