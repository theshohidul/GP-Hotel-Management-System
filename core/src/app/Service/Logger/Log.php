<?php


namespace App\Service\Logger;


use App\Utility\Config;
use App\Utility\User\Device;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;

class Log
{
    protected Config $config;
    protected static array $payload = [];
    protected static ?Log $instance = null;
    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var Device
     */
    protected Device $device;

    protected array $enableResourses = [
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

    public static function getInstance() : Log
    {
        if (is_null(static::$payload)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function isEnable( ?string $selector = null) : bool
    {
        $logEnable = $this->config->get('log.enable', false);

        if (is_null($selector)) return $logEnable;

        return $logEnable && $this->hasEnable($selector);
    }

    public function set(string $name, $log, $check = null) : void
    {
        //dump(!is_null($check)  && !$this->isEnable($check));
        if (!is_null($check) && !$this->isEnable($check)) {
            return;
        }

        array_set(static::$payload, $name, $log);
    }

    public function get(string $name)
    {
        return array_get(static::$payload, $name, null);
    }

    public function push(string $name, $log, $check = null) : void
    {
        if (!is_null($check) && !$this->isEnable($check)) {
            return;
        }

        if (!isset(static::$payload[$name])) {
            static::$payload[$name] = $log;

            return;
        }

        $data = static::$payload[$name];

        if (!is_array($data)) return;

        if (!is_array($log)) {
            $data[] = $log;
        }

        if (is_array($log)) {
            $data += $log;
        }

        array_set(static::$payload, $name, $data);

        return;
    }

    public static function has(string $name) : bool
    {
        return array_get(static::$payload, $name) ? true : false;
    }

    public static function remove(string $name) : void
    {
        unset(static::$payload[$name]);
    }

    public static function getPayload() : string
    {
        return json_encode(static::$payload);
    }

    public static function getPayloadAsObject() : object
    {
        return (object) static::$payload;
    }

    public static function getPayloadAsArray() : array
    {
        return static::$payload;
    }

    public static function save($medium = ["file", "cloudwatch"]) : bool
    {
        dump(static::$payload);
    }

    public function hasEnable(string $module)
    {
        if (in_array($module, $this->enableResourses)) {
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

    protected function hasResourceModuleEnable(string $module)
    {
        $moduleResource = $module. 's';
        $platforms = $this->config->get('log.' . "{$module}.{$moduleResource}", []);
        $isEnable = $this->config->get("log.{$module}.enable");

        $currentMethod = 'current' . ucfirst($module);

        $currentResource = call_user_func([$this, $currentMethod]);

        $hasPlatform = in_array($currentResource, $platforms);

        return $isEnable && $hasPlatform;
    }

    protected function currentPlatform()
    {
        return strtolower($this->device->getPlatformName());
    }

    protected function currentUrl()
    {
        return $this->request->getUri()->getPath();
    }

    protected function enableDatabase()
    {
        return $this->config->get('log.database.enable', false) &&
            $this->config->get('log.database.query_logging_enable', false);
    }

    protected function enableRequest()
    {
        return $this->config->get('log.request.enable');
    }

    protected function enableResponse()
    {
        return $this->config->get('log.response.enable');
    }
}