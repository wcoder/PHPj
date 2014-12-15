<?php

define('SYS_PATH', __DIR__ . '/backend');

require_once SYS_PATH . '/vendor/autoload.php';

\PHPj\Core\Core::init(SYS_PATH)->run();