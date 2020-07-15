<?php #UTF-8

header('content-type: text/html; charset=utf-8');
header('content-language: fr');
header('cache-control: no-cache');

if (is_file('../data/config.php')) {
  require '../data/config.php';
} else {
  require '../config.default.php';
}

require '../app/views/login.php';

$GET_c = $_GET['c'] ?? '';
switch ($GET_c) {
  case 'subscriptions':
    require '../app/views/subscriptions.php';
    break;

  case 'waiting':
    require '../app/views/waiting.php';
    break;

  case 'channels':
    require '../app/views/channels.php';
    break;

  case 'configure':
    require '../app/views/configure.php';
    break;

  case 'feed':
    require '../app/views/feed.php';
    break;

  case 'add_url':
    require '../app/views/add_url.php';
    break;

  case 'watch':
    require '../app/views/watch.php';
    break;

  case 'stats':
    require '../app/views/stats.php';
    break;

  case 'about':
    require '../app/views/about.phtml';
    break;

  case 'configure':
    require '../app/views/configure.php';
    break;

  case 'update':
    require '../app/views/update.php';
    break;

  case 'category':
    require '../app/views/category.php';
    break;


  case 'logout':
    header('WWW-Authenticate: Basic realm="Login"');
    header('HTTP/1.0 401 Unauthorized');
    break;

  default:
    require '../app/views/subscriptions.php';
    // header('Location: ?c=subscriptions');
    break;
}
