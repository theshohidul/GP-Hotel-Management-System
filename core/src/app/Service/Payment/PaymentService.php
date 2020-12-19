<?php


namespace App\Service\Payment;


use App\DTO\Payment\PaymentCreateDTO;
use App\Repository\Payment\PaymentRepository;

class PaymentService
{
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }


    public function addNew($bookingId, $paymentData)
    {
        $paymentData['booking_id'] = $bookingId;
        $paymentData['customer_id'] = 1;
        return $this->paymentRepository->add(new PaymentCreateDTO($paymentData));
    }
}