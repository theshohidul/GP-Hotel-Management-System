<?php


namespace App\Repository\Booking;


use App\DTO\Booking\BookingDTO;
use App\DTO\Room\RoomCreateDTO;
use DI\Container;
use Medoo\Medoo;

class BookingRepository
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
        return $this->database->get('bookings', '*', [
            'id' => $id
        ]);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->database->select('bookings', '*');
    }

    /**
     * @param RoomCreateDTO $roomCreateDTO
     * @return bool|\PDOStatement
     */
    public function add(BookingDTO $bookingDTO)
    {
        return $this->database->insert('bookings', (array)$bookingDTO);
    }

    public function getBookingInfoByRoomNumber($roomNumber)
    {
        return $this->database->query(
            "SELECT * FROM <bookings> WHERE <room_number> = :room_number ORDER BY book_time DESC", [
                ":room_number" => $roomNumber
            ]
        )->fetch();
    }
}