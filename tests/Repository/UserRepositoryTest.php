<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Repository;

use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\User;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = "test1";
        $user->name = "Test Satu";
        $user->password = "rahasia";

        $this->userRepository->save($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($result->id, $user->id);
        self::assertEquals($result->name, $user->name);
        self::assertEquals($result->password, $user->password);
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById('coba');
        self::assertNull($user);
    }
}
