<?php declare(strict_types = 1);


namespace App\Utility\Logger;

use App\Utility\User\Device;
use App\Utility\Config;
use Psr\Http\Message\RequestInterface;

class Log
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var array
     */
    protected  array $payload = [];

    /**
     * @var Log|null
     */
    protected  ?Log $instance = null;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var Device
     */
    protected Device $device;

    /**
     * @var array|string[]
     */
    protected array $enableResources = [
        'url',
        'platform',
        'msisdn',
    ];

    public function __construct(Config $config, RequestInterface $request, Device $device)
    {
        $this->config = $config;
        $this->request = $request;
        $this->device = $device;
    }

    /**
     * This method checking provided selector is enable or not
     *
     * @param string|null $selector
     * @return bool
     */
    public function isEnable( ?string $selector = null) : bool
    {
        $logEnable = $this->config->get('log.enable', false);

        if (is_null($selector)) return $logEnable;

        return $logEnable && $this->hasEnable($selector);
    }

    /**
     * This method set the log value with provided key
     *
     * @param string $name
     * @param $log
     * @param string|null $check
     */
    public function set(string $name, $log, ?string $check = null) : void
    {
        if (!$this->isEnable()) return;

        if (!is_null($check) && !$this->isEnable($check)) {
            return;
        }

        array_set($this->payload, $name, $log);
    }

    /**
     * This method pull the value of provided key
     *
     * @param string $name
     * @param null $default
     * @return array|mixed|null
     */
    public function get(string $name, $default = null)
    {
        return array_get($this->payload, $name, $default);
    }

    /**
     * This method push the log
     *
     * @param string $name
     * @param $log
     * @param string|null $check
     */
    public function push(string $name, $log, ?string $check = null) : void
    {
        if (!is_null($check) && !$this->isEnable($check)) {
            return;
        }

        $data = $this->get($name);
        if (!$data) {
            $this->set($name, [$log]);

            return;
        }

        if (!is_array($data)) return;

        if (is_array($log)) {
            $data[] = $log;
        }

        array_set($this->payload, $name, $data);

        return;
    }

    /**
     * This method push the value in last item
     *
     * @param $name
     * @param $key
     * @param $log
     * @param string|null $check
     */
    public function pushEnd(string $name, string $key, $log, ?string $check = null)
    {
        if (!is_null($check) && !$this->isEnable($check)) {
            return;
        }

        $data = $this->get($name);

        if (!$data) {
            $this->push($name, [$key => $log]);
            return;
        }

        if (!is_array($data)) return;

        $lastKey = array_key_last($data);
        $lastData = end($data);

        if (!is_array($lastData)) return;

        $lastData[$key] = $log;

        $this->set($name . '.' . $lastKey, $lastData);

        return;
    }

    /**
     * This method check the specific key is available or not
     * and return boolean
     *
     * @param string $name
     * @return bool
     */
    public  function has(string $name) : bool
    {
        return array_get($this->payload, $name) ? true : false;
    }

    /**
     * This method remove the specific item from payload
     *
     * @param string $name
     */
    public  function remove(string $name) : void
    {
        array_remove($this->payload, $name);
    }

    /**
     * This method return json string of payload
     *
     * @return array
     */
    public  function getPayload() : array
    {
        return $this->payload;
    }

    /**
     * This method return object of payload
     *
     * @return object
     */
    public  function getPayloadAsObject() : object
    {
        return (object) $this->payload;
    }

    /**
     * This method return array of payload
     *
     * @return string|null
     */
    public  function getPayloadAsJson() : ?string
    {
        return json_encode($this->payload);
    }

    /**
     * This method will save the payload
     *
     * @param string[] $medium
     * @return bool
     */
    public  function save($medium = ["file", "cloudwatch"]) : bool
    {
        dump($this->payload);
    }

    /**
     * This method will check module enable or not
     *
     * @param string $module
     * @return array|bool|mixed|null
     */
    public function hasEnable(string $module)
    {
        if (in_array($module, $this->enableResources)) {
            return $this->hasResourceModuleEnable($module);
        }

        $moduleState = $this->config->get("log.{$module}", false);
        $hasParent = strpos($module, '.');

        if (!is_array($moduleState) && !$hasParent) return $moduleState;

        if ($hasParent) {
            $parent = substr($module,0, $hasParent );
            $moduleMethod = 'enable' . ucfirst($parent);
            return $moduleState && call_user_func([$this, $moduleMethod]);
        }

        if (is_array($moduleState)) {
            return $this->config->get('log.' . $module . '.enable' , false);
        }

        return $moduleState;
    }

    /**
     * This method will check if resource enable or not
     *
     * @param string $module
     * @return bool
     */
    protected function hasResourceModuleEnable(string $module) : bool
    {
        $moduleResource = $module. 's';
        $platforms = $this->config->get('log.' . "{$module}.{$moduleResource}", []);
        $isEnable = $this->config->get("log.{$module}.enable");

        $currentMethod = 'current' . ucfirst($module);

        $currentResource = call_user_func([$this, $currentMethod]);

        $hasPlatform = in_array($currentResource, $platforms);

        return $isEnable && $hasPlatform;
    }

    /**
     * @return string|null
     */
    protected function currentPlatform() : ?string
    {
        return strtolower($this->device->getPlatformName());
    }

    /**
     * @return string|null
     */
    protected function currentUrl() : ?string
    {
        return $this->request->getUri()->getPath();
    }

    /**
     * @return bool
     */
    protected function enableDatabase() : bool
    {
        return $this->config->get('log.database.enable', false) &&
            $this->config->get('log.database.query_logging_enable', false);
    }

    /**
     * @return array|mixed|null
     */
    protected function enableRequest()
    {
        return $this->config->get('log.request.enable');
    }

    /**
     * @return array|mixed|null
     */
    protected function enableResponse()
    {
        return $this->config->get('log.response.enable');
    }

    /**
     * @return array|mixed|null
     */
    protected function enableRedis()
    {
        return $this->config->get('log.redis.enable');
    }
}