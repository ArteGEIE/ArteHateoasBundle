<?php

namespace Arte\Bundle\HateoasBundle\Generator;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Exception\LogicException;
use Hateoas\Factory\EmbeddedsFactory;
use Hateoas\Factory\LinksFactory;
use Hateoas\Serializer\JsonSerializerInterface;
use Hateoas\Serializer\Metadata\InlineDeferrer;
use Hateoas\Configuration\RelationsRepository;
use Hateoas\Serializer\ExclusionManager;
use Bazinga\Bundle\HateoasBundle\ExpressionLanguage\ExpressionLanguage;

class Generator implements GeneratorInterface
{
    protected $jsonSerializer;
    protected $linksFactory;
    protected $embeddedsFactory;
    protected $embeddedsInlineDeferrer;
    protected $linksInlineDeferrer;
    protected $twigEnvironment;
    protected $relationsRepository;
    protected $expressionLanguage;

    public function __construct(
        $twigEnvironment,
        JsonSerializerInterface $jsonSerializer,
        LinksFactory $linksFactory,
        EmbeddedsFactory $embeddedsFactory,
        InlineDeferrer $embeddedsInlineDeferrer,
        InlineDeferrer $linksInleDeferrer,
        RelationsRepository $relationsRepository,
        ExclusionManager $exclusionManager,
        ExpressionLanguage $expressionLanguage
    ) {
        $this->twigEnvironment         = $twigEnvironment;
        $this->jsonSerializer          = $jsonSerializer;
        $this->linksFactory            = $linksFactory;
        $this->embeddedsFactory        = $embeddedsFactory;
        $this->embeddedsInlineDeferrer = $embeddedsInlineDeferrer;
        $this->linksInlineDeferrer     = $linksInleDeferrer;
        $this->relationsRepository     = $relationsRepository;
        $this->exclusionManager        = $exclusionManager;
        $this->expressionLanguage      = $expressionLanguage;
    }

    public function generate($type, $object, $className, $context)
    {
        return $this->render($type, $object, $className, $context);
    }

    public function render($type, $object, $className, $context)
    {
        $template = $this->twigEnvironment->loadTemplate('ArteHateoasBundle::hateoasAdder.php.twig');

        return $template->render([
            'className' => $className,
            'type' => $type,
            'object' => $object,
            'linksFactory' => $this->linksFactory,
            'embeddedsFactory' => $this->embeddedsFactory,
            'exclusionManager' => $this->exclusionManager,
            'relationsRepository' => $this->relationsRepository,
            'context' => $context,
            'expressionLanguage' => $this->expressionLanguage
        ]);
    }
}
