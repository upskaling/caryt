<?php #UTF-8


class Entry
{

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function pager($page_start, $page_fin, $state)
  {
    switch ($state) {
      case 1:
        $query = $this->pdo->prepare('SELECT *, "rowid"
        FROM admin_entry
        WHERE "is_read" IS NOT NULL ORDER BY "update"
        LIMIT :page_start, :page_fin');
        break;

      case 2:
        $query = $this->pdo->prepare('SELECT *, "rowid"
        FROM admin_entry
        WHERE "is_read" IS NULL ORDER BY "update"
        LIMIT :page_start, :page_fin');
        break;

      default:
        $query = $this->pdo->prepare('SELECT *, "rowid"
        FROM admin_entry
        ORDER BY "update"
        LIMIT :page_start, :page_fin');
        break;
    }

    $query->execute([
      'page_start' => $page_start,
      'page_fin' => $page_fin,

    ]);

    return $query->fetchall();
  }

  public function sizeof($state)
  {
    switch ($state) {
      case 1:
        return $this->pdo->query('SELECT COUNT(*) AS COUNT
        FROM admin_entry
        WHERE "is_read" IS NOT NULL ORDER BY "update"')
          ->fetch()->COUNT;
      case 2:
        return $this->pdo->query('SELECT COUNT(*) AS COUNT
        FROM admin_entry
        WHERE "is_read" IS NULL ORDER BY "update"')
          ->fetch()->COUNT;
      default:
        return $this->pdo->query('SELECT COUNT(*) AS COUNT
        FROM admin_entry
        WHERE "is_read" IS NULL')
          ->fetch()->COUNT;
    }
  }

  public function add_video_list(array $videos_list)
  {

    $query = $this->pdo->prepare('INSERT INTO "admin_entry" ("url", "title", "uploader_url", "get_date", "thumbnail", "description", "update")
    VALUES (:url, :title, :uploader_url, :get_date, :thumbnail, :description, :update)
    ');

    foreach ($videos_list as $value) {

      $query->execute([
        'url' => $value['url'],
        'title' => $value['title'] ?? '',
        'uploader_url' => $value['uploader-url'] ?? '',
        'get_date' => $value['get_date'] ?? '',
        'thumbnail' => $value['thumbnail'] ?? '',
        'description' =>  $value['description'] ?? '',
        'update' => $value['update'] ?? 0
      ]);
    }
  }

  public function delete(int $id)
  {
    $query = $this->pdo->prepare('DELETE FROM "admin_entry"
    WHERE (("rowid" = :id))
    ');
    $query->execute([
      'id' => $id
    ]);
  }

  public function download(
    int $id,
    $output,
    $cookiefile,
    $download_archive
  ) {
    require_once __DIR__ . '/../Models/Youtube_dl.php';
    $youtube_dl = new Youtube_dl();

    $query = $this->pdo->prepare('SELECT * FROM "admin_entry"
    WHERE "rowid" = :id');
    $query->execute([
      'id' => $id
    ]);
    $value = $query->fetch();

    $youtube_dl->downloader(
      $value->url,
      $stderr,
      $stdout,
      $status,
      $output,
      $cookiefile,
      $download_archive
    );

    error_log($stderr);
    error_log($stdout);

    if ($status > 0) {
      $query = $this->pdo->prepare('UPDATE "admin_entry" SET "pass" = :pass
      WHERE "rowid" = :id 
      LIMIT :id');

      if ($value->pass === null) {
        $query->execute([
          'pass' => 0,
          'id' => $id
        ]);
      } else {
        $query->execute([
          'pass' => $value->pass + 1,
          'id' => $id
        ]);
      }

      throw new Exception('download error');
    } else {

      $query = $this->pdo->prepare('UPDATE "admin_entry" SET "is_read" = :is_read
      WHERE "rowid" = :id');
      $query->execute([
        'is_read' => 1,
        'id' => $id
      ]);
    }
  }

  public function download_from_list(
    int $max_downloads = 1,
    int $errorspass = 3,
    string $output,
    string $cookiefile,
    string $download_archive
  ) {

    $query = $this->pdo->prepare('SELECT "is_read", "pass", "rowid"
    FROM "admin_entry"
    WHERE "is_read" IS NULL');
    $query->execute();

    $download = 0;
    while ($value = $query->fetch()) {

      if ($download >= $max_downloads) {
        break;
      }

      if (empty($value->pass)) {
      } elseif ($value->pass >= $errorspass) {
        continue;
      }

      try {
        $this->download(
          $value->rowid,
          $output,
          $cookiefile,
          $download_archive
        );
        $download += 1;
      } catch (Exception $e) {
        error_log($e->getMessage());
      }
    }
  }


  public function add_video(string $url)
  {
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
      die('Not a valid URL');
    }

    $this->add_video_list([[
      'url' => $url,
      'update' => time()
    ]]);
  }


  public function count_entry(string $uploader_url)
  {
    $query = $this->pdo->prepare('SELECT COUNT(*) AS "count_entry"
    FROM "admin_entry"
    WHERE "uploader_url" = :uploader_url');
    $query->execute(["uploader_url" => $uploader_url]);
    return $query->fetch()->count_entry;
  }
}
