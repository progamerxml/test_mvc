<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Service;

use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Repository\UserRepository;

class UserServiceTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testRegistSuccess()
    {
        
    }
}
