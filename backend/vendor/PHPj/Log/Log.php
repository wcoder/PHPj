<?php

namespace PHPj\Log;

use \PHPj\Http\Response;
use \PHPj\Config\Config;
use \PHPj\File\File;

class Log
{
    protected static $configs = array();
    protected static $debug = false;

    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';


    public static function init()
    {
        self::$configs = Config::get('log');
        self::$debug = Config::get('dev');
    }


    public static function message($message, $level = self::ALERT)
    {
        if (self::$debug) {
            self::inDisplay($message, $level);
        } else {
            $message = self::getFormatString($message, $level);

            switch (self::$configs['type']) {
                case 'file':
                    self::inFile($message, $level);
                    break;
            }
        }
    }


    public static function getFormatString($message, $level)
    {
        return date('d-m-Y') . '    |   ' . $level . '  |   ' . $message;
    }


    protected static function inDisplay($message, $level)
    {
        Response::send(
            "<div>"
            .   "<h4>{$level}</h4>"
            .   "<blockquote>{$message}</blockquote>"
            . "</div>"
        );
    }


    protected static function inFile($message, $level)
    {
        $path = Config::get('system_path') . '/' . $level . '.log';
        $file = new File($path);
        $file->data($message);
        $file->save();
    }
}