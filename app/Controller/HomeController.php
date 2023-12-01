<?php

namespace PRGANYAR\MVC\TEST\Controller;

use PRGANYAR\MVC\TEST\App\View;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Repository\SessionRepository;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Service\SessionService;

class HomeController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $sessionRepository = new SessionRepository($connection);

        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }
    public function index()
    {
        $user = $this->sessionService->current();
        if($user == null){
            View::view('Home/index', [
                'title' => 'Beranda'
            ]);
        }else{
            View::view('Home/dashboard', [
                'title' => 'Dashboard',
                'user' => [
                    'name' => $user->name
                ]
            ]);
        }
    }

    public function error()
    {
        View::view('Error/404', [
            'title' => '404 Not Found',
            'content' => 'rA kEteMU ! EmpaTnOlemPAt'
        ]);
    }
}
