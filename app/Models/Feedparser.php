<?php #UTF-8

class Feedparser
{
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function write()
    {
        if (!empty($this->videos)) {
            require_once 'Entry.php';
            $entry = new Entry($this->pdo);
            $entry->add_video_list($this->videos);
        }
    }

    private function ttl_conter(int $update_interval_id = 0, int $ttl_default = 43200)
    {

        $ttl_list = [
            1200, 1500, 1800, 2700,
            3600, 5400, 7200, 10800, 14400, 18800, 21600, 25200, 28800,
            36000, 43200, 64800,
            86400, 129600, 172800, 259200, 345600, 432000, 518400,
            604800
        ];

        if ($update_interval_id ?? 0 > 0) {
            return $ttl_list[$update_interval_id - 1];
        } else {
            return $ttl_default;
        }
    }

    public function refresh_a_stream(int $id, $ttl_default)
    {
        $query = $this->pdo->prepare('SELECT * FROM "admin_feed"
        WHERE "rowid" = :id');
        $query->execute([
            'id' => $id
        ]);

        $value = $query->fetch();
        error_log("[feedparser]: " . $value->xmlurl);
        require_once '../lib/SimplePie/autoloader.php';
        $feed = new SimplePie();
        $feed->set_feed_url($value->xmlurl);
        $feed->set_useragent('Mozilla/5.0 (Windows NT 10.0; rv:68.0) Gecko/20100101 Firefox/68.0');
        $feed->set_cache_duration($this->ttl_conter($value->update_interval, $ttl_default));
        $feed->enable_cache();
        $feed->set_cache_location('../data/SimplePie/');
        $feed->init();

        $query = $this->pdo->prepare('UPDATE "admin_feed" SET "update" = :update
        WHERE "rowid" = :id');
        $query->execute([
            'update' => time(),
            'id' => $id
        ]);

        $query = $this->pdo->prepare('UPDATE "admin_feed" SET "status" = :log
        WHERE "rowid" = :id');
        if ($feed->error()) {
            error_log($feed->error());
            $query->execute([
                'log' => $feed->error(),
                'id' => $id
            ]);
            return;
        } else {
            $query->execute([
                'log' => null,
                'id' => $id
            ]);
        }

        $feed->handle_content_type();

        $items = $feed->get_items();

        $query = $this->pdo->prepare('UPDATE "admin_feed" SET "status" = :log
        WHERE "rowid" = :id');
        if (empty($items)) {
            $query->execute([
                'log' => 'Chaîne sans contenu',
                'id' => $id
            ]);
            return;
        } else {
            $query->execute([
                'log' => null,
                'id' => $id
            ]);
        }

        $query = $this->pdo->prepare('SELECT "update", "rowid"
        FROM "admin_entry"
        WHERE "url" = :url');

        $entity_counter_add = 0;
        foreach ($items as $item) {
            $query->execute([
                'url' => $item->get_permalink()
            ]);
            $saved_items = $query->fetch();

            if (!empty($saved_items)) {
                continue;
            }

            $entry = [];
            $entry['url'] = $item->get_permalink();
            $entry['title'] = $item->get_title();

            $entry['uploader-url'] = $value->xmlurl;
            $entry['get_date'] = $item->get_date('U');

            if ($enclosure = $item->get_enclosure()) {
                if (null !== $enclosure->get_thumbnail()) {
                    $entry['thumbnail'] = $enclosure->get_thumbnail();
                }
                if (null !== $enclosure->get_description()) {

                    $entry['description'] = $enclosure->get_description();
                }
            }

            if (isset($saved_items->update)) {
                $entry['update'] = $saved_items->update;
            } else {
                $entry['update'] = time();
            }

            $entity_counter_add += 1;
            $this->videos[] = $entry;
        }

        if ($entity_counter_add > 0) {
            error_log("[feedparser]:  => add $entity_counter_add", 0);
        }
    }

    public function track_flows(int $max_feed, int $ttl_default)
    {
        require_once '../lib/SimplePie/autoloader.php';

        $query = $this->pdo->prepare('SELECT "update", "update_interval", "rowid"
        FROM "admin_feed"');
        $query->execute();

        $max_f = 0;
        while ($value = $query->fetch()) {

            if (
                $value->update >
                strtotime('-' . $this->ttl_conter($value->update_interval, $ttl_default) . ' seconds') ||
                !empty($value->mute)
            ) {
                continue;
            }

            $max_f += 1;
            if ($max_f > $max_feed) {
                break;
            }
            $this->refresh_a_stream($value->rowid, $ttl_default);
        }
        $this->write();
    }

    public function add_feeds(string $xmlUrl, &$resulta)
    {
        if (empty($resulta)) {
            require_once '../lib/SimplePie/autoloader.php';
            $feed = new SimplePie();
            $feed->set_feed_url($xmlUrl);
            $feed->set_cache_duration(43200);
            $feed->enable_cache();
            $feed->set_cache_location('../data/SimplePie/');
            $feed->init();

            if ($feed->error()) {
                $resulta = $feed->error();
            } else {
                $query = $this->pdo->prepare('INSERT INTO "admin_feed" ("xmlurl", "siteurl", "title", "update_interval", "update", "category")
                VALUES (:xmlurl, :siteurl, :title, :update_interval, :update, :category);');
                $query->execute([
                    'xmlurl' => filter_var($xmlUrl, FILTER_VALIDATE_URL),
                    'siteurl' => $feed->get_permalink(),
                    'title' => $feed->get_title(),
                    'update_interval' => 0,
                    'update' => time(),
                    'category' => 0
                ]);
                $resulta = 0;
            }
        }
    }

    public function delete($id)
    {
        $query = $this->pdo->prepare('DELETE FROM "admin_feed"
        WHERE "rowid" = :id');
        $query->execute([
            'id' => $id
        ]);
    }


    public function change_category($id, $dest = 0)
    {
        $query = $this->pdo->prepare('UPDATE "admin_feed" SET category = :dest
        WHERE category = :id');
        $query->execute([
            'id' => $id,
            'dest' => $dest
        ]);
    }


    public function get_info_feed(string $uploader_url)
    {
        $query = $this->pdo->prepare('SELECT *, "rowid"
        FROM "admin_feed"
        WHERE "xmlurl" = :uploader_url
        LIMIT 1');
        $query->execute([
            'uploader_url' => $uploader_url
        ]);
        return $query->fetch();
    }
}
