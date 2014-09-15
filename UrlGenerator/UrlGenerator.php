<?php

namespace Arte\Bundle\HateoasBundle\UrlGenerator;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlGenerator
{
    /**
     * @var SymfonyUrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, array $parameters, $absolute = false)
    {
        return $this->urlGenerator->generate($name, $parameters, $absolute);
    }

    public function generateTemplate($name, array $parameters, $absolute = false)
    {
        $url = $this->generate($name, $parameters, $absolute);
        $url = str_replace('%7B', '{', $url);
        $url = str_replace('%7D', '}', $url);

        return $url;
    }
}
