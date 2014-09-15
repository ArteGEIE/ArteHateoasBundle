<?php

namespace Arte\Bundle\HateoasBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Arte\Bundle\HateoasBundle\DependencyInjection\CompilerPass\HateoasAdderCompilerPass;

class ArteHateoasBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new HateoasAdderCompilerPass());
    }
}
