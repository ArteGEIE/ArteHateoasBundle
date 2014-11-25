<?php

namespace Arte\Bundle\HateoasBundle\Adder;

use Hateoas\UrlGenerator\UrlGeneratorRegistry;
use Hateoas\Serializer\JsonSerializerInterface;
use Symfony\Component\DependencyInjection\Container;

abstract class AbstractAdder
{
    protected $urlGeneratorRegistry;
    protected $jsonSerializer;
    protected $context;
    protected $container;

    public function __construct(UrlGeneratorRegistry $urlGeneratorRegistry, JsonSerializerInterface $jsonSerializer, $context, Container $container)
    {
        $this->urlGeneratorRegistry = $urlGeneratorRegistry;
        $this->jsonSerializer = $jsonSerializer;
        $this->context = $context;
        $this->container = $container;
    }

    public abstract function add($object, $visitor);

    public function get($service) {
        return $this->container->get($service);
    }
}
