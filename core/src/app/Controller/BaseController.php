<?php


namespace App\Controller;


use App\Utility\Logger\Log;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class BaseController
{
    protected ContainerInterface $container;
    /**
     * @var Log|mixed
     */
    protected Log $log;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->log = $this->container->get(Log::class);
    }

    protected function response($data = null, $code = 200, $headers = [])
    {
        $response = new Response();
        $contentType = 'text/html;charset=UTF-8';

        if (is_array($data)) {
            $contentType = 'application/json';
            $data = json_encode($data);
        }

        $response = $response->withHeader('Content-Type', $contentType);

        foreach ($headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }

        $this->log->set('app.end_at', microtime(true));
        $totalExecutionTime = $this->log->get('app.end_at')- $this->log->get('app.start_at');
        $this->log->set('app.total_response_time', $totalExecutionTime);
        $this->log->set('response', [
            'headers' => $headers,
            'status_code' => $code
        ], 'response');
        $this->log->set('response.data', $data, 'response.accept_data');

        $response->getBody()->write($data);

        return $response->withStatus($code);
    }
}