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

    public function get()
    {
        $query =  $this->pdo->prepare('SELECT *, "rowid" FROM "admin_category"');
        $query->execute();
        return $query;
    }

    public function GetCategory(Int $id = 0)
    {
        $query =  $this->pdo->prepare('SELECT *
        FROM "admin_category"
        WHERE "rowid" = :id');

        $query->execute([
            'id' => $id
        ]);

        return $query->fetch();
    }

    public function get_top(string $uploader_url)
    {
        $query = $this->pdo->prepare('SELECT "categories", COUNT(*) AS "count"
        FROM "admin_entry"
        WHERE "categories" IS NOT NULL AND "uploader_url" LIKE :uploader_url
        GROUP BY "categories"
        ORDER BY "count" DESC
        LIMIT 1');
        $query->execute(['uploader_url' => $uploader_url]);
        return $query;
    }
}
