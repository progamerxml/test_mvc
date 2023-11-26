<?php

namespace PRGANYAR\MVC\TEST\Controller;
use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Repository\UserRepository;

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userController = new UserController();
        $this->userRepository = new UserRepository(Database::getConnection());
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
}
