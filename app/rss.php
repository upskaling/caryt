<?php #UTF-8

if (is_file('../data/config.php')) {
  require '../data/config.php';
} else {
  require '../config.default.php';
}

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

$youtubr_dl_wl_dir = glob($config['YOUTUBR_DL_WL'] . '/*/');

$filename = end($youtubr_dl_wl_dir);

$dir_video = $filename;

foreach (scandir($dir_video) as $name) {
  if (in_array($name, array('.', '..'))) {
    continue;
  }
  $jsonb = json_decode(file_get_contents($dir_video . '/' . $name . '/' . $name . '.info.json'), true);

  $dir_date = pathinfo($filename, PATHINFO_BASENAME);
  $extension_thumbnail = pathinfo($jsonb['thumbnail'], PATHINFO_EXTENSION);

  if (is_file($dir_video . $name . '/' . $name . '.' . $extension_thumbnail)) {
    $jsonb['thumbnail'] = $config['url'] . 'f.php?' .  $dir_date . '/' . $name . '.' . $extension_thumbnail;
  }


  $item = $dom->createElement('item');
  $channel->appendChild($item);

  $jsonb['_filename'] = $dir_video . '/' . $name . '/' . pathinfo($jsonb['_filename'], PATHINFO_BASENAME);
  if (is_file($jsonb['_filename'])) {
    $url_video = $config['url'] . 'f.php?' . $dir_date . '/' . basename($jsonb['_filename']);
    $item->appendChild($dom->createElement('pubDate', date(DATE_RSS, filemtime($jsonb['_filename']))));
    $LSmimetypes = mime_content_type($jsonb['_filename']);
    $LSbasename = basename($jsonb['_filename']);
    $html_video = '<video width="100%" preload="none" controls poster="' . htmlspecialchars($jsonb['thumbnail']) . '"><source src="' . $url_video . '" type="' . $LSmimetypes . '">Votre navigateur ne permet pas de lire les vidéos HTML5.</video>';

    $enclosure = $dom->createElement('enclosure');
    $enclosure->setAttribute('length', filesize($jsonb['_filename']));
    $enclosure->setAttribute('type', $LSmimetypes);
    $enclosure->setAttribute('url', $url_video);
    $item->appendChild($enclosure);
  } else {
    $url_video = htmlspecialchars($jsonb['webpage_url']);
    $poster = '<img src="' . htmlspecialchars($jsonb['thumbnail']) . '" loading="lazy" alt="" width="100%">';
    $html_video = '<a href="' . $url_video . '" rel="author">' . $poster . '</a>';
  }

  $description = $html_video . '<br>';
  $description .= '<a title="' . htmlspecialchars($jsonb['title']) . '" href="' . htmlspecialchars($jsonb['webpage_url']) . '">';
  $description .= htmlspecialchars($jsonb['title']) . '</a><br>';
  $description .= '<p>' . htmlspecialchars($jsonb['view_count']) . ' vues • ' . date('j M Y', strtotime($jsonb['upload_date'])) . '</p><hr>';
  $description .= '<a href="' . htmlspecialchars($jsonb['uploader_url']) . '" rel="author">' . htmlspecialchars($jsonb['uploader']) . '</a>';
  $description .= '<p>' . nl2br(htmlspecialchars($jsonb['description']), false) . '</p>';

  $item->appendChild($dom->createElement('title', htmlspecialchars($jsonb['title'])));
  $item->appendChild($dom->createElement('link', htmlspecialchars($config['url'] . '?c=watch&v=' . $dir_date . '/' . pathinfo($jsonb['_filename'], PATHINFO_FILENAME))));
  $item->appendChild($dom->createElement('description', $description));

  $item->appendChild($dom->createElement('author', htmlspecialchars($jsonb['uploader'])));

  if ($jsonb['tags']) {
    foreach ($jsonb['tags'] as $tag) {
      $item->appendChild($dom->createElement('category', htmlspecialchars($tag)));
    }
  }
}



$dom->save('../p/index.xml') or die('XML Create Error');
error_log("[rss] ok");
