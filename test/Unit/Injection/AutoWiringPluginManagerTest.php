<?php

namespace Reinfi\DependencyInjection\Unit\Injection;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\AutoWiringPluginManager;
use Reinfi\DependencyInjection\Service\Service1;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Unit\Injection
 */
class AutoWiringPluginManagerTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsServiceFromContainer()
    {
        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->has(Service1::class)
            ->willReturn(true);

        $service1 = $this->prophesize(Service1::class);
        $pluginManager->get(Service1::class)
            ->willReturn($service1->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('PluginManager')
            ->willReturn($pluginManager->reveal());

        $injection = new AutoWiringPluginManager(
            'PluginManager',
            Service1::class
        );

        $this->assertInstanceOf(
            Service1::class,
            $injection($container->reveal())
        );
    }

    /**
     * @test
     */
    public function itReturnsServiceFromParentLocator()
    {
        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->has(Service1::class)
            ->willReturn(true);

        $service1 = $this->prophesize(Service1::class);
        $pluginManager->get(Service1::class)
            ->willReturn($service1->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('PluginManager')
            ->willReturn($pluginManager->reveal());

        $otherPluginManager = $this->prophesize(AbstractPluginManager::class);
        $otherPluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $injection = new AutoWiringPluginManager(
            'PluginManager',
            Service1::class
        );

        $this->assertInstanceOf(
            Service1::class,
            $injection($otherPluginManager->reveal())
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfServiceNotFound()
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->has(Service1::class)
            ->willReturn(false);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('PluginManager')
            ->willReturn($pluginManager->reveal());

        $injection = new AutoWiringPluginManager(
            'PluginManager',
            Service1::class
        );

        $injection($container->reveal());
    }
}