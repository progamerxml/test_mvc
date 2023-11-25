<?php

declare(strict_types=1);

namespace PRGANYAR\MVC\TEST\Service;

use PRGANYAR\MVC\TEST\Config\Database;
use PRGANYAR\MVC\TEST\Domain\User;
use PRGANYAR\MVC\TEST\Exception\ValidationException;
use PRGANYAR\MVC\TEST\Model\UserRegisterRequest;
use PRGANYAR\MVC\TEST\Model\UserRegisterResponse;
use PRGANYAR\MVC\TEST\Repository\UserRepository;

class UserService2
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegisterRequest($request);

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
            $user->password = $request->password;

            $this -> userRepository -> save($user);
            
            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commit();

            return $response;
        }catch (\Exception $err){
            Database::rollback();
            throw $err;
        }
    }

    public function validateUserRegisterRequest(UserRegisterRequest $request): void
    {
        if($request->id == null || $request->name == null || $request->password == null ||
        trim($request->id) == '' || trim($request->name) == '' || trim($request->password) == '')
        {
            throw new ValidationException("Id, Nama, Password aja kosong!");
        }
    }
}
