<?php
class category
{

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function update($title, $id)
    {
        $query = $this->pdo->prepare('UPDATE "admin_category" SET "name" = :name
        WHERE "rowid" = :id
        LIMIT :id');
        $query->execute([
            'name' => $title,
            'id' => $id
        ]);
    }

    public function add_category($new_category)
    {
        $query = $this->pdo->prepare('INSERT INTO "admin_category" ("name")
        VALUES (:name)');
        $query->execute([
            'name' => $new_category
        ]);
    }

    public function delete($id)
    {
        require_once __DIR__ . '/../Models/Feedparser.php';
        $feedparser = new Feedparser($this->pdo);
        $feedparser->change_category($_GET['id'], 0);

        $query = $this->pdo->prepare('DELETE FROM "admin_category"
        WHERE "rowid" = :id');
        $query->execute([
            'id' => $id
        ]);
    }
}
