<?php

namespace Arte\Bundle\HateoasBundle\Adder;

use Arte\Bundle\HateoasBundle\Adder\AbstractAdder;
use Arte\Bundle\HateoasBundle\Generator\Generator;
use Hateoas\UrlGenerator\UrlGeneratorRegistry;
use Hateoas\Serializer\JsonSerializerInterface;

class AdderRegistry
{
    protected $adders = array();
    protected $generator;
    protected $urlGeneratorRegistry;
    protected $jsonSerializer;
    protected $loader;
    protected $alwaysGenerate;

    public function __construct(Generator $generator, UrlGeneratorRegistry $urlGeneratorRegistry, JsonSerializerInterface $jsonSerializer, $alwaysGenerate = false)
    {
        $this->generator = $generator;
        $this->urlGeneratorRegistry = $urlGeneratorRegistry;
        $this->jsonSerializer = $jsonSerializer;
        $this->registerClassloader($generator->getOutputDir());
        $this->alwaysGenerate = $alwaysGenerate;
    }

    protected function getClassname($type)
    {
        return sprintf('HateoasAdder%s', md5($type));
    }

    protected function load($type, $object, $context)
    {
        $className = $this->getClassname($type);
        $fullClassName = Generator::ADDERS_NAMESPACE.$className;

        if (!class_exists($fullClassName) || true === $this->alwaysGenerate) {
            if ($fullPath = $this->generator->generate($type, $object, $className, $context)) {
                return new $fullClassName($this->urlGeneratorRegistry, $this->jsonSerializer, $context);
            }
        } else {
            return new $fullClassName($this->urlGeneratorRegistry, $this->jsonSerializer, $context);
        }

        return false;
    }

    protected function registerClassloader($addersDir)
    {
        $this->loader = function($class) use ($addersDir) {
            if (0 === strpos($class, Generator::ADDERS_NAMESPACE)) {
                $fileName = $addersDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

                if (file_exists($fileName)) {
                    include $fileName;
                }

                return false;
            }
        };
        spl_autoload_register($this->loader);
    }

    public function retrieve($object, $context)
    {
        $type = get_class($object);

        if (!array_key_exists($type, $this->adders)) {
            $this->adders[$type] = $this->load($type, $object, $context);
        }

        return $this->adders[$type];
    }
}
