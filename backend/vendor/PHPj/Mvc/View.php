<?php

namespace PHPj\Mvc;

use \PHPj\Config\Config;
use \PHPj\Http\Response;

class View
{
    protected static $viewPath;
    protected static $configs;

    protected static $layout = true;
    protected static $template = false;
    protected static $cache = true;
    protected static $data = array();


    public function __get($key)
    {
        return isset(self::$data[$key]) ? self::$data[$key] : false;
    }


    public function __set($key, $value)
    {
        return self::$data[$key] = $value;
    }


    /**
     * @param string $format
     * @param array $args
     * @return string
     */
    public function trans(string $format, array $args = array()) {
        return \PHPj\Translate\Adapter::getInstance()->trans($format, $args);
    }


   /* public function style($file)
    {
        $file = $this->getFrontendPath() . "css/{$file}";
        Response::send("<link rel=\"stylesheet\" href=\"{$file}.css\">" . PHP_EOL);
    }


    public function script($file)
    {
        $file = $this->getFrontendPath() . "js/{$file}";
        Response::send("<script src=\"{$file}.js\"></script>" . PHP_EOL);
    }


    public function getFrontendPath()
    {
        $bp = Config::get('base_path') == '' ? '/' : Config::get('base_path');
        return $bp . Config::get('frontend_path');
    }*/



    public static function make($options = false)
    {
        self::getConfigs();
        self::setOptions($options);
        self::setViewPath();

        $content = self::makeAction();

         if (self::$layout) {
            require_once self::$viewPath . '/layout.html';
        } else {
            Response::send($content);
        }
    }

    protected static function getConfigs()
    {
        self::$configs = Config::get('private');
    }

    protected static function setOptions(array $options)
    {
        if ($options) {
            if (isset($options['layout'])) {
                self::$layout = $options['layout'];
            }
            if (isset($options['template'])) {
                self::$template = $options['template'];   
            }
            if (isset($options['cache'])) {
                self::$cache = $options['cache'];    
            }
            if (isset($options['params'])) {
                self::$data += $options['params'];   
            }
        }
    }

    public static function setViewPath()
    {
        self::$viewPath = Config::get('system_path') . '/modules/'
                        . self::$configs['Module'] . '/Views';
        set_include_path(get_include_path() . PATH_SEPARATOR . self::$viewPath);
    }


    protected static function makeAction()
    {
        if (!self::$template) {
            self::$template = self::$configs['Action'];
        }
        $controller = str_replace('\\', '/', strtolower(self::$configs['Controller']));

        ob_start();
            require_once $controller . '/' . self::$template . '.html';
            $html = ob_get_contents() . PHP_EOL;
        ob_end_clean();

        return $html;
    }


    protected static function makeLayout()
    {

    }
}