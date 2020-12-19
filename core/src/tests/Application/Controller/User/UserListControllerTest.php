<?php

namespace Test\Application\Controller\User;

use App\Application\Service\User\UserListService;
use Controller\User\UserListController;
use PHPUnit\Framework\TestCase;
use Mockery;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UserListControllerTest extends TestCase
{

    public function tearDown():void
    {
        Mockery::close();
    }

    /**
     * Test
     */
    public function testSingleUser(): void
    {
        $mockRequest  = Mockery::mock(Request::class);
        $mockResponse = new Response();

        $mockLogger = Mockery::mock(LoggerInterface::class);

        $mockUserListService = Mockery::mock(UserListService::class);
        $mockUserListService->shouldReceive('getSingleUser')
                            ->once()
                            ->andReturn(['id'=>1]);

        $userListController = new UserListController($mockLogger, $mockUserListService);


        $user = $userListController->getSingleUser($mockRequest,$mockResponse,1);

       self::assertEquals(200,$user->getStatusCode());
    }

//    /**
//     * Test
//     */
//    public function testAllUser(): void
//    {
//        $mockUserRepository = Mockery::mock(UserListController::class);
//        $mockRequest = Mockery::mock(Request::class);
//        $mockResponse = Mockery::mock(Response::class);
//        $user = $mockUserRepository->getAllUser($mockRequest,$mockResponse);
//        self::assertTrue(is_array($user));
//    }
}
