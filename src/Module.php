<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

/**
 * @package Reinfi\DependencyInjection
 */
class Module implements ConfigProviderInterface, ConsoleUsageProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }

    /**
     * @inheritDoc
     */
    public function getConsoleUsage(AdapterInterface $console): array
    {
        return [
            'reinfi:di cache warmup - fills the cache with injections',
        ];
    }
}
