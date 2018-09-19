<?php

/**
 * WoW Guild news downloader
 * @author Nikita Chernyi
 */
class News
{
    /**
     * News data
     * @var array
     */
    protected $data = [];
    /**
     * WoWProgress RSS url
     * @var string
     */
    protected $wowprogressUrl;

    /**
     * Battle.net API url
     * @var string
     */
    protected $battlenetUrl;
    /**
     * Init
     * @param string $region WoW region, default: eu
     * @param string $realm WoW realm (server)
     * @param string $guild Guild name
     * @param string $apikey Battle.net API key
     * @param string $lang Battle.net locale, default: en_GB
     */
    public function __construct(string $region = 'eu', string $realm, string $guild, string $apikey, string $lang = 'en_GB')
    {
        if(!$apikey) {
            $this->log('Battle.net', 'warning. No API key');
        }
        $this->wowprogressUrl = 'guild/'.$region.'/'.strtolower($realm).'/'.str_replace(' ','+',$guild);
        $this->battlenetUrl = 'https://'.$region.'.api.battle.net/wow/guild/'.ucfirst($realm).'/'.str_replace(' ','%20', $guild).'?fields=news&locale='.$lang.'&apikey='.$apikey;
    }

    public function get(): array
    {
        return $this->getWowprogress()
                    ->getBattlenet()
                    ->sort()
                    ->data;
    }

    public function update(): News
    {
        return $this->updateWowprogress();
    }

    protected function log(string $task = 'News', string $message): void
    {
        echo "[".date('Y-m-d H:i:s')."] $task - $message\n";
    }

    /**
     * Fetch data from URL
     * @param string $url URL to GET
     * @return string
     */
    protected function fetch(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Send POST request to URL with DATA.
     * @param string $url
     * @param array $data
     * @return string
     */
    protected function send(string $url, array $data): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); //workaround for redirect bug (send post - redirect - send get)
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POSTREDIR, 3); //workarond for redirect bug
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Load news from WoWProgress
     * @param int $limit Items limit
     * @return News
     */
    protected function getWowprogress(int $limit = 5): News
    {
        try {
        $raw = $this->fetch('https://wowprogress.com/rss/'.$this->wowprogressUrl);
        $rss = simplexml_load_string($raw);
        foreach($rss->channel->item as $item) {
            $type = 'member-'.((strpos($item->title, 'joined') !== false) ? 'join' : 'leave');
            $this->data[] = [
                'timestamp' => strtotime((string)$item->pubDate),
                'type' => $type,
                'title' => strtr((string)$item->title, ['joined' => 'вступил(-а) в', 'left' => 'покинул(-а)']),
                'description' => '',
            ];
        }
        $this->log('FETCH WoWProgress', 'success');
        $this->sort();
        $this->data = array_slice($this->data, 0, $limit);
        } catch (\Throwable $t) {
            $this->log('FETCH WoWProgress', 'fail. '.$t->getMessage());
        }

        return $this;
    }

    /**
     * Update WoWProgress results
     * @return News
     */
    protected function updateWowprogress(): News
    {
        /**
         * Algo:
         * 1. Get char ids from wowprogress page (find by css class)
         * 2. Send POST request with those char ids
         */
        try {
        $charIds = [];
        $html = $this->fetch('https://wowprogress.com/update_progress/'.$this->wowprogressUrl);
        libxml_use_internal_errors(true);
        $dom = new \DomDocument();
        $dom->loadHTML($html);
        $finder = new DomXPath($dom);
        $classname = "char_chbx";
        $nodes = $finder->query("//*[contains(@class, '$classname')]");
        foreach($nodes as $node) {
            $charIds[] = substr($node->getAttribute('id'), 6); //because id is "check_1232145", so we must remove that prefix
        }

        $result = json_decode($this->send('https://wowprogress.com/update_progress/'.$this->wowprogressUrl, ['submit' => 1, 'char_ids' => json_encode($charIds)]), true);
        $this->log('UPDATE WoWProgress', ($result['success'] ?? false) == true ? 'success' : 'fail');
        } catch (\Throwable $t) {
            $this->log('UPDATE WoWProgress', 'fail. '.$t->getMessage());
        }

        return $this;
    }

    /**
     * Load news from Battle.net API
     * @return News
     */
    protected function getBattlenet(): News
    {
        $news = json_decode($this->fetch($this->battlenetUrl), true);
        if(!($news['news'] ?? false)) {
            $this->log('FETCH Battle.net', 'fail. No news found');
            return $this;
        }
        foreach($news['news'] as $item) {
            if($item['type'] == 'guildAchievement') {
                $this->data[] = [
                    'timestamp' => substr($item['timestamp'], 0, -3), //weird battle.net result with "000" ending in any timestamp
                    'type' => 'achievement',
                    'title' => $item['achievement']['title'],
                    'description' => $item['achievement']['description'],
                ];
            }
        }
        $this->log('FETCH Battle.net', 'success');
        return $this;
    }

    /**
     * Sort results
     * @return News
     */
    protected function sort(): News
    {
        uasort($this->data, function(array $a, array $b){
            if ($a['timestamp'] == $b['timestamp']) {
                return 0;
            }
            return ($a['timestamp'] < $b['timestamp']) ? 1 : -1;
        });

        $this->log('SORT', 'success');
        return $this;
    }
}
/**
 * Run it!
 */
// 1. Download and parse news
$news = new News('eu', 'Галакронд', 'Ясный Лес', getenv('BATTLENET_API_KEY'), 'ru_RU');
$data = $news->update()->get();
// 2. Create CSV file with headers
$fp = fopen('./_data/news.csv', 'w');
fputcsv($fp, ['timestamp', 'type', 'title', 'description']);
// 3. And append results.
foreach($data as $item) {
    fputcsv($fp, $item);
}
fclose($fp);
// 4. Done
