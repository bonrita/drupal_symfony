<?php

namespace App\Application\DependencyInjection\Security\Factory;


use App\Infrastructure\Security\Authentication\Provider\PasswordResetProvider;
use App\Infrastructure\Security\Firewall\PasswordResetListener;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PasswordResetFactory implements SecurityFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        // authentication provider
        $authProviderId = 'security.authentication.provider.passwordreset.'.$id;

        $container
            ->setDefinition($authProviderId, new ChildDefinition(PasswordResetProvider::class))
//            ->setDefinition($authProviderId, new ChildDefinition('security.authentication.provider.passwordreset'))
//            ->replaceArgument(0, $authenticators)
            ->setArgument('$userProvider', new Reference($userProvider))
            ->setArgument('$providerKey', $config['provider_key']);
//            ->setArgument('$userChecker', new Reference('security.user_checker.'.$id));

        $listenerId = 'security.authentication.listener.passwordreset';
        $container->setDefinition($listenerId, new ChildDefinition(PasswordResetListener::class))
            ->setArgument('$providerKey', $config['provider_key']);

        return array($authProviderId, $listenerId, $defaultEntryPoint);

    }

    /**
     * @inheritDoc
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return 'passwordreset';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        $builder
            ->fixXmlConfig('settings')
            ->children()
            ->scalarNode('provider_key')
            ->isRequired()
            ->info(
                'A key from the "providers" section of your security config'
            )
            ->end();
    }

}