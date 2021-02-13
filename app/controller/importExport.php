<?php #UTF-8

require_once(__DIR__ . '/../Models/Feedparser.php');

$feedparser = new Feedparser($pdo);

function export($feedparser)
{
    $dom = new DOMDocument('1.0', "UTF-8");
    $dom->formatOutput = true;
    $opml = $dom->createElement('opml');
    $opml->setAttribute('version', "2.0");
    $dom->appendChild($opml);

    $head = $dom->createElement('head');
    $head->appendChild($dom->createElement('title', 'Caryt'));
    $head->appendChild($dom->createElement('dateCreated', date(DATE_RSS)));
    $opml->appendChild($head);

    $body = $dom->createElement('body');
    $opml->appendChild($body);

    $category = [];
    foreach ($feedparser as $value) {

        if (!in_array($value->name, $category)) {
            $category[] = $value->name;
            $outline = $dom->createElement('outline');
            $outline->setAttribute('text', $value->name);
            $body->appendChild($outline);
        }

        $outlines = $dom->createElement('outline');
        $outlines->setAttribute('text', $value->title);
        $outlines->setAttribute('type', 'RSS');
        $outlines->setAttribute('xmlUrl', $value->xmlurl);
        $outlines->setAttribute('htmlUrl', $value->siteurl);
        // $outlines->setAttribute('description', '');
        $outline->appendChild($outlines);
    }

    return $dom->saveXML();
}

$get_a = $_GET['a'] ?? '';
switch ($get_a) {
    case 'export':
        header('content-type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="caryt_' . date('Y-m-d') . '.opml.xml"');
        print(export($feedparser->export()));
        die();
        break;

    case 'import':
        break;

    default:
        break;
}

require(__DIR__ . '/../views/importExport.php');