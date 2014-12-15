<?php

namespace PHPj\File;

class File
{
    protected $fileName;
    protected $fileData;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function data($data)
    {
        $this->fileData = $data;
    }

    public function save()
    {
        $data = (is_file($this->fileName))
                ? file_get_contents($this->fileName) . PHP_EOL
                : '';
        return file_put_contents($this->fileName, $data . $this->fileData);
    }
}