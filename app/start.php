<?php #UTF-8

header('content-type: text/html; charset=utf-8');
header('content-language: fr');
header('cache-control: no-cache');

if (is_file('../data/config.php')) {
  require '../data/config.php';
} else {
  require '../config.default.php';
}

if (!is_file('../data/install')) {
  require '../app/controller/install.php';
  die();
}

require '../app/controller/login.php';

$pdo = new PDO(
  $config['db'],
  null,
  null,
  [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
  ]
);

$GET_c = $_GET['c'] ?? '';
switch ($GET_c) {
  case 'subscriptions':
    $path = 'subscriptions.php';
    break;

  case 'waiting':
    $path = 'waiting.php';
    break;

  case 'channels':
    $path = 'channels.php';
    break;

  case 'configure':
    $path = 'configure.php';
    break;

  case 'feed':
    $path = 'feed.php';
    break;

  case 'add_url':
    $path = 'add_url.php';
    break;

  case 'watch':
    $path = 'watch.php';
    break;

  case 'stats':
    $path = 'stats.php';
    break;

  case 'about':
    require '../app/views/about.phtml';
    break;

  case 'configure':
    $path = 'configure.php';
    break;

  case 'update':
    $path = 'update.php';
    break;

  case 'category':
    $path = 'category.php';
    break;

  case 'entry':
    $path = 'entry.php';
    break;

  case 'importExport':
    $path = 'importExport.php';
    break;

  case 'logout':
    header('WWW-Authenticate: Basic realm="Login"');
    header('HTTP/1.0 401 Unauthorized');
    break;

  default:
    $path = 'subscriptions.php';
    break;
}

require '../app/controller/' . $path;
