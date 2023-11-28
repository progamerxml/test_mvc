<?php

namespace PRGANYAR\MVC\TEST\Service;

use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\Session;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Repository\SessionRepository;
use PRGANYAR\MVC\TEST\Repository\UserRepository;

class SessionService
{
    public static string $COOKIE_NAME = "X-PRG-ANYRN";
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $connection = Database::getConnection();
        $this->sessionRepository = new SessionRepository($connection);
        $this->userRepository = new UserRepository($connection);
    }

    public function create(string $userId): Session
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $userId;

        $this->sessionRepository->save($session);

        setcookie(self::$COOKIE_NAME, $session->id, time() + (60*60*24), "/");

        return $session;
    }

    public function destroy(): void
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($sessionId);

        setcookie(self::$COOKIE_NAME, '', 1, "/");
    }

    public function current(): ?User
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $session = $this->sessionRepository->findById($sessionId);

        if($session == null)
        {
            return null;
        }

        return $this->userRepository->findById($session->user_id);
    }
}
