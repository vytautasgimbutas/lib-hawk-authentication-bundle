<?php

namespace Tornado\Bundle\HawkAuthenticationBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * HawkSecurityFactory
 *
 * @author Vytautas Gimbutas <vytautas@gimbutas.net>
 * @package Tornado\Bundle\HawkAuthenticationBundle\DependencyInjection\Security\Factory
 */
class HawkSecurityFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerClass = 'Tornado\Bundle\HawkAuthenticationBundle\Service\UserProviderCredentialsProviderAdapter';
        $providerDefinition = new Definition($providerClass, array(
            new Reference($userProvider),
        ));

        $container->setDefinition('hawk.security.default_credentials_provider', $providerDefinition);

        $container->setDefinition('hawk.security.header_parser', new DefinitionDecorator($config['header_parser']));

        $hawkServer = new Definition($config['hawk_server_class'], array(
            new Reference($config['crypto']),
            new Reference($config['credentials_provider']),
            new Reference($config['time_provider']),
            new Reference($config['nonce_validator']),
            $config['timestamp_skew_seconds'],
            $config['local_time_offset_seconds'],
        ));

        $hawkAuthenticationProvider = new Definition($config['hawk_authentication_provider_class'], array(
            $hawkServer,
            new Reference($userProvider)
        ));

        $providerId = 'security.authentication.provider.hawk.' . $id;
        $container->setDefinition($providerId, $hawkAuthenticationProvider);

        $listenerId = 'security.authentication.listener.hawk.' . $id;
        $container->setDefinition($listenerId, new DefinitionDecorator('hawk.security.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'hawk';
    }

    /**
     * @param NodeDefinition $builder
     */
    public function addConfiguration(NodeDefinition $builder)
    {
        $hawkAuthenticationProviderClass =
            'Tornado\Bundle\HawkAuthenticationBundle\Security\Core\Authentication\HawkAuthenticationProvider';

        $builder
            ->children()
                ->scalarNode('provider')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('nonce_validator')->isRequired()->cannotBeEmpty()->end()

                ->scalarNode('credentials_provider')->defaultValue('hawk.security.default_credentials_provider')->end()
                ->scalarNode('crypto')->defaultValue('hawk.security.default_crypto')->end()
                ->scalarNode('time_provider')->defaultValue('hawk.security.default_time_provider')->end()
                ->scalarNode('header_parser')->defaultValue('hawk.security.default_header_parser')->end()

                ->integerNode('timestamp_skew_seconds')->defaultValue(60)->end()
                ->integerNode('local_time_offset_seconds')->defaultValue(0)->end()

                ->scalarNode('hawk_server_class')->defaultValue('Dflydev\Hawk\Server\Server')->end()
                ->scalarNode('hawk_authentication_provider_class')
                    ->defaultValue($hawkAuthenticationProviderClass)
                ->end()
            ->end()
        ;
    }
}
