<?php

namespace App\Application\DependencyInjection\Compiler;

use App\Infrastructure\Manager\Plugin\Collection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PluginPass implements CompilerPassInterface
{

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        // Check if the primary service is defined.
        if (!$container->has(Collection::class)) {
            return;
        }

        $definition = $container->findDefinition(Collection::class);

        // find all service IDs with the app.plugin tag
        $taggedServices = $container->findTaggedServiceIds('app.plugin');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the ChainTransport service
            $definition->addMethodCall('addPlugin', array(new Reference($id)));
        }
    }

}