<?php

namespace Application\Controllers;

use \PHPj\Mvc\Controller;
use \PHPj\Mvc\View;

class Index extends Controller
{
    public function index()
    {

        return View::make(array(
            //'layout' => false,
            'params' => array(
                'a' => 1,
            ),
        ));
    }
}