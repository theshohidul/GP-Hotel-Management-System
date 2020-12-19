<?php


namespace App\DTO\Payment;


use App\DTO\AbstractDTO;
use Webmozart\Assert\Assert;

class PaymentCreateDTO extends AbstractDTO
{
    public string $booking_id;
    public string $customer_id;
    public string $amount;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
    }
}