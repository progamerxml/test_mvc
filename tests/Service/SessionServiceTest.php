<?php

namespace PRGANYAR\MVC\TEST\Service;

use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\Session;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Repository\SessionRepository;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Service\SessionService;

function setcookie(string $name, string $value){
    echo "$name: $value";
}

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "prganyrn";
        $user->name = "Progammer Anyaran";
        $user->password = "prganyrn";

        $this->userRepository->save($user);
    }

    public function testCreateSuccess()
    {
        $session = $this->sessionService->create('prganyrn');

        self::expectOutputRegex("[X-PRG-ANYRN: $session->id]");

        $result = $this->sessionRepository->findById('prganyrn');

        self::assertEquals('eko', $result->user_id);
    }

    public function testDestroy()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "prganyrn";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();

        self::expectOutputRegex('[X-PRG-ANYRN: ]');

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "prganyrn";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->user_id, $user->id);
    }

    public function testCurrentNull()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "prganyrn";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->user_id, $user->id);
    }
}
