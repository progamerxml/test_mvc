<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Repository;

use PHPUnit\Framework\TestCase;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\Session;
use PRGANYAR\MVC\TEST\Domain\User;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->sessionRepository = new SessionRepository($connection);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "prganyrn";
        $user->name = "Progammer Anyaran";
        $user->password = "prganyrn";

        $this->userRepository->save($user);
    }

    public function testSaveSuccess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "prganyrn";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);

        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->user_id, $result->user_id);
    }

    public function testDelByIdSuccess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "prganyrn";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);

        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->user_id, $result->user_id);

        $this->sessionRepository->deleteById($session->id);

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testFindByIdNotFound()
    {
        $result = $this->sessionRepository->findById("notFound");
        self::assertNull($result);
    }
}
