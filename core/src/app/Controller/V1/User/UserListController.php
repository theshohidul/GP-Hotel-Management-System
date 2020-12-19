<?php

namespace App\Controller\V1\User;

use App\Controller\BaseController;
use App\Presenter\V1\UserPresenter;
use App\Service\Logger\Log;
use App\Service\User\UserService;
use App\Utility\Config;
use App\Utility\User\Device;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function DI\autowire;
use function DI\create;

/**
 * Class UserListController
 * This controller is responsible for only get user list
 * @package App\app\Controller\V1
 */
class UserListController extends BaseController
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var UserService
     */
    private $userListService;
    /**
     * @var ContainerInterface
     */
    //private ContainerInterface $container;
    /**
     * @var Log
     */
    //private Log $log;

    /**
     * UserListController constructor.
     * @param UserService $userListService
     */
    public function __construct(LoggerInterface $logger, UserService $userListService, ContainerInterface $container, Log $log)
    {
        parent::__construct($container);

        $this->logger = $logger;
        $this->userListService = $userListService;
        //$this->container = $container;
        //$this->log = $log;
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getAllUser(Request $request, Response $response)
    {
        $this->logger->info('getAllUser method called');

        $users = $this->userListService->getAllUser();

        $resp = [
            'data' => $users,
        ];

        return $this->response([], 404, []);

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($resp));

        return $response->withStatus(200);
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getSingleUser(Request $request, Response $response, $id)
    {
        $user_id = $id;

        $user = $this->userListService->getSingleUser($user_id);

        $resp = [
            'data' => $user,
        ];

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($resp));

        return $response->withStatus(200);
    }

    public function abc(Request $request)
    {

        $resp = $this->response([], 200);
        dd($this->log->getPayloadAsArray());
    }
}