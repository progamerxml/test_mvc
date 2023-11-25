<?php

require_once __DIR__ . "/../vendor/autoload.php";

use PRGANYAR\MVC\TEST\App\Route;
use PRGANYAR\MVC\TEST\Controller\HomeController;

Route::add("GET", "/", HomeController::class, "index", []);
Route::add("GET", "/error/404", HomeController::class, "error", []);

Route::gas();