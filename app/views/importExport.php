<?php #UTF-8

require_once __DIR__ . '/../Models/Feedparser.php';
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
?>

<?php $title = 'importer / exporte'; ?>
<?php ob_start(); ?>

<?php include(__DIR__ . '/../template/header.php'); ?>

<div class="container py-4">
    <div class="card mb-4 shadow-sm card-body">
        <legend>importer</legend>
        <p>non fonctionnel</p>
        <form action="./?c=importExport&a=import" method="post" enctype="multipart/form-data">
            <input id="file" type="file" name="file">
            <button class="btn btn-primary">importer</button>
        </form>
        <hr>
        <legend>exporte</legend>
        <form action="./?c=importExport&a=export" method="post">
            <button class="btn btn-primary">exporte</button>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require(__DIR__ . '/../template/template.php'); ?>