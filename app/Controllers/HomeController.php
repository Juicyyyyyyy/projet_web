<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Request;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        $this->render('home', ['title' => 'Welcome to My MVC App']);
    }
}
