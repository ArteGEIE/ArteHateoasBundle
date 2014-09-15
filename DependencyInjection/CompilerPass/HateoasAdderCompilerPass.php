<?php

namespace Arte\Bundle\HateoasBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class HateoasAdderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('arte.hateoas.event_subscriber.json')) {
            return;
        }

        // remove BazingaHateoasBundle's event subscriber
        $hateoasJsonEventSubscriber = $container->removeDefinition('hateoas.event_subscriber.json');
    }
}
