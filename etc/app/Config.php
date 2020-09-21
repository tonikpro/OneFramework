<?php
namespace etc\app;

class Config
{
    private $contents;

    public function __construct()
    {
        $this->contents = [];
        if ($handle = opendir(__DIR__ . '/../../config/')) {
            while (($entry = readdir($handle)) !== false) 
            {
                if ($entry != "." && $entry != "..") {
                    $contents = require __DIR__ . '/../../config/' . $entry;
                    $this->contents = array_merge_recursive($this->contents, $contents);
                }
            }
            closedir($handle);
        }
    }

    public function get($name)
    {
        if(!array_key_exists($name, $this->contents)){
            return null;
        }
        return $this->contents[$name];
    }
}