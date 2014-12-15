<?php

namespace PHPj\Http;

use \PHPj\Http\Request;

class Response
{    
    /**
     * @param string $url
     */
    public static function redirect($url)
    {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $url;
        
        if (headers_sent()) {
            echo "<script>document.location.href=" . $url . ";</script>";
        } else {
            self::status(301);
            self::sendHeader('Location: ' . $url);
        }
    }


    /**
     * @param int $error_code
     * @param bool $error_view
     * @param string $error_content
     */
    public static function status(
        $error_code, $error_view = false, $error_content = '')
    {
        switch ($error_code) {
            case 301 :
                $status = 'Moved Permanently';
                break;
            case 400 :
                $status = 'Bad Request';
                break;
            case 401 :
                $status = 'Unauthorized';
                break;
            case 402 :
                $status = 'Unauthorized';
                break;
            case 403 :
                $status = 'Forbidden';
                break;
            case 404 :
                $status = 'Not Found';
                break;
            case 407 :
                $status = 'Proxy Authentication Required';
                break;
            case 408 :
                $status = 'Request Time-out';
                break;
            case 429:
                $status = 'Too many requests';
                break;
            case 500 :
                $status = 'Internal Server Error';
                break;
            case 502 :
                $status = 'Bad Gateway';
                break;
            case 503 :
                $status = 'Service Unavailable';
                break;
            case 504 :
                $status = 'Gateway Timeout';
                break;
            default:
                $status = '';
                break;
        }

        self::sendHeader(Request::getHttpProtocol() . " {$error_code} {$status}");
        self::sendHeader("Status: {$error_code} {$status}");

        // если указан вывод шаблона
        if ($error_view) {
            exit(self::getErrorView($error_code, $status, $error_content));
        }
    }


    /**
     * @param string $string
     */
    public static function sendHeader($string)
    {
        header($string);
    }


    /**
     * Используем шаблон для показа страницы ошибки
     * @param int $error_code
     * @param string $error_status
     * @param string $error_content
     * @return string
     */
    public static function getErrorView(
        $error_code, $error_status, $error_content = '')
    {
        return require_once('frontend/ErrorTemplate.phtml');
    }


    /**
     * @param array|string $data
     */
    public static function sendJson($data)
    {
        self::setContentType('json');
        self::send(json_encode($data));
    }


    /**
     * @param string $type
     */
    public static function setContentType($type = false)
    {
        switch ($type) {
            // для JSON вывода
            case 'json':
                $type = 'application/json';
                break;
            // тип по умолчанию
            default:
                $type = 'text/html';
                break;
        }

        self::sendHeader("Content-type: {$type}; charset=utf-8");
    }   


    public static function send($string)
    {
        echo $string;
    }
}