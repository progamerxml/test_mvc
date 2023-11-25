<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Middleware;

class AuthMiddleware implements Middleware
{
    public function cek(): void
    {
        session_start();
        if(!isset($_SESSION['user']))
        {
            header("Location: /login");
            exit();
        }
    }
}
