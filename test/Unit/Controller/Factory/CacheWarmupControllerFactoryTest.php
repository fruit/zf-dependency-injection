<?php

namespace Reinfi\DependencyInjection\Unit\Controller\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Controller\CacheWarmupController;
use Reinfi\DependencyInjection\Controller\Factory\CacheWarmupControllerFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Zend\Mvc\Controller\ControllerManager;

/**
 * @package Reinfi\DependencyInjection\Unit\Controller\Factory
 */
class CacheWarmupControllerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesController()
    {
        if (!class_exists('Zend\Mvc\Controller\AbstractConsoleController')) {
            $this->markTestSkipped('Skipped because zend console is removed within zend version 3');
        }

        $controllerManager = $this->prophesize(ControllerManager::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn(['service_manager' => []]);
        $container->get(ExtractorInterface::class)
            ->willReturn(
                $this->prophesize(ExtractorInterface::class)->reveal()
            );
        $container->get(ResolverService::class)
            ->willReturn(
                $this->prophesize(ResolverService::class)->reveal()
            );
        $container->get(CacheService::class)
            ->willReturn(
                $this->prophesize(CacheService::class)->reveal()
            );
        $controllerManager->getServiceLocator()
            ->willReturn($container->reveal());

        $factory = new CacheWarmupControllerFactory();

        $instance = $factory($controllerManager->reveal());

        $this->assertInstanceOf(
            CacheWarmupController::class,
            $instance
        );
    }
}