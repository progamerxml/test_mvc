<?php

namespace PRGANYAR\MVC\TEST\Controller;
use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\Session;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Repository\SessionRepository;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Service\SessionService;

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    protected function setUp(): void
    {
        $this->userController = new UserController();
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testRegister()
    {
        $this->userController->register();

        self::expectOutputRegex('[Register]');
        self::expectOutputRegex('[Id]');
        self::expectOutputRegex('[Name]');
        self::expectOutputRegex('[Password]');
        self::expectOutputRegex('[Register User Baru]');
    }

    public function testPostRegisterSuccess()
    {

    }

    public function testPostRegister()
    {
        
    }

    public function testUpdateProfile()
    {
        $user = new User();
        $user->id = "test01";
        $user->name = "Name Test 1";
        $user->password = password_hash("test01", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user->id;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->userController->updateProfile();

        self::expectOutputRegex('[Profile]');
        self::expectOutputRegex('[Id]');
        self::expectOutputRegex('[Name]');
        self::expectOutputRegex('[test01]');
        self::expectOutputRegex('[Name Test 1]');
    }

    public function testUpdateProfileSuccess()
    {
        $user = new User();
        $user->id = "test01";
        $user->name = "Name Test 1";
        $user->password = password_hash("test01", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user->id;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $_POST['name'] = "Test Name 1";
        $this->userController->postUpdateProfile();

        self::expectOutputRegex("[Location: /]");

        $result = $this->userRepository->findById('test01');

        self::assertEquals("Test Name 1", $result->name);
    }

    public function testUpdateProfileValidationException()
    {
        $user = new User();
        $user->id = "test01";
        $user->name = "Name Test 1";
        $user->password = password_hash("test01", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user->id;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $_POST['name'] = "";
        $this->userController->postUpdateProfile();

        self::expectOutputRegex('[Profile]');
        self::expectOutputRegex('[Id]');
        self::expectOutputRegex('[Name]');
        self::expectOutputRegex('[Id, dan Password ra olih kosong !]');
    }
}
