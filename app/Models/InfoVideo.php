<?php

class InfoVideo
{

    public function __construct(string $filename)
    {
        $this->info = $this->InfoJson($filename);
    }

    public function InfoJson(string $filename)
    {
        return json_decode(file_get_contents($filename), true);
    }

    public function ThumbnailBasename()
    {
        return (string) pathinfo($this->info['thumbnail'], PATHINFO_EXTENSION);
    }
}
