<?php


namespace App\Controller\V1\Room;


use App\DTO\Room\RoomCreateDTO;
use App\Enum\CacheEnums;
use App\Service\Room\RoomService;
use App\Utility\Cache\Cache;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RoomController
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RoomService
     */
    private $roomService;

    /**
     * @var
     */
    private $cache;

    public function __construct(LoggerInterface $logger, RoomService $roomService)
    {
        $this->logger = $logger;
        $this->roomService = $roomService;
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function createNew(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        $customer = $this->roomService->addNew(new RoomCreateDTO($data));

        $statusCode = 200;

        if ($customer->errorInfo()[2])
        {
            $this->logger->info($customer->errorInfo()[2], ['RoomController']);

            $statusCode = 400;
            $resp = [
                'status'  => $statusCode,
                'message' => $customer->errorInfo()[2],
            ];
        }
        else
        {
            $resp = [
                'status'  => $statusCode,
                'message' => 'Room created successfully!'
            ];
        }

        $resp = json_encode($resp, JSON_PRETTY_PRINT);
        $response =  $response->withHeader('Content-type', 'application/json');
        $response =  $response->withStatus($statusCode);
        $response->getBody()->write($resp);
        return $response;
    }


    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getAll(Response $response)
    {
        $this->logger->info('getAllCustomer method called');

        $rooms = $this->roomService->getAll();

        //REDIS CACHE
//
//        if ($this->cache->get(CacheEnums::ROOM_CACHE_KEY))
//        {
//            $rooms = $this->cache->get(CacheEnums::ROOM_CACHE_KEY);
//        }else{
//            $rooms = $this->roomService->getAll();
//            $this->cache->set(CacheEnums::ROOM_CACHE_KEY, $rooms, 86400);
//        }

        $resp = [
            'data' => $rooms,
        ];

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($resp));

        return $response->withStatus(200);
    }

    public function getSingle(Request $request, Response $response, $id)
    {
        $customer = $this->roomService->getSingle($id);

        $resp = [
            'data' => $customer,
        ];

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($resp));

        return $response->withStatus(200);
    }


    public function bookRoom(Request $request, Response $response, $roomNumber)
    {
        $bookingData = $request->getParsedBody();

        $roomBooked = $this->roomService->bookRoom($roomNumber, $bookingData);

        $resp = [
            'data' => $roomBooked,
        ];

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($resp));

        return $response->withStatus(200);

    }
}