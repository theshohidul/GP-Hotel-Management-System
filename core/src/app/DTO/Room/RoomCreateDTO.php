<?php


namespace App\DTO\Room;


use App\DTO\AbstractDTO;
use Webmozart\Assert\Assert;

class RoomCreateDTO extends AbstractDTO
{
    public string $room_number;
    public string $price;
    public string $locked;
    public string $max_persons;
    public string $room_type;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        Assert::stringNotEmpty($this->room_number);
        Assert::stringNotEmpty($this->price);
        Assert::stringNotEmpty($this->locked);
    }
}