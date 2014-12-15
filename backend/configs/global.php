<?php

return array(
	
	// режим рапуска
	'dev' => true,

	// настройки параметров базы данных
	'db' => array(
		'adapter' => 'mysql',
		'host' => 'localhost',
		'username' => '',
		'password' => '',
		'dbname' => '',
	),

	// настройки логирования (для режима отладки)
	'log' => array(
		'type' => 'file',
		'options' => array(),
	),

	// базовый путь
	'base_path' => '',

	// путь расположения статики
	'frontend_path' => 'frontend/',

	// используемые модули
	'modules' => array(
		'application',
	),

	// модуль по умолчанию
	'default_module' => 'application',

	'langs' => array('ru_RU', 'en_US'),

	'session_name' => md5(date('y0m') . '0Hs21340dfLIU'),

	// описание маршрутов
	'routes' => array(

		/* Controller - Index */

		'home' => array('GET', '', array(
			'controller' => 'index',
			'action' => 'index',
		)),

	),

);