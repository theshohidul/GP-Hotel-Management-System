<?php


namespace App\Service\Booking;


use App\DTO\Booking\BookingDTO;
use App\Repository\Booking\BookingRepository;

class BookingService
{
    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * @param int $id
     * @return array|string[]
     */
    public function getSingle($id = 0)
    {
        return $this->bookingRepository->get($id);
    }


    /**
     * @return \string[][]
     */
    public function getAll()
    {
        return $this->bookingRepository->getAll();
    }

    public function getBookInfoByRoomNumber($roomNumber)
    {
        return $this->bookingRepository->getBookingInfoByRoomNumber($roomNumber);
    }

    public function addNew(BookingDTO $bookingDTO)
    {
        return $this->bookingRepository->add($bookingDTO);
    }
}