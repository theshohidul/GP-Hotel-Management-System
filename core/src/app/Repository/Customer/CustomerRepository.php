<?php


namespace App\Repository\Customer;


use App\DTO\Customer\CustomerCreateDTO;
use DI\Container;
use Medoo\Medoo;


class CustomerRepository
{

    /**
     * @var mixed|Medoo
     */
    private $database;

    /**
     * CustomerRepository constructor.
     * @param Container $c
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct(Container $c)
    {
        $this->database = $c->get('database');
    }


    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->database->get('customers', '*', [
            'id' => $id
        ]);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->database->select('customers', '*');
    }

    /**
     * @param CustomerCreateDTO $customerCreateDTO
     * @return bool|\PDOStatement
     */
    public function add(CustomerCreateDTO $customerCreateDTO)
    {
        return $this->database->insert('customers', (array)$customerCreateDTO);
    }

}