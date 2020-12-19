<?php


namespace App\Api;


use Apiz\AbstractApi;

class UsageConsumptionApi extends AbstractApi
{
    protected $baseUrl = 'http://54.164.168.51:8080';
    protected $prefix = 'pub/rs/v1.0/digital/producto/suscriptor/balancedetallado';

    protected function baseUrl():string
    {
        return $this->baseUrl;
    }


    public function getUsageByMsisdn($msisdn)
    {
        $response = $this
                    ->headers(['canal'=>'App'])
                    ->get('obtener?numeroSuscriptor='.$msisdn.'&detalle=1&api_key=6crjWFuCa1oukE1BLWg6rwvjmJnSn9bCFja7l0gr');

        if ($response->getStatusCode() === 200) {
            return $response->autoParse();
        }

        return [];
    }

}