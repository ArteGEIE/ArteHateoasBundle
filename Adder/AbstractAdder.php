<?php

namespace Arte\Bundle\HateoasBundle\Adder;

use Hateoas\UrlGenerator\UrlGeneratorRegistry;
use Hateoas\Serializer\JsonSerializerInterface;

abstract class AbstractAdder
{
    protected $urlGeneratorRegistry;
    protected $jsonSerializer;
    protected $context;

    public function __construct(UrlGeneratorRegistry $urlGeneratorRegistry, JsonSerializerInterface $jsonSerializer, $context)
    {
        $this->urlGeneratorRegistry = $urlGeneratorRegistry;
        $this->jsonSerializer = $jsonSerializer;
        $this->context = $context;
    }

    public abstract function add($object, $visitor);
}
