<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Service;

use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Exception\ValidationException;
use PRGANYAR\MVC\TEST\Model\UserRegisterRequest;
use PRGANYAR\MVC\TEST\Service\UserService;
use PRGANYAR\MVC\TEST\Repository\UserRepository;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

        $this->userRepository->deleteAll();
    }

    public function testRegistSuccess()
    {
        $request = new UserRegisterRequest();
        $request->id = "test33";
        $request->name = "Name Testing";
        $request->password = "eR.HA.eS";

        $response = $this->userService->register($request);

        self::assertEquals($response->user->id, $request->id);
        self::assertEquals($response->user->name, $request->name);
        self::assertNotEquals($response->user->password, $request->password);

        self::assertTrue(password_verify($request->password, $response->user->password));
    }

    public function testRegisterFailed()
    {
        self::expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->id = "";
        $request->name = "Name Testing";
        $request->password = "";

        $this->userService->register($request);
    }

    public function testRegisterDuplicate()
    {
        $user = new User();
        $user->id = "test33";
        $user->name = "Name Testing";
        $user->password = "eR.HA.eS";

        $this->userRepository->save($user);

        self::expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "test33";
        $request->name = "Name Testing";
        $request->password = "eR.HA.eS";

        $this->userService->register($request);
    }
}
