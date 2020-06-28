<?php #UTF-8

class Waiting_list
{
  public function __construct(string $waiting_list = '../data/waiting_list.json')
  {

    $this->waiting_list = $waiting_list;
    $this->read();
  }

  public function read()
  {
    if (is_file($this->waiting_list)) {
      $this->videos = json_decode(file_get_contents($this->waiting_list), true);
    } else {
      $this->videos = [];
    }
  }

  public function write()
  {
    $this->remove_duplicates();
    file_put_contents(
      $this->waiting_list,
      json_encode($this->videos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );
  }

  public function remove_duplicates()
  {
    $url = [];
    $result = [];
    foreach ($this->videos as $value) {
      if (!in_array($value['url'], $url)) {
        $url[] = $value['url'];
        $result[] = $value;
      }
    }
    usort($result, function ($a, $b) {
      return $a['update'] <=> $b['update'];
    });
    $this->videos = $result;
  }

  public function add_video_list(array $videos_list)
  {
    $this->videos = array_merge($this->videos, $videos_list);
  }

  public function add_video(string $url)
  {
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
      die('Not a valid URL');
    }
    $this->add_video_list(
      [
        'url' => $url,
        'update' => date('Y-m-j H:i:s')
      ]
    );
  }

  public function download(
    int $id,
    $output,
    $cookiefile,
    $download_archive
  ) {
    require_once __DIR__ . '/../Models/Youtube_dl.php';
    $youtube_dl = new Youtube_dl();
    $value = &$this->videos[$id];

    $youtube_dl->downloader(
      $value['url'],
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
      if (empty($value['pass'])) {
        $value['pass'] = 0;
      }
      $value['pass'] += 1;
      throw new Exception('download error');
    } else {
      $this->delete($id);
    }
  }

  public function download_from_list(
    int $max_downloads = 1,
    int $errorspass = 3,
    string $output,
    string $cookiefile,
    string $download_archive
  ) {

    $download = 0;
    foreach ($this->videos as $key => &$value) {
      if ($download >= $max_downloads) {
        break;
      }

      if (empty($value['pass'])) {
      } elseif ($value['pass'] >= $errorspass) {
        continue;
      }

      try {
        $this->download(
          $key,
          $output,
          $cookiefile,
          $download_archive
        );
        $download += 1;
      } catch (Exception $e) {
        error_log($e->getMessage());
      }
    }

    $this->write();
  }

  public function delete(int $id)
  {
    unset($this->videos[$id]);
    $this->videos = array_values($this->videos);
  }
}
