<?php declare(strict_types = 1);

namespace App\Utility\Cache;

use App\Utility\Logger\Log;
use Stash\Interfaces\ItemInterface;
use Stash\Pool;

class Cache
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @var mixed|string
     */
    protected string $default;

    /**
     * @var int
     */
    protected int $ttl = 100;

    /**
     * @var Log
     */
    protected Log $log;

    /**
     * @var mixed|string
     */
    protected string $connection;

    /**
     * @var array
     */
    protected array $connections;

    public function __construct(array $config, Log $log)
    {
        $this->config = $config;

        $this->default = $this->connection = $this->config['default'];
        $this->log = $log;
        $this->ttl = $this->makeTtl($config['ttl'] ?? 60);
        //$this->connect();
    }

    /**
     * This method will add extra 40 ttl to actual TTL
     *
     * @param int $ttl
     * @return int
     */
    public function makeTtl(int $ttl) : int
    {
        return $ttl + 40;
    }

    /**
     * This method will provide default TTL
     *
     * @return int
     */
    protected function getDefaultTtl() : int
    {
        return $this->ttl;
    }

    /**
     * This method will set connection name
     *
     * @param $name
     * @return $this
     */
    public function setConnection($name) : self
    {
        $this->connection = $name;

        return $this;
    }

    /**
     * This method will resolve the connection
     * and set connection to the array
     * return the Pool instance
     *
     * @return Pool
     * @throws \Exception
     */
    public function connect() : Pool
    {
        $connect = $this->resolveConnection();
        $this->connections[$this->connection] = $connect;

        return $connect;
    }

    /**
     * This method will resolve the connection
     *
     * @return Pool
     * @throws \Exception
     */
    public function resolveConnection() : Pool
    {
        $config = array_get($this->config, 'connections.' . $this->connection, []);

        if (empty($config)) {
            throw new \Exception("Driver [{$this->connection}] config is missing from system!");
        }

        switch ($this->connection) {
            case 'redis':
                $connection = new Client\Redis($config);
                break;
            default:
                throw new \Exception("Driver [{$this->connection}] is missing from system!");
        }
        try {
            $this->log->set('redis.connection_start_at', microtime(true), 'redis');
            $connection = $connection->connect();
            $this->log->set('redis.connection_end_at', microtime(true), 'redis');
            $this->log->set('redis.connection_total_time', ($this->log->get('redis.connection_end_at') - $this->log->get('redis.connection_start_at')), 'redis');
            $this->log->set('redis.connection_success', true, 'redis');
            return $connection;
        } catch (\Exception $e) {
            $this->log->set('redis.connection_fails', $e->getMessage(), 'redis');
            throw $e;
        }

    }

    /**
     * This method will return the connection name
     *
     * @return string
     */
    public function getConnectionName(): string
    {
        return $this->connection;
    }

    /**
     * This method will connect the current driver
     * connection
     *
     * @param string|null $name
     * @return Pool|null
     * @throws \Exception
     */
    public function getConnection(?string $name = null) : ?Pool
    {
        $connection = $name ?? $this->default;

        if ($con = $this->connections[$connection]) return $con;

        return $this->setConnection($connection)
            ->connect();

    }

    /**
     * This method will return prefixed key
     *
     * @param $key
     * @return string
     */
    private function getKey($key) : string
    {
        return array_get($this->config, 'prefix', '') . $key;
    }

    /**
     * Reset connection to default
     */
    protected function resetDefaultConnection() : void
    {
        $this->connection = $this->default;
    }

    /**
     * This method will pull the specific key from cache
     *
     * @param string $key
     * @return mixed|null
     * @throws \Exception
     */
    public function get(string $key)
    {
        $item = $this->getConnection($this->connection)->getItem($this->getKey($key));

        if ($item->isMiss()) {
            return null;
        }

        $this->resetDefaultConnection();

        return $item->get();
    }

    /**
     * This method will check if item exists or not
     *
     * @param string $key
     * @return bool
     * @throws \Exception
     */
    public function has(string $key) : bool
    {
        $this->resetDefaultConnection();

        return $this->getConnection($this->connection)->hasItem($key);
    }

    /**
     * This method will set the value in cache
     *
     * @param string $key
     * @param $value
     * @param null $ttl
     * @return \Stash\Interfaces\ItemInterface
     * @throws \Exception
     */
    public function set(string $key, $value, ?int $ttl = null) : ItemInterface
    {
        $this->log->push('redis.caches', [], 'redis.query_logging_enable');

        $item = $this->getConnection($this->connection)->getItem($this->getKey($key));
        $item->set($value);
        if ($ttl) {
            $ttl = $this->makeTtl($ttl);
        } else {
            $ttl = $this->getDefaultTtl();
        }

        if ($ttl !== -1) {
            $item->setTTL($ttl);
        }

        $this->log->pushEnd('redis.caches', 'start_at', microtime(true), 'redis.query_logging_enable');

        $this->getConnection($this->connection)->save($item);

        $this->log->pushEnd('redis.caches', 'end_at', microtime(true), 'redis.query_logging_enable');

        $this->log->pushEnd('redis.caches', 'data', [
            'key' => $key,
            'value' => $value
            // TODO: value will remove before deploying
        ], 'redis.query_logging_enable');

        $this->resetDefaultConnection();
        return $item;
    }

    /**
     * This method will store value with ttl
     *
     * @param string $key
     * @param int|null $ttl
     * @param callable $fn
     * @return mixed|null
     * @throws \Exception
     */
    public function remember(string $key, ?int $ttl, callable $fn)
    {
        if ($value = $this->get($key)) {
            return $value;
        }

        $value = $fn();
        $this->set($key, $value, $ttl);

        return $value;
    }


    /**
     * This method will store value with forever
     *
     * @param string $key
     * @param callable $fn
     * @return mixed|null
     * @throws \Exception
     */
    public function forever(string $key, callable $fn)
    {
        if ($value = $this->get($key)) {
            return $value;
        }

        $value = $fn();
        $this->set($key, $value, -1);

        return $value;
    }

    /**
     * This method will remove the specific item
     *
     * @param string $key
     * @return bool
     * @throws \Exception
     */
    public function destroy(string $key)
    {
        $this->log->push('redis.caches', [], 'redis.query_logging_enable');
        $this->log->pushEnd('redis.caches', 'start_at', microtime(true), 'redis.query_logging_enable');
        
        $isDestroy = $this->getConnection($this->connection)->deleteItem($key);
        
        $this->log->pushEnd('redis.caches', 'end_at', microtime(true), 'redis.query_logging_enable');
        $this->log->pushEnd('redis.caches', 'deleted_key', $key, 'redis.query_logging_enable');

        $this->resetDefaultConnection();

        return $isDestroy;
    }
}