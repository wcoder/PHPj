<?php

namespace PHPj\Core;

use \PHPj\Log\Log;
use \PHPj\Config\Config;
use \PHPj\Router\Router;
use \PHPj\Http\Request;

/**
 * @package   PHPj
 * @author    Evgeniy Pakalo <evgeniy.pakalo@gmail.com>
 * @copyright 2013 Evgeniy Pakalo
 * @version   0.0.1
 */
class Core
{
    /**
     * @param string $sysPath
     * @return \PHPj\Core\Core
     */
    public static function init($sysPath)
    {
        Config::set(array(
            'system_path' => $sysPath
        ));
        self::loadConfigs();

        Log::init();

        self::setEncoding('UTF-8');
        return new self();
    }


    public function loadConfigs()
    {
        Config::loadFile('global');

        if (Config::get('dev')) {
            Config::loadFile('local');
        }
    }


    /**
     * @param string $enc
     */
    public function setEncoding($enc)
    {
        mb_internal_encoding($enc);
        ini_set('default_charset', $enc);
    }


    /**
     * @throws \Exception
     */
    public function run()
    {
        try {

            new Router(new Request());

        } catch (\Exception $e) {

            Log::message($e->getMessage(), Log::ERROR);

        }
    }
}