<?php

namespace Omnilog\DynamoDbCacheBundle;

use Omnilog\DynamoDbCache\Converter\CacheItemConverterInterface;
use Omnilog\DynamoDbCacheBundle\DependencyInjection\Compiler\AssignConvertersCompilerPass;
use Omnilog\DynamoDbCacheBundle\DependencyInjection\Compiler\SetDefaultAdapterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @codeCoverageIgnore
 */
final class OmnilogDynamoDbCacheBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(CacheItemConverterInterface::class)
            ->addTag('omnilog.dynamo_cache.converter');
        $container->addCompilerPass(new SetDefaultAdapterCompilerPass());
        $container->addCompilerPass(new AssignConvertersCompilerPass());
    }
}
