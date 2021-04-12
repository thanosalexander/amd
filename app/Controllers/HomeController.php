<?php

namespace App\Controllers;

use App\View\View;

class HomeController extends Controller
{
    /**
     * @return string
     */
    public function index(): string
    {
        return View::render('index');
    }
}