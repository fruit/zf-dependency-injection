<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class AutoWiring implements InjectionInterface
{
    /**
     * @var string
     */
    private $serviceName;

    /**
     * @param string $serviceName
     */
    public function __construct(string $serviceName)
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return mixed
     * @throws AutoWiringNotPossibleException
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container->has($this->serviceName)) {
            return $container->get($this->serviceName);
        }

        if ($container instanceof AbstractPluginManager) {
            if ($container->getServiceLocator()->has($this->serviceName)) {
                return $container->getServiceLocator()->get($this->serviceName);
            }
        }

        throw new AutoWiringNotPossibleException($this->serviceName);
    }
}
