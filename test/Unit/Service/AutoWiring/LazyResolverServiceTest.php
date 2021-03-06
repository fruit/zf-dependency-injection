<?php

namespace Reinfi\DependencyInjection\Unit\Service\AutoWiring;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Service\AutoWiring\LazyResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\AutoWiring
 *
 * @group unit
 */
class LazyResolverServiceTest extends TestCase
{
    /**
     * @test
     */
    public function itResolvesResolverServiceLazy()
    {
        $resolverService = $this->prophesize(ResolverServiceInterface::class);
        $resolverService->resolve('test')
            ->willReturn([]);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ResolverService::class)
            ->willReturn($resolverService->reveal())
            ->shouldBeCalled();

        $service = new LazyResolverService($container->reveal());

        $service->resolve('test');
    }

    /**
     * @test
     */
    public function itResolvesResolverServiceOnlyOnce()
    {
        $resolverService = $this->prophesize(ResolverServiceInterface::class);
        $resolverService->resolve('test')
            ->willReturn([]);
        $resolverService->resolve('test2')
            ->willReturn([]);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ResolverService::class)
            ->willReturn($resolverService->reveal())
            ->shouldBeCalledTimes(1);

        $service = new LazyResolverService($container->reveal());

        $service->resolve('test');
        $service->resolve('test2');
    }
}