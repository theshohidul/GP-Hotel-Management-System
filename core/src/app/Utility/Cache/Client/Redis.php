<?php

namespace App\Utility\Cache\Client;

use App\Utility\Cache\Contracts\ClientInterface;
use App\Utility\Cache\Drivers\Redis  as R;
use Stash\Pool;

class Redis implements ClientInterface
{
    /**
     * @var array
     */
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Stash\Driver\Redis
     */
    public function driver()
    {
        return (new R($this->config));
    }

    /**
     * @return Pool
     */
    public function connect() : Pool
    {
        return (new Pool($this->driver()));
    }

}