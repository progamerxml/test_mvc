<?php

namespace PRGANYAR\MVC\TEST\App;

class View
{
    public static function view(string $view, $model): void
    {
        require_once __DIR__ . "/../View/Templates/header.php";
        require_once __DIR__ . "/../View/" . $view . ".php";
        require_once __DIR__ . "/../View/Templates/footer.php";
    }

    public static function redirect(string $url): void
    {
        header("Location: $url");
        if(getenv("mode") != "test")
        {
            exit();
        }
    }
}
