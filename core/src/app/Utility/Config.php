<?php declare(strict_types = 1);


namespace App\Utility;

class Config
{
    protected ?string $configPath;
    protected array $config = [];
    protected bool $isProcessed = false;
    protected $skips = [
        'ioc.php',
        'middleware.php',
        'route.php',
        'routes.php',
        'settings.php',
        '.',
        '..',
    ];

    public function __construct(?string $path = null)
    {
        $this->configPath = $path;

        $this->resolveConfig();
    }

    public function resolveConfig()
    {
        if ($this->isProcessed) return;

        $files = scandir($this->configPath);

        foreach ($files as $file) {
            if (in_array($file, $this->skips)) continue;

            $file = substr($file, 0, strpos($file,'.php'));

            $config = require $this->configPath . '/' . $file . '.php';

            $this->config[$file] = $config;
        }

        $this->isProcessed = true;
    }

    public function get(?string $node = '.', $default = null)
    {
        $value = array_get($this->config, $node);

        if ($value === null) {
            return $default;
        }

        return $value;
    }
    
}