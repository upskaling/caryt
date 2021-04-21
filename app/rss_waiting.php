<?php #UTF-8

if (is_file('../data/config.php')) {
  require '../data/config.php';
} else {
  require '../config.default.php';
}


$pdo = new PDO(
  $config['db'],
  null,
  null,
  [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
  ]
);

require_once(__DIR__ . '/Models/Feedparser.php');
$feedparser = new Feedparser($pdo);

$dom = new DOMDocument('1.0', "UTF-8");
$dom->formatOutput = true;
$root = $dom->createElement('rss');
$root->setAttribute('version', "2.0");
$root->setAttribute('xmlns:content', "http://purl.org/rss/1.0/modules/content/");
$root->setAttribute('xmlns:atom', "http://www.w3.org/2005/Atom");

$dom->appendChild($root);
$channel = $dom->createElement('channel');
$root->appendChild($channel);

$channel->appendChild($dom->createElement('title', 'watchlater'));
$channel->appendChild($dom->createElement('link', $config['url'] . 'index2.xml'));
$channel->appendChild($dom->createElement('description', 'watchlater'));
$channel->appendChild($dom->createElement('lastBuildDate', date(DATE_RSS)));

$query = $pdo->prepare('SELECT * FROM "admin_entry" WHERE "update" >= :update');
$query->execute(
  [
    "update" => time() - (24 * 60 * 60),
  ]
);

$tlk = $query->fetchAll();
foreach ($tlk as $key => $name) {
  $uploader_url = $feedparser->get_info_feed($name->uploader_url);
  $item = $dom->createElement('item');
  $channel->appendChild($item);


  $item->appendChild(
    $dom->createElement('pubDate', date(DATE_RSS, $name->get_date))
  );

  $poster = '<img src="' . htmlspecialchars($name->thumbnail) . '" loading="lazy" alt="" width="100%">';
  $html_video = '<a href="' . htmlspecialchars($name->url) . '" rel="author">' . $poster . '</a>';

  $description = $html_video . '<br>';
  $description .= '<a title="' . htmlspecialchars($name->title) . '" href="' . htmlspecialchars($name->url) . '">';
  $description .= htmlspecialchars($name->title) . '</a><br>';
  $description .= '<a href="' . htmlspecialchars($uploader_url->siteurl) . '" rel="author">' . htmlspecialchars($uploader_url->title) . '</a>';
  $description .= '<p>' . nl2br(htmlspecialchars($name->description), false) . '</p>';

  $item->appendChild($dom->createElement('title', htmlspecialchars($name->title)));
  $item->appendChild($dom->createElement('link', htmlspecialchars($name->url)));
  $item->appendChild($dom->createElement('description', $description));

  $item->appendChild($dom->createElement('author', htmlspecialchars($uploader_url->title)));
}

$dom->save('../p/waiting.xml') or die('XML Create Error');

error_log("[rss] waiting: ok");
