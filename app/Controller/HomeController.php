<?php

namespace PRGANYAR\MVC\TEST\Controller;

use PRGANYAR\MVC\TEST\App\View;

class HomeController
{
    public function index()
    {
        View::view('Home/index', [
            'title' => 'Beranda'
        ]);
    }

    public function error()
    {
        View::view('Error/404', [
            'title' => '404 Not Found',
            'content' => 'rA kEteMU ! EmpaTnOlemPAt'
        ]);
    }
}
