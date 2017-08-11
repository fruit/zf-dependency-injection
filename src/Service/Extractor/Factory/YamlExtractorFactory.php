<?php

namespace Reinfi\DependencyInjection\Service\Extractor\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Symfony\Component\Yaml\Yaml;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor\Factory
 */
class YamlExtractorFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return YamlExtractor
     */
    public function __invoke(ContainerInterface $container): YamlExtractor
    {
        $yaml = new Yaml();

        /** @var Config $config */
        $config = $container->get(ModuleConfig::class);

        $reflClass = new \ReflectionClass(AnnotationInterface::class);

        return new YamlExtractor(
            $yaml,
            $config->extractor_options->file,
            $reflClass->getNamespaceName()
        );
    }
}