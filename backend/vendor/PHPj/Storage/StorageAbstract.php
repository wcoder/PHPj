<?php

namespace PHPj\Storage;

abstract class StorageAbstract
{

    protected static $data = array();


    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset(self::$data[$key]);
    }


    /**
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        self::$data[$key] = $value;
    }


    /**
     * @param string $key
     * @return mixed|false
     */
    public static function get($key)
    {
        if (self::has($key)) {
            return self::$data[$key];
        } else {
            return false;
        }
    }

}