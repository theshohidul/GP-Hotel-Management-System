<?php


namespace App\Service\User;


use App\Api\UsageConsumptionApi;

class UsageConsumptionService
{
    private $usageConsumptionApi;

    public function __construct(UsageConsumptionApi $usageConsumptionApi)
    {
        $this->usageConsumptionApi = $usageConsumptionApi;
    }


    public function getUsageByMsisdn($msisdn)
    {
        return $this->usageConsumptionApi->getUsageByMsisdn($msisdn);
    }
}