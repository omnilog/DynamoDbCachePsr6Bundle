<?php

namespace Omnilog\DynamoDbCacheBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @codeCoverageIgnore
 */
final class OmnilogDynamoDbCacheExtension extends Extension
{
    /**
     * @param array<string, mixed> $configs
     * @param ContainerBuilder     $container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('aliases.yaml');

        $configs = $this->processConfiguration(new Configuration(), $configs);

        $this->createCacheClient($container, $configs);
        $this->createDefaultEncoder($container, $configs);
        $this->createParameters($container, $configs);
    }

    /**
     * @param array<string, mixed> $configs
     * @param ContainerBuilder     $container
     */
    private function createCacheClient(ContainerBuilder $container, array $configs): void
    {
        $definition = $container->getDefinition('omnilog.dynamo_cache.cache');
        $definition->addArgument($configs['table']);
        $definition->addArgument(new Reference('async_aws.client.dynamo_db'));
        $definition->addArgument($configs['primary_key_field']);
        $definition->addArgument($configs['ttl_field']);
        $definition->addArgument($configs['value_field']);
        $definition->setArgument('$prefix', $configs['key_prefix']);
    }

    /**
     * @param ContainerBuilder     $container
     * @param array<string, mixed> $configs
     */
    private function createDefaultEncoder(ContainerBuilder $container, array $configs): void
    {
        $container->removeDefinition('omnilog.dynamo_cache.encoder.default');
        $container->setAlias('omnilog.dynamo_cache.encoder.default', $configs['encoder']['service']);
    }

    /**
     * @param ContainerBuilder     $container
     * @param array<string, mixed> $configs
     */
    private function createParameters(ContainerBuilder $container, array $configs): void
    {
        $container->setParameter(
            'omnilog.dynamo_cache.internal.replace_adapter',
            $configs['replace_default_adapter']
        );

        $container->setParameter(
            'omnilog.dynamo_cache.json_encoder.encode_flags',
            $configs['encoder']['json_options']['encode_flags']
        );
        $container->setParameter(
            'omnilog.dynamo_cache.json_encoder.decode_flags',
            $configs['encoder']['json_options']['decode_flags']
        );
        $container->setParameter(
            'omnilog.dynamo_cache.json_encoder.depth',
            $configs['encoder']['json_options']['depth']
        );
    }
}
