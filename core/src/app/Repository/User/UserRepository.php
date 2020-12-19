<?php


namespace App\Repository\User;


use DI\Container;
use Medoo\Medoo;

/**
 * Class UserRepository
 * This repository class is responsible only for user related queries
 * @package App\app\Repository\V1
 */
class UserRepository
{

    /**
     * @var mixed|Medoo
     */
    private $database;

    /**
     * UserRepository constructor.
     * @param Container $c
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct(Container $c)
    {
        $this->database = $c->get('database');
    }

    /**
     * @return bool
     */
    public function createTables()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS users (
                        id serial PRIMARY KEY,
                        name character varying(100) NOT NULL,
                        email character varying(255) NOT NULL UNIQUE ,
                        password character varying(255) NOT NULL )';


        return $this->database->query($sql)->execute();
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->database->select('users', '*');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->database->get('users', '*', [
            'id' => $id
        ]);
    }

    /**
     * @param $email
     * @param $password
     * @return mixed
     */
    public function authUser($email, $password)
    {
        return $this->database->get('users', '*', [
            'email' => $email,
            'password' => $password
        ]);
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     * @return bool|\PDOStatement
     */
    public function add(UserCreateDTO $userCreateDTO)
    {
        return $this->database->insert('users', (array)$userCreateDTO);
    }

}