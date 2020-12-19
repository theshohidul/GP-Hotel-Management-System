<?php


namespace App\Service\Auth;


use App\Repository\User\UserRepository;

class AuthService
{

    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function auth($email,$password)
    {
        return $this->userRepository->authUser($email,$password);
    }
}