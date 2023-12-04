<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Service;

use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Exception\ValidationException;
use PRGANYAR\MVC\TEST\Model\UserLoginRequest;
use PRGANYAR\MVC\TEST\Model\UserProfileUpdateRequest;
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

    public function testLoginNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = "test22";
        $request->password = "passwordtest";

        $this->userService->login($request);
    }

    public function testLoginWrongPassword()
    {
        $user = new User();
        $user->id = "test22";
        $user->name = "Testing Name";
        $user->password = password_hash("passwordtest", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = "test22";
        $request->password = "passwordtestSalah";

        $this->userService->login($request);
    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->id = "test22";
        $user->name = "Testing Name";
        $user->password = password_hash("passwordtest", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $request = new UserLoginRequest();
        $request->id = "test22";
        $request->password = "passwordtestSalah";

        $response = $this->userService->login($request);

        self::assertEquals($user->id, $response->user->id);
        self::assertTrue(password_verify($request->password, $response->user->password));
    }

    public function testUpdateProfileSuccess()
    {
        $user = new User();
        $user->id = "test1";
        $user->name = "Test Name 1";
        $user->password = password_hash("rahasia1", PASSWORD_BCRYPT);

        $this->userRepository->save($user);

        $request = new UserProfileUpdateRequest();
        $request -> id = "test1";
        $request -> name = "Test Name 0";

        $response = $this->userService->updateProfile($request);

        $result = $this->userRepository->findById($response->user->id);

        self::assertEquals($user->id, $result->id);
        
    }

    public function testUpdateProfileValidationError()
    {
        $this->expectException(ValidationException::class);
        $request = new UserProfileUpdateRequest();
        $request -> id = "";
        $request -> name = "";

        $this->userService->updateProfile($request);

    }

    public function testUpdateProfileNotFound()
    {
        $this->expectException(ValidationException::class);
        $request = new UserProfileUpdateRequest();
        $request -> id = "test";
        $request -> name = "Testing 1";

        $this->userService->updateProfile($request);
    }
}
