<?php


namespace App\Controller\V1\User;



use App\Controller\BaseController;
use App\Service\User\UsageConsumptionService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UsageConsumptionController extends BaseController
{

    private $usageConsumptionService;

    public function __construct(ContainerInterface $container, UsageConsumptionService $usageConsumptionService)
    {
        parent::__construct($container);
        $this->usageConsumptionService = $usageConsumptionService;
    }



    public function getUsageConsumption(Request $request, Response $response, $msisdn)
    {
        $usage = $this->usageConsumptionService->getUsageByMsisdn($msisdn);
        return $this->response([$usage],200);
    }
}