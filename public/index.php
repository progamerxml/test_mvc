<?php

require_once __DIR__ . "/../vendor/autoload.php";

use PRGANYAR\MVC\TEST\App\Route;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Controller\HomeController;
use PRGANYAR\MVC\TEST\Controller\UserController;

Database::getConnection('prod');

Route::add("GET", "/", HomeController::class, "index", []);
Route::add("GET", "/error/404", HomeController::class, "error", []);

Route::add("GET", "/users/register", UserController::class, "register", []);
Route::add("POST", "/users/register", UserController::class, "postRegister", []);

Route::gas();