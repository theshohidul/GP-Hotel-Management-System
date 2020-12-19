<?php


namespace App\DTO\Booking;


use App\DTO\AbstractDTO;
use Webmozart\Assert\Assert;

class BookingDTO extends AbstractDTO
{
    public string $room_number;
    public string $room_id;
    public string $arrival;
    public string $checkout;
    public string $customer_id;
    public string $book_type;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
    }
}