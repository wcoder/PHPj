<?php

namespace PHPj\Mvc;

class Controller
{
    protected $data = array();
    protected $view = null;

    /**
     * Конструктор. Запускается одиножды - в классе-роутере, при инициализации контроллера
     * @param array $args дополнительные параметры из адресной строки
     */
    public function __construct(array $args = array())
    {
        $this->data += $args;
    }

    /**
     * Возврат значение переменной из адресной строки
     * @param string $param название переменной
     * @return string значение переменной
     */
    public function __get($param)
    {
        return isset($this->data[$param]) ? $this->data[$param] : false;
    }


}