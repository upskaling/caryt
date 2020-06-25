<?php

class Youtube_dl
{

    public function __construct()
    {
        print('');
        $this->exec = 'python3 ../bin/youtube-dl' . ' ';
    }

    public function version()
    {
        return exec($this->exec . '--version');
    }

    public function update()
    {
        return exec($this->exec . '--update');
    }

    public function install()
    {
        $curl = curl_init('https://yt-dl.org/downloads/latest/youtube-dl');
        $result = curl_exec($curl);
        file_put_contents('../bin/youtube-dl', $result);
    }

    public function sehll(array $arguments, &$stderr = null, &$stdout = null, &$ret)
    {
        $arguments[] .= '--ignore-errors';
        $arguments[] .= '--no-continue';
        $arguments[] .= '--no-progress';
        $arguments[] .= '--user-agent "Mozilla/5.0 (Windows NT 10.0; rv:68.0) Gecko/20100101 Firefox/68.0"';
        $string = ($this->exec . join(' ', $arguments));

        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w"),  // stderr
        );

        $process = proc_open($string, $descriptorspec, $pipes);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $ret = proc_close($process);
    }

    public function downloader_info(string $url ,$output)
    {
        $arguments = [
            '--skip-download',
            '--write-info-json',
            '--write-thumbnail',
            '--output "' . $output . '"',
            escapeshellarg($url)
        ];
        $this->sehll($arguments, $stderr, $stdout, $ret);
    }

    // https://stackoverflow.com/questions/15153183/youtube-dl-and-php-exec
    public function downloader(string $url, &$stderr = null, &$stdout = null, &$ret, $output, $cookiefile, $download_archive)
    {
        $this->downloader_info($url , $output);
        $arguments = [
            '--add-metadata',
            '--format "worstvideo[height>=?480][ext=webm]+bestaudio[ext=webm]/worst[height>=?480]/best"',
            '--match-filter "!is_live & duration < 7200"',
            '--merge-output-format "webm"',
            '--write-info-json',
            '--write-thumbnail'
        ];

        if (!empty($cookiefile)) {
            $arguments[] .= '--cookies "' . $cookiefile . '"';
        }

        if (!empty($download_archive)) {
            $arguments[] .= '--download-archive "' . $download_archive . '"';
        }

        if (!empty($output)) {
            $arguments[] .= '--output "' . $output . '"';
        }

        $arguments[] .= escapeshellarg($url);

        $this->sehll($arguments, $stderr, $stdout, $ret);
    }
}