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
    use Arte\Bundle\HateoasBundle\Adder\AdderRegistry;
    use Hateoas\Serializer\ExclusionManager;
use Bazinga\Bundle\HateoasBundle\ExpressionLanguage\ExpressionLanguage;

class Generator
{
    const ADDERS_NAMESPACE = 'Arte\\Bundle\\HateoasBundle\\Adder\\Generated\\';

    protected $jsonSerializer;
    protected $linksFactory;
    protected $embeddedsFactory;
    protected $embeddedsInlineDeferrer;
    protected $linksInlineDeferrer;
    protected $twigEnvironment;
    protected $outputDir;
    protected $relationsRepository;
    protected $expressionLanguage;
    protected $alwaysGenerate;
    protected $generatedClassMap = array();

    public function __construct(
        $twigEnvironment,
        $outputDir,
        JsonSerializerInterface $jsonSerializer,
        LinksFactory $linksFactory,
        EmbeddedsFactory $embeddedsFactory,
        InlineDeferrer $embeddedsInlineDeferrer,
        InlineDeferrer $linksInleDeferrer,
        RelationsRepository $relationsRepository,
        ExclusionManager $exclusionManager,
        ExpressionLanguage $expressionLanguage,
        $alwaysGenerate = false
    ) {
        $this->twigEnvironment         = $twigEnvironment;
        $this->outputDir               = $outputDir;
        $this->jsonSerializer          = $jsonSerializer;
        $this->linksFactory            = $linksFactory;
        $this->embeddedsFactory        = $embeddedsFactory;
        $this->embeddedsInlineDeferrer = $embeddedsInlineDeferrer;
        $this->linksInlineDeferrer     = $linksInleDeferrer;
        $this->relationsRepository     = $relationsRepository;
        $this->exclusionManager        = $exclusionManager;
        $this->expressionLanguage      = $expressionLanguage;
        $this->alwaysGenerate          = $alwaysGenerate;
    }

    public function generate($type, $object, $className, $context)
    {
        $fileName = $this->outputDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, Generator::ADDERS_NAMESPACE) . $className . '.php';

        if (!file_exists($fileName) || $this->mustGenerate($fileName)) {
            if (!file_exists(dirname($fileName))) {
                mkdir(dirname($fileName), 0777, true);
            }

            file_put_contents($fileName, $this->render($type, $object, $className, $context));
            $this->generatedClassMap[$fileName] = true;
        }

        return $fileName;
    }

    public function getOutputDir()
    {
        return $this->outputDir;
    }

    protected function mustGenerate($fileName)
    {
        return $this->alwaysGenerate && !array_key_exists($fileName, $this->generatedClassMap);
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
