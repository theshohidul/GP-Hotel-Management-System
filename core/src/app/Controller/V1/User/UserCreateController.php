<?php


namespace App\Controller\V1\User;

use App\Controller\BaseController;
use App\Repository\User\UserCreateDTO;
use App\Service\User\UserService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Class UserCreateController
 * This controller is responsible for only new user creation process
 * @package Controller\V1
 */
class UserCreateController extends BaseController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserCreateController constructor.
     * @param UserService $userService
     * @param ContainerInterface $container
     */
    public function __construct(UserService $userService, ContainerInterface $container)
    {
        $this->userService = $userService;
        parent::__construct($container);
    }

    public function createUser(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        if ($this->userService->add(new UserCreateDTO($data)))
        {
            $resp = [
                'message' => 'V1 created successfully!',
            ];
        }
        else
        {
            $resp = [
                'message' => 'V1 couldn\'t created successfully!'
            ];
        }

        $resp = json_encode($resp, JSON_PRETTY_PRINT);
        $response =  $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write($resp);
        return $response;
    }

    public function showUser(Request $request, Response $response)
    {
        $arr = [
            'app' => [
                'boot_start_at' => microtime(),
                'boot_end_at'   => microtime()
            ],
            'request' => [
                'start_at' => microtime(),
                'end_at'   => microtime(),
                'headers'  => [
                    'Content-Type' => "application/json",
                    'Accept'       => "application/json"
                ],
                'payload'  => [
                    'name' => "Rafsan jani",
                    'age'  => 12,
                    'school' => 'Dhaka School'
                ]
            ],
            'response' => [
                'data' => [
                    'full_name' => "Al Hashemi Rafsan jani",
                    'age' => 12,
                    'college' => "Dhaka college"
                ],
                'headers'  => [
                    'Content-Type' => "application/json",
                    'Accept'       => "application/json"
                ],
            ]
        ];

       return $this->response($arr);
    }
}