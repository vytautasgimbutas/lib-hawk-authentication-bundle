<?php

namespace Tornado\Bundle\HawkAuthenticationBundle;

use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tornado\Bundle\HawkAuthenticationBundle\DependencyInjection\Security\Factory\HawkSecurityFactory;

class TornadoHawkAuthenticationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var SecurityExtension $securityExtension */
        $securityExtension = $container->getExtension('security');
        $securityExtension->addSecurityListenerFactory(new HawkSecurityFactory());
    }
}
