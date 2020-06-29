<?php #UTF-8

$config = array(
    'login' =>
    array(
        // admin => admin;
        'admin' => '$argon2i$v=19$m=65536,t=4,p=1$akpNY1Z3aFNGcUVISnNPLg$YTEVa70CUGh+w/N0dNj9yIBmNbWhfdJG8xjn9ScDMdM',
    ),
    'waiting_list' => '../data/waiting_list.json',
    // number of download attempts
    'errorspass' => 3,
    'feed' => "../data/feeds.json",
    'cookiefile' => '../data/cookies.txt',
    'download-archive' =>  '../data/archive-WL.txt',
    'diff' => '../data/diff',
    // nombre max de téléchargement 
    'max_downloads' => 5,
    // max number of streams to be refreshed
    'max_feed' => 170,
    // URL of the instance (important for the RSS feed)
    'url' => '',
    // number of days after which to delete
    'YOUTUBR_DL_WL_purge_days' => 6,
    // path to store videos
    'YOUTUBR_DL_WL' => '../data/watchlater',
    // number of items per page
    'items_per_page' => 50,
);
