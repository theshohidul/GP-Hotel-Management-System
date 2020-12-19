<?php


namespace App\Service\Room;


use App\DTO\Booking\BookingDTO;
use App\DTO\Room\RoomCreateDTO;
use App\Repository\Room\RoomRepository;
use App\Service\Booking\BookingService;

class RoomService
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    private $bookingService;

    public function __construct(RoomRepository $roomRepository, BookingService $bookingService)
    {
        $this->roomRepository = $roomRepository;
        $this->bookingService = $bookingService;
    }

    /**
     * @param RoomCreateDTO $roomCreateDTO
     * @return mixed
     */
    public function addNew(RoomCreateDTO $roomCreateDTO)
    {
        return $this->roomRepository->add($roomCreateDTO);
    }

    /**
     * @param int $id
     * @return array|string[]
     */
    public function getSingle($id = 0)
    {
        return $this->roomRepository->get($id);
    }

    /**
     * @param int $id
     * @return array|string[]
     */
    public function getSingleByRoomNumber($roomNumber)
    {
        return $this->roomRepository->getByRoomNumber($roomNumber);
    }


    /**
     * @return \string[][]
     */
    public function getAll()
    {
        return $this->roomRepository->getAll();
    }


    public function bookRoom($roomNumber, $bookingData)
    {
        $getRoomInfo = $this->getSingleByRoomNumber($roomNumber);

        if($getRoomInfo)
        {
            $bookingData['room_number'] = $roomNumber;
            $bookingData['room_id'] = $getRoomInfo['id'];

            $getRoomBookingInfo = $this->bookingService->getBookInfoByRoomNumber($roomNumber);

            if(!empty($getRoomBookingInfo) && isset($bookingData['arrival']) && $bookingData['arrival'] <= $getRoomBookingInfo['checkout'])
            {
                return false;
            }else
            {
                $this->bookingService->addNew(new BookingDTO($bookingData));
            }
        }else
        {
            return false;
        }
        return true;
    }

}