<?php

require_once __DIR__ . "/../vendor/autoload.php";

use PRGANYAR\MVC\TEST\App\Route;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Controller\HomeController;
use PRGANYAR\MVC\TEST\Controller\UserController;
use PRGANYAR\MVC\TEST\Middleware\MustLoginMiddleware;
use PRGANYAR\MVC\TEST\Middleware\MustNotLoginMiddleware;

Database::getConnection('prod');

Route::add("GET", "/", HomeController::class, "index", []);
Route::add("GET", "/error/404", HomeController::class, "error", []);

Route::add("GET", "/users/register", UserController::class, "register", [MustNotLoginMiddleware::class]);
Route::add("POST", "/users/register", UserController::class, "postRegister", [MustNotLoginMiddleware::class]);
Route::add("GET", "/users/login", UserController::class, "login", [MustNotLoginMiddleware::class]);
Route::add("POST", "/users/login", UserController::class, "postLogin", [MustNotLoginMiddleware::class]);
Route::add("GET", "/users/logout", UserController::class, "logout", [MustLoginMiddleware::class]);

Route::gas();