<?php declare(strict_types = 1);

namespace App\Utility\User;

use Psr\Http\Message\ServerRequestInterface;

/**
 * User Request Meta data collector
 *
 * Class Device
 * @package App\Utility
 */
class Device
{
    /**
     * @var ServerRequestInterface
     */
    protected ServerRequestInterface $request;

    /**
     * @var string|null
     */
    protected ?string $locale = null;

    /**
     * @var array
     */
    protected array $platform;

    /**
     * @var array
     */
    protected array $channel;

    /**
     * @var bool
     */
    protected bool $isProcessed = false;

    /**
     * Device constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;

        $this->execute();
    }

    /**
     * Execute user meta data from request header
     *
     * @return void
     */
    public function execute() : void
    {
        if ($this->isProcessed()) {
            return;
        }

        $this->parseLocale();
        $this->parseUserAgent();

        $this->isProcessed = true;

        return;
    }

    /**
     * Parse request header Accept-Language
     *
     * @return void
     */
    protected function parseLocale() : void
    {
        $locale = $this->request->getHeader('Accept-Language');
        if (is_array($locale)) {
            $locale = $locale[0] ?? null;
        }

        if (!$locale) {
            $locale = 'en';
        }

        $this->locale = $locale;

        return;
    }

    /**
     * Parse request header User-Agent
     *
     * @return void
     */
    protected function parseUserAgent() : void
    {
        $userAgent = $this->request->getHeader('User-Agent');
        if (!$userAgent) return;

        if (is_array($userAgent)) {
            $userAgent = $userAgent[0] ?? null;
        }

        $userAgentData = explode(' ', $userAgent);

        $platform = $userAgentData[0] ?? '';
        $channel = $userAgentData[1] ?? '';

        $platformData = explode('/', $platform);
        $channelData = explode('/', $channel);

        $this->platform = [
            'name' => $platformData[0] ?? null,
            'version' => $platformData[1] ?? null,
        ];

        $this->channel = [
            'name' => $channelData[0] ?? null,
            'version' => $channelData[1] ?? null,
        ];
    }

    /**
     * Get User locale from request
     *
     * @return string|null
     */
    public function getLocale() : ?string
    {
        return $this->locale;
    }

    /**
     * Make sure already process this task
     *
     * @return bool
     */
    public function isProcessed() : bool
    {
        return $this->isProcessed;
    }

    /**
     * Get Platform name from request
     *
     * @return string|null
     */
    public function getPlatformName() : ?string
    {
        return $this->platform['name'];
    }

    /**
     * Get Platform version from request
     *
     * @return int|null
     */
    public function getPlatformVersion() : ?int
    {
        return (int) $this->platform['version'];
    }

    /**
     * Get Platform info with version
     *
     * @return string|null
     */
    public function getPlatformWithVersion() : ?string
    {
        return implode('/', $this->platform);
    }

    /**
     * Get Channel name from request
     *
     * @return string|null
     */
    public function getChannelName() : ?string
    {
        return $this->channel['name'];
    }

    /**
     * Get Channel version from request
     *
     * @return int|null
     */
    public function getChannelVersion() : ?int
    {
        return (int) $this->channel['version'];
    }


    /**
     * Get Channel info with version
     *
     * @return string|null
     */
    public function getChannelWithVersion() : ?string
    {
        return implode('/', $this->channel);
    }
}