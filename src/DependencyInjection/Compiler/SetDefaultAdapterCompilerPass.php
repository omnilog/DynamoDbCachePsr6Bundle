<?php

namespace Omnilog\DynamoDbCacheBundle\DependencyInjection\Compiler;

use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface as Psr16CacheInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @codeCoverageIgnore
 */
final class SetDefaultAdapterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $replace = $container->getParameter('omnilog.dynamo_cache.internal.replace_adapter');
        if ($replace) {
            $container->setAlias(AdapterInterface::class, 'omnilog.dynamo_cache.adapter');
            $container->setAlias(CacheInterface::class, 'omnilog.dynamo_cache.adapter');
            $container->setAlias(CacheItemPoolInterface::class, 'omnilog.dynamo_cache.cache');
            $container->setAlias(Psr16CacheInterface::class, 'omnilog.dynamo_cache.cache');
        }
    }
}
