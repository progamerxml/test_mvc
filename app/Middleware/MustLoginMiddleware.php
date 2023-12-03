<?php

namespace PRGANYAR\MVC\TEST\Middleware;

use PRGANYAR\MVC\TEST\App\View;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Middleware\Middleware;
use PRGANYAR\MVC\TEST\Repository\SessionRepository;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Service\SessionService;

class MustLoginMiddleware implements Middleware
{
    private SessionService $sessionService;
    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

    }

    public function cek(): void
    {
        $user = $this->sessionService->current();
        if($user == null)
        {
            View::redirect('/users/login');
        }
    }
}
