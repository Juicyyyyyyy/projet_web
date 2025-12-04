<?php

namespace App\Controllers;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function index($request)
    {
        echo $this->render('home', ['title' => 'Welcome to My MVC App']);
    }
}
