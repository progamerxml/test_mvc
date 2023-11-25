<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Controller;

use Exception;
use PRGANYAR\MVC\TEST\App\View;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Exception\ValidationException;
use PRGANYAR\MVC\TEST\Model\UserRegisterRequest;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Service\UserService;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
    }

    public function register()
    {
        View::view('User/register', [
            'title' => 'Register',
            'heading' => 'Register User Baru'
        ]);
    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try{
            $this->userService->register($request);
            View::redirect('/users/login');
        }catch(ValidationException $err){
            View::view('User/register', [
                'title' => 'Register',
                'heading' => 'Register User Baru',
                'error' => $err->getMessage()
            ]);
        }
    }
}
