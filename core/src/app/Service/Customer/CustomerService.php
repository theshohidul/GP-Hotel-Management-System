<?php

namespace App\Service\Customer;

use App\DTO\Customer\CustomerCreateDTO;
use App\Repository\Customer\CustomerRepository;

class CustomerService
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * CustomerService constructor.
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param CustomerCreateDTO $customerCreateDTO
     * @return bool|\PDOStatement
     */
    public function addNew(CustomerCreateDTO $customerCreateDTO)
    {
        return $this->customerRepository->add($customerCreateDTO);
    }

    /**
     * @param int $id
     * @return array|string[]
     */
    public function getSingle($id = 0)
    {
        return $this->customerRepository->get($id);
    }


    /**
     * @return \string[][]
     */
    public function getAll()
    {
        return $this->customerRepository->getAll();
    }

}