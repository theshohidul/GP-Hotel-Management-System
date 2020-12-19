<?php


namespace App\Repository\Payment;


use App\DTO\Booking\BookingDTO;
use App\DTO\Payment\PaymentCreateDTO;
use App\DTO\Room\RoomCreateDTO;
use DI\Container;
use Medoo\Medoo;

class PaymentRepository
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
        return $this->database->get('payments', '*', [
            'id' => $id
        ]);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->database->select('payments', '*');
    }

    /**
     * @param RoomCreateDTO $roomCreateDTO
     * @return bool|\PDOStatement
     */
    public function add(PaymentCreateDTO $paymentCreateDTO)
    {
        return $this->database->insert('payments', (array)$paymentCreateDTO);
    }
}