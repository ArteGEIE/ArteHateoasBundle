<?php

namespace Arte\Bundle\HateoasBundle\Generator;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Cache implements ContainerAwareInterface, GeneratorInterface
{
    protected $container;
    protected $generator;
    protected $outputDir;
    protected $alwaysGenerate;
    protected $generatedClassMap;

    public function __construct($outputDir = "", $alwaysGenerate = false)
    {
        $this->outputDir         = $outputDir;
        $this->alwaysGenerate    = $alwaysGenerate;
        $this->generatedClassMap = array();
    }


    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return GeneratorInterface
     */
    public function getGenerator()
    {
        if (!$this->generator) {
            $this->generator = $this->container->get('arte.hateoas.adder.generator');
        }

        return $this->generator;
    }

    public function generate($type, $object, $className, $context)
    {
        $fileName = $this->outputDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, GeneratorInterface::ADDERS_NAMESPACE) . $className . '.php';

        if (!file_exists($fileName) || $this->mustGenerate($fileName)) {
            if (!file_exists(dirname($fileName))) {
                mkdir(dirname($fileName), 0777, true);
            }

            $generatedClass = $this->getGenerator()->generate($type, $object, $className, $context);
            file_put_contents($fileName, $generatedClass);
            $this->generatedClassMap[$fileName] = true;
        }

        return $fileName;
    }

    protected function mustGenerate($fileName)
    {
        return $this->alwaysGenerate && !array_key_exists($fileName, $this->generatedClassMap);
    }
}
