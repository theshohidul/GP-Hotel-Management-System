<?php

namespace App\Service\User;

use App\Repository\User\UserCreateDTO;
use App\Repository\User\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return \string[][]
     */
    public function getAllUser()
    {
        return $this->userRepository->getAll();
    }

    /**
     * @param int $id
     * @return array|string[]
     */
    public function getSingleUser($id = 0)
    {
        return $this->userRepository->get($id);
    }

    public function add(UserCreateDTO $userCreateDTO)
    {
        return $this->userRepository->add($userCreateDTO);
    }
}