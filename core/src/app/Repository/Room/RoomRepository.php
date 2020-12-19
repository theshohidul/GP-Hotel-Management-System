<?php


namespace App\Repository\Room;


use App\DTO\Customer\CustomerCreateDTO;
use App\DTO\Room\RoomCreateDTO;
use DI\Container;
use Medoo\Medoo;

class RoomRepository
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
        return $this->database->get('rooms', '*', [
            'id' => $id
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getByRoomNumber($roomNumber)
    {
        return $this->database->get('rooms', '*', [
            'room_number' => $roomNumber
        ]);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->database->select('rooms', '*');
    }

    /**
     * @param RoomCreateDTO $roomCreateDTO
     * @return bool|\PDOStatement
     */
    public function add(RoomCreateDTO $roomCreateDTO)
    {
        return $this->database->insert('rooms', (array)$roomCreateDTO);
    }
}