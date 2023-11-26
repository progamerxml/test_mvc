<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Service;

use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Exception\ValidationException;
use PRGANYAR\MVC\TEST\Model\UserRegisterRequest;
use PRGANYAR\MVC\TEST\Model\UserRegisterResponse;
use PRGANYAR\MVC\TEST\Repository\UserRepository;
use PRGANYAR\MVC\TEST\Model\UserLoginRequest;
use PRGANYAR\MVC\TEST\Model\UserLoginResponse;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validationUserRegister($request);

        try{
            Database::start();

            $user = $this->userRepository->findById($request->id);
            if($user != null)
            {
                throw new ValidationException("Wis ana !");
            }
            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commit();

            return $response;
        }catch (\Exception $e) {
            Database::rollback();
            throw $e;
        }
        
    }

    public function validationUserRegister(UserRegisterRequest $request)
    {
        if($request->id == null || $request->name == null || $request->password == null ||
        trim($request->id) == '' ||trim($request->name) == '' || trim($request->password) == '')
        {
            throw new ValidationException("Id, Nama, Dan Password ra olih kosong !");
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLogin($request);

        $user = $this->userRepository->findById($request->id);
        if($user == null)
        {
            throw new ValidationException("Id atau Passworde SALAH !");
        }

        if(password_verify($request->password, $user->password))
        {
            $response = new UserLoginResponse();
            $response ->user = $user;
            return $response;
        }else{
            throw new ValidationException("Id atau Passworde SALAH !");
        }

    }

    public function validateUserLogin(UserLoginRequest $request)
    {
        if($request->id == null || $request->password == null ||
        trim($request->id) == '' || trim($request->password) == '')
        {
            throw new ValidationException("Id, dan Password ra olih kosong !");
        }
    }
}
