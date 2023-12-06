<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Controller;

use Exception;
use PRGANYAR\MVC\TEST\App\View;
use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Exception\ValidationException;
use PRGANYAR\MVC\TEST\Model\UserLoginRequest;
use PRGANYAR\MVC\TEST\Model\UserPasswordUpdateRequest;
use PRGANYAR\MVC\TEST\Model\UserProfileUpdateRequest;
use PRGANYAR\MVC\TEST\Model\UserRegisterRequest;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Repository\SessionRepository;
use PRGANYAR\MVC\TEST\Service\SessionService;
use PRGANYAR\MVC\TEST\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
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

    public function login()
    {
        View::view('User/login', [
            "title" => "Login",
            "heading" => "Login User"
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try{
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect('/');
        }catch(ValidationException $err){
            View::view('User/login', [
                "title" => "Login",
                "heading" => "Login User",
                "error" => $err->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect('/');
    }

    public function updateProfile()
    {
        $user = $this->sessionService->current();
        View::view('User/profile', [
            "title" => "Update Profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ]
        ]);
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();
        $request = new UserProfileUpdateRequest();

        $request->id = $user->id;
        $request->name = $_POST['name'];

        try{
            $this->userService->updateProfile($request);
            View::redirect('/');
        }catch(ValidationException $err){
            View::view('User/profile', [
                "title" => "Update Profile",
                "error" => $err->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $_POST['name']
                ]
            ]);
        }
    }

    public function updatePassword()
    {
        $user = $this->sessionService->current();

        View::view('User/password', [
            "title" => "Update user password",
            "user" => [
                "id" => $user->id
            ]
        ]);
    }

    public function postUpdatePassword()
    {
        $user = $this->sessionService->current();
        $request = new UserPasswordUpdateRequest();
        $request->id = $user->id;
        $request->oldPassword = $_POST['oldPassword'];
        $request->newPassword = $_POST['newPassword'];

        try{
            $this->userService->updatePassword($request);
            View::redirect('/');
        }catch(\Exception $err){
            View::view('User/password', [
                "title" => "Update user password",
                "error" => $err->getMessage(),
                "user" => [
                    "id" => $user->id
                ]
            ]);
        }
    }
}
