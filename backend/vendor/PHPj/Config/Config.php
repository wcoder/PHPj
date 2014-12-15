<?php

namespace PHPj\Config;

use \PHPj\Storage\StorageAbstract;

class Config extends StorageAbstract
{
    public static function set($configs)
    {
        self::$data = array_replace_recursive(self::$data, $configs);
    }


    public static function get($key = false)
    {
        return $key ? self::$data[$key] : self::$data;
    }


    public static function loadFile($name)
    {
        $path = self::get('system_path') . '/configs/' . $name . '.php';
        self::set(require_once $path);
    }
}