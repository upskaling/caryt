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

    public function get_id(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM "admin_feed"
        WHERE "rowid" = :id');
        $query->execute([
            'id' => $id
        ]);
        return $query;
    }

    public function refresh_a_stream(int $id, $ttl_default)
    {
        $value = $this->get_id($id)->fetch();
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

    public function delete(int $id)
    {
        $query = $this->pdo->prepare('DELETE FROM "admin_feed"
        WHERE "rowid" = :id');
        $query->execute([
            'id' => $id
        ]);
    }

    public function update(
        $xmlUrl,
        $siteUrl,
        $title,
        $update_interval,
        $category,
        $mute,
        $id
    ) {
        $query = $this->pdo->prepare('UPDATE "admin_feed" SET "xmlurl" = :xmlurl, "siteurl" = :siteurl, "title" = :title, "update_interval" = :update_interval, "category" = :category, "mute" = :mute
        WHERE "rowid" = :id
        LIMIT :id;');
        $query->execute([
            'xmlurl' => filter_var($xmlUrl, FILTER_VALIDATE_URL),
            'siteurl' => filter_var($siteUrl, FILTER_VALIDATE_URL),
            'title' => $title,
            'update_interval' => $update_interval,
            "category" => $category,
            'mute' => $mute,
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

    public function get_count_id($id)
    {
        $query = $this->pdo->prepare('SELECT COUNT(*) AS COUNT
        FROM "admin_feed"
        WHERE "category" = :id');
        $query->execute([
            'id' => $id
        ]);
        return $query->fetch()->COUNT;
    }


    public function export()
    {
        $query = $this->pdo->query('SELECT admin_category.name, admin_feed.title, admin_feed.xmlurl, admin_feed.siteurl
        FROM "admin_feed"
        INNER JOIN "admin_category" ON admin_feed.category = admin_category.category
        ORDER BY admin_feed.category');
        return $query->fetchAll();
    }

    public function feed()
    {
        $query = $this->pdo->query('SELECT *, "rowid"
        FROM "admin_feed"');
        return $query->fetchAll();
    }


    public function FunctionFeedGet(int $id, int $error = 0)
    {
        if ($error == 1) {
            $query = $this->pdo->prepare('SELECT *, "rowid"
            FROM "admin_feed"
            WHERE "status" IS NOT NULL AND "category" = :id');
        } else {
            $query = $this->pdo->prepare('SELECT *, "rowid"
            FROM "admin_feed"
            WHERE "category" = :id');
        }
        $query->execute([
            'id' => $id,
        ]);
        return $query->fetchAll();
    }

    public function count_update()
    {
        $query = $this->pdo->query(
            'SELECT COUNT(*) AS COUNT
            FROM admin_feed
            WHERE "siteurl" = "" OR "siteurl" IS NULL'
        );
        return $query->fetch();
    }

    public function update_siteurl()
    {
        $query = $this->pdo->query(
            'SELECT "xmlurl", "title", "rowid"
            FROM "admin_feed"
            WHERE "siteurl" = "" OR "siteurl" IS NULL'
        )->fetchAll();

        require_once('../lib/SimplePie/autoloader.php');

        $list_flux = [];
        foreach ($query as $key => $value) {
            $xmlurl = [];
            $xmlurl['id'] = $value->rowid;
            $xmlurl['title'] = $value->title;
            $xmlurl['xmlurl'] = $value->xmlurl;

            $feed = new SimplePie();
            $feed->set_feed_url($value->xmlurl);
            $feed->set_cache_duration(43200);
            $feed->enable_cache();
            $feed->set_cache_location('../data/SimplePie/');
            $feed->init();

            if ($feed->error()) {
                $xmlurl['results'] =  $feed->error();
            } else {
                $query = $this->pdo->prepare(
                    'UPDATE "admin_feed"
                    SET siteurl=:siteurl, title=:title
                    WHERE xmlurl=:xmlurl'
                );
                $query->execute([
                    'xmlurl' => filter_var($value->xmlurl, FILTER_VALIDATE_URL),
                    'siteurl' => $feed->get_permalink(),
                    'title' => $feed->get_title()
                ]);
            }
            array_push($list_flux, $xmlurl);
        }
        return $list_flux;
    }
}
