<?php #UTF-8

class Feedparser
{
    public function __construct($feed_file)
    {
        $this->feed_file = $feed_file;
        $this->read();
    }

    public function read()
    {
        $this->feeds = json_decode(
            file_get_contents($this->feed_file),
            true
        )['outline'];
    }

    public function write()
    {
        $this->remove_duplicates();
        file_put_contents(
            $this->feed_file,
            json_encode(
                ['outline' => $this->feeds],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );
    }

    public function remove_duplicates()
    {
        $url = [];
        $result = [];
        foreach ($this->feeds as $value) {
            if (!in_array($value['xmlUrl'], $url)) {
                $url[] = $value['xmlUrl'];
                $result[] = $value;
            }
        }
        usort($result, function ($a, $b) {
            return $a['title'] <=> $b['title'];
        });
        $this->feeds = $result;
    }

    public function refresh_a_stream(int $id)
    {
        $value = &$this->feeds[$id];
        error_log("[feedparser]: " . $value['xmlUrl']);
        require_once '../lib/SimplePie/autoloader.php';
        $feed = new SimplePie();
        $feed->set_feed_url($value['xmlUrl']);
        $feed->set_useragent('Mozilla/5.0 (Windows NT 10.0; rv:68.0) Gecko/20100101 Firefox/68.0');
        $feed->set_cache_duration($value['update_interval']);
        $feed->enable_cache();
        $feed->set_cache_location('../data/SimplePie/');
        $feed->init();


        if ($feed->error()) {
            error_log($feed->error());
            $value['status'] = $feed->error();
            return;
        } else {
            unset($value['status']);
        }

        $feed->handle_content_type();

        $cache = '../data/feeds/' . md5($value['xmlUrl']) . '.json';

        if (is_file($cache)) {
            $saved_items = json_decode(file_get_contents($cache), true);
        }

        unset($saved_items_new);
        $entity_counter = 0;
        foreach ($feed->get_items() as $item) {
            $entry = [];
            $entry['url'] = $item->get_permalink();
            $entry['title'] = (string) $item->get_title();

            $entry['uploader'] = $value['title'];
            $entry['uploader-url'] = $value['xmlUrl'];
            $entry['get_date'] = $item->get_date('U');
            
            $get_id = $item->get_id();
            if (isset($saved_items[$id]['update'])) {
                $entry['update'] = $saved_items[$get_id]['update'];
            } else {
                $entry['update'] = date('Y-m-d H:i:s', time());
            }

            $saved_items_new[$get_id] = $entry;
            if (isset($saved_items[$get_id])) {
                continue;
            }
            $entity_counter += 1;
            $videos[] = $entry;
        }


        if (isset($saved_items_new)) {
            file_put_contents(
                $cache,
                json_encode(
                    $saved_items_new,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
            unset($value['status']);
        } else {
            $value['status'] = 'Chaîne sans contenu';
        }
        $value['update'] = date('Y-m-d H:i:s', time());

        if ($entity_counter > 0) {
            error_log("[feedparser]:  => add $entity_counter", 0);
        }


        $videos = !empty($videos) ? $videos : [];

        require_once 'Waiting_list.php';
        $waiting_list = new Waiting_list();
        $waiting_list->add_video_list($videos);
        $waiting_list->write();
    }

    public function track_flows(int $max_feed)
    {
        require_once '../lib/SimplePie/autoloader.php';

        $max_f = 0;
        foreach ($this->feeds as $id => $value) {
            if (
                strtotime($value['update']) >
                strtotime('-' . $value['update_interval'] . ' seconds')
            ) {
                continue;
            }

            $max_f += 1;
            if ($max_f > $max_feed) {
                break;
            }
            $this->refresh_a_stream($id);
        }
        $this->write();
    }

    public function add_feeds($xmlUrl, &$resulta, &$i)
    {
        foreach ($this->feeds as $i => $value) {
            if ($value['xmlUrl'] == $xmlUrl) {
                $resulta = 'déjà abonné';
                break;
            }
        }

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

                $this->feeds[] = [
                    'xmlUrl' => $xmlUrl,
                    'title' => $feed->get_title(),
                    'siteUrl' => $feed->get_permalink(),
                    'update_interval' => 43200,
                    'update' => date('Y-m-j H:i:s')
                ];
                $resulta = 0;
            }
        }
    }

    public function delete($id)
    {
        unset($this->feeds[$id]);
    }
}
