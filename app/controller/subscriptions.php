<?php
require_once(__DIR__ . '/../Models/InfoVideo.php');

$count_video = 0;
foreach (scandir($config['YOUTUBR_DL_WL']) as $fileDate) {
    if (in_array($fileDate, ['.', '..'])) continue;
    $youtubr_dl_wl_dir[] = $fileDate;
    foreach (scandir($config['YOUTUBR_DL_WL'] . '/' . $fileDate) as $video_id) {
        if (in_array($video_id, ['.', '..'])) continue;
        $count_video += 1;
    }
}
$filename_a = isset($_GET['page']) ? $_GET['page'] : end($youtubr_dl_wl_dir);

require(__DIR__ . '/../views/subscriptions.php');
