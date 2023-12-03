<?php

namespace PRGANYAR\MVC\TEST\Middleware;
use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\Session;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Repository\SessionRepository;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Service\SessionService;

class MustLoginMiddlewareTest extends TestCase
{
    private MustLoginMiddleware $mustLoginMiddleware;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->mustLoginMiddleware = new MustLoginMiddleware();
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        putenv("mode=test");
    }

    public function testCekLoginGuest()
    {
        $this->mustLoginMiddleware->cek();

        $this->expectOutputRegex('[Location: /users/login]');
    }

    public function testCekLoginUser()
    {
        $user = new User();
        $user->id = "adm_0";
        $user->name = "Admin";
        $user->password = "adm_0";

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user->id;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->mustLoginMiddleware->cek();
        $this->expectOutputString('');
    }
}
