<?php

namespace Arte\Bundle\HateoasBundle\Generator;

interface GeneratorInterface
{
    const ADDERS_NAMESPACE = 'Arte\\Bundle\\HateoasBundle\\Adder\\Generated\\';

    public function generate($type, $object, $className, $context);
}
