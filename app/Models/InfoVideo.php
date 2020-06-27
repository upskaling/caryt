<?php

class InfoVideo
{

    public function __construct($filename)
    {
        $this->info = $this->InfoJson($filename);
    }

    public function InfoJson($filename)
    {
        return json_decode(file_get_contents($filename), true);
    }

    public function ThumbnailBasename()
    {
        return pathinfo($this->info['thumbnail'], PATHINFO_EXTENSION);
    }
}
