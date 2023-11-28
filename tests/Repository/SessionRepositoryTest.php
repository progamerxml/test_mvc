<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Repository;

use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->sessionRepository = new SessionRepository($connection);
    }
}
