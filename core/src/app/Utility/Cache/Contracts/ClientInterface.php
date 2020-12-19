<?php

namespace App\Utility\Cache\Contracts;

use Stash\Pool;

interface ClientInterface
{
    /**
     * This method will return the specific caching driver
     * by using config
     *
     * @return mixed
     */
    public function driver();

    /**
     * This method will return Pool instance by using
     * driver instance and driver instance will be
     * provided by "driver" method
     *
     * @return Pool
     */
    public function connect(): Pool;
}