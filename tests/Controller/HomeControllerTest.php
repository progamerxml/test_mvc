<?php

namespace PRGANYAR\MVC\TEST\Controller;
use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\App\View;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\Session;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Repository\SessionRepository;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Service\SessionService;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    

    public function __construct()
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->homeController = new HomeController();

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testGuest()
    {
        $this->homeController->index();

        self::expectOutputRegex('[Mvc Test]');
    }

    public function testUserLogin()
    {
        $user = new User();
        $user->id = "test";
        $user->name = "Name Testing";
        $user->password = "erhaes123";

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user->id;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->homeController->index();

        self::expectOutputRegex('[Halo Name Testing]');
    }

}
