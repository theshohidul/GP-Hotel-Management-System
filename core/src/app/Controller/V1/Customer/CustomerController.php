<?php
namespace App\Controller\V1\Customer;

use App\DTO\Customer\CustomerCreateDTO;
use App\Service\Customer\CustomerService;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CustomerController
{

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * @var CustomerService
     */
    private $customerService;


    /**
     * CustomerController constructor.
     * @param CustomerService $customerService
     */
    public function __construct(LoggerInterface $logger, CustomerService $customerService)
    {
        $this->customerService = $customerService;
        $this->logger = $logger;
    }


    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function signUp(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        $customer = $this->customerService->addNew(new CustomerCreateDTO($data));

        $statusCode = 200;

        if ($customer->errorInfo()[2])
        {
            $this->logger->info($customer->errorInfo()[2], ['CustomerController']);

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
                'message' => 'Customer created successfully!'
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

        $users = $this->customerService->getAll();

        $resp = [
            'data' => $users,
        ];

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($resp));

        return $response->withStatus(200);
    }

    public function getSingle(Request $request, Response $response, $id)
    {
        $customer = $this->customerService->getSingle($id);

        $resp = [
            'data' => $customer,
        ];

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($resp));

        return $response->withStatus(200);
    }


}