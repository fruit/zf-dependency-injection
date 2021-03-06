<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service;

use Reinfi\DependencyInjection\Exception\ConfigPathNotFoundException;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class ConfigService
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $configPath
     *
     * @return mixed|null
     */
    public function resolve(string $configPath)
    {
        $nullAllowed = true;

        if (substr($configPath, -1) === '!') {
            $nullAllowed = false;
            $configPath = substr($configPath, 0, -1);
        }

        $configParts = explode('.', $configPath);

        return $this->resolveConfigPath(
            $this->config,
            $configParts,
            $nullAllowed
        );
    }

    /**
     * @param Config $config
     * @param array  $configParts
     * @param bool   $nullAllowed
     *
     * @return mixed|null
     * @throws ConfigPathNotFoundException
     */
    private function resolveConfigPath(
        Config $config,
        array $configParts,
        bool $nullAllowed
    ) {
        $currentKey = array_shift($configParts);

        if (!$config->offsetExists($currentKey)) {
            if ($nullAllowed) {
                return null;
            }

            throw new ConfigPathNotFoundException($currentKey);
        }

        if (count($configParts) === 0) {
            return $config->get($currentKey);
        }

        return $this->resolveConfigPath(
            $config->get($currentKey),
            $configParts,
            $nullAllowed
        );
    }
}
