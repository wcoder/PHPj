<?php

namespace PHPj\Router;

use \PHPj\Http\Request;
use \PHPj\Http\Response;
use \PHPj\Config\Config;

class Router
{
    protected static $url = false;
    protected static $method = false;

    // управляющие параметры
    protected static $module = false;
    protected static $controller = false;
    protected static $action = false;

    // переменные из адресной строки
    protected static $params = false;


    public function __construct(Request $request)
    {
        try {
            $this->url = $request->getUrl();
            $this->method = $request->getMethod();

            $this->initModule();

            Config::set($this->loadConfig());

            $this->handler();

            Config::set($this->getControlParams());

            $this->loadBootstrap();
            $this->loadController();
            $this->callAction();

        } catch (\Exception $e) {
            $this->getError($e->getMessage());
        }
    }


    protected function handler()
    {
        $options = false;

        $this->url = preg_replace('/^\/' . strtolower($this->module) . '\/?/', '', $this->url);

        foreach (Config::get('routes') as $key => $route) {
            if ($this->isMatchRoute($route)) {
                $options = $route[2];
                break;
            }
        }

        if ($options) {
            $this->analysisParams();

            $this->checkControlParam($options, 'controller');
            $this->checkControlParam($options, 'action');

        } else {
            $this->getError('Page not found!');
        }
    }


    /**
     * @param array $route
     * @return bool
     */
    protected function isMatchRoute(array $route)
    {
        if (!in_array($this->method, explode('|', $route[0]))) {
            return false;
        }
        
        return (bool) preg_match('/^\/?' . $route[1] . '\/?$/', $this->url, $this->params);
    }

    protected function analysisParams()
    {
        $this->controller = $this->searchParam('controller');
        $this->action = $this->searchParam('action');

        $this->params = $this->removeAssocKeys($this->params);
    }


    /**
     * Проверка управляющих параметров (контроллер, действие)
     * @param array $options опции из конфигураций маршрута
     * @param string $name имя параметра
     * @throws \Exception неопределен управляющий параметр
     */
    protected function checkControlParam(array $options, $name)
    {
        if (!$this->$name) {
            if ($options[$name]) {
                $this->$name = $options[$name];
            } else {
                throw new \Exception('404 [' . $name . ']');
            }
        }
    }


    /**
     * Поиск параметра по ключу в массиве параметров
     * @param string $name имя параметра
     * @return bool|string
     */
    protected function searchParam($name)
    {
        $param = false;

        if (isset($this->params[$name])) {
            $param = $this->params[$name];
            unset($this->params[$name]);
        }

        return $param;
    }


    /**
     * Удаление числовых ключений из массива
     * @param array $a смешанный массив
     * @return array ассоциативный массив
     */
    private function removeAssocKeys(array $a)
    {
        $t = array();
        foreach ($a as $k => $v) {
            if (!is_int($k)) $t[$k] = $v;
        }
        return $t;
    }

    protected function getError($message)
    {
        if (Config::get('dev')) {
            Response::status(404, TRUE, $message);
        } else {            
            Response::status(404, TRUE);
            Logger::message($message, Log::ERROR);
        }
        exit;
    }


    protected function initModule()
    {
        $modules = Config::get('modules');
        $load_module = Config::get('default_module');

        foreach ($modules as $module) {
            if (preg_match('/^\/(' . $module . ')/', $this->url, $matches)) {
                $load_module = $matches[1];
                break;
            }
        }

        $this->module = ucfirst($load_module);
    }


    /**
     * Загрузка загрузачного файла модуля
     */
    protected function loadBootstrap()
    {
        $class_name = '\\' . $this->module . '\Bootstrap';
        $class = new $class_name;

        $methods = get_class_methods($class);

        foreach ($methods as $method) {
            call_user_func(array($class, $method));
        }
    }


    /**
     * Загрузка конфигураций модуля
     * @return array
     */
    protected function loadConfig()
    {
        $config_path = Config::get('system_path') . '/modules/' . $this->module . '/Configs/';
        $configs = array();

        if (is_file($config_path . 'global.php')) {
            $configs = require_once $config_path . 'global.php';
        } else {
            throw new \Exception('Cannot load configuration file for module!');
        }

        if (Config::get('dev')
            && is_file($config_path . 'local.php')) {
            $local_configs = require_once $config_path . 'local.php';
            $configs = array_replace_recursive($configs, $local_configs);
        }

        return $configs;
    }


    /**
     * Управляющие параметры
     * @return array
     */
    public function getControlParams()
    {
        return array('private' => array(
            'Module'           => $this->module,
            'Controller'       => $this->controller,
            'Action'           => $this->action,
            'ApplicationPath'  => Config::get('system_path') . '/modules/' . $this->module,
        ));
    }


    /**
     * Инициализация класса контроллера
     */
    protected function loadController()
    {
        $class_name = '\\' . $this->module . '\Controllers\\'
                    . ucfirst($this->controller);

        $this->controller = new $class_name($this->params);
    }


    /**
     * Вызов метода (действия) из класса контроллера
     */
    protected function callAction()
    {
        if (is_callable(array($this->controller, $this->action)) === FALSE) {
            throw new \Exception('Аction not defined');
        }

        Response::send(call_user_func(array($this->controller, $this->action)));
    }

}