<?php

namespace Omnilog\DynamoDbCacheBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @codeCoverageIgnore
 */
final class AssignConvertersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $service = $container->getDefinition('omnilog.dynamo_cache.converter_registry');
        $converters = $container->findTaggedServiceIds('omnilog.dynamo_cache.converter');
        foreach ($converters as $serviceId => $tags) {
            $service->addArgument(new Reference($serviceId));
        }
    }
}
