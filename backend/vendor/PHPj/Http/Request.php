<?php

namespace PHPj\Http;

class Request
{
    protected $url;
    protected $method;
    

    public function __construct()
    {
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return isset($_SERVER['REQUEST_URI'])
                ? $_SERVER['REQUEST_URI']
                : '/';
    }


    /**
     * @return string
     */
    public function getMethod()
    {
        return isset($_SERVER['REQUEST_METHOD'])
                ? $_SERVER['REQUEST_METHOD']
                : 'GET';
    }


    /**
     * @return bool
     */
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }


    /**
     * @return bool
     */
    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }


    /**
     * @return bool
     */
    public static function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }


    /**
     * @return bool
     */
    public static function isPostAjax()
    {
        if (self::isAjax() && self::isPost()) {
            return (bool) str_replace(  $_SERVER['SERVER_NAME'],
                                        '',
                                        $_SERVER['HTTP_REFERER']);
        }

        return false;
    }

    
    /**
     * Получить версию используемого HTTP протокола
     *
     * @return string
     */
    public static function getHttpProtocol()
    {
        return isset($_SERVER['SERVER_PROTOCOL'])
                ? $_SERVER['SERVER_PROTOCOL']
                : 'HTTP/1.1';
    }
}