<?php

namespace Arte\Bundle\HateoasBundle\Twig;

use Bazinga\Bundle\HateoasBundle\ExpressionLanguage\ExpressionLanguage;
use Hateoas\Serializer\Metadata\RelationPropertyMetadata;

class ExpressionEvaluatorExtension extends \Twig_Extension
{
    const EXPRESSION_REGEX = '/expr\((?P<expression>.+)\)/';

    protected $expressionLanguage;

    public function __construct(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'expression_evaluator';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('expression_compile', array($this, 'compile'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('expression_compile_array', array($this, 'compileArray'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('is_expression', array($this, 'isExpression')),
            new \Twig_SimpleFunction('expression_dump', array($this, 'dumpExpression'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('expression_dump_bool', array($this, 'dumpBoolExpression'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('should_skip_property', array($this, 'shouldSkipProperty'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('exclude_if_exists', array($this, 'excludeIfExists'), array('is_safe' => array('html'))),
        );
    }

    public function compile($expression, $names = array())
    {
        if (!preg_match(self::EXPRESSION_REGEX, $expression, $matches)) {
            return $expression;
        }

        $expression = $matches['expression'];
        $names = array_merge($names, array('context', 'object'));

        return $this->expressionLanguage->compile($expression, $names);
    }

    public function compileArray($array)
    {
        $newArray = array();

        foreach ($array as $key => $value) {
            $value = is_array($value) ? $this->compileArray($value) : $this->compile($value);
            $newArray[$this->compile($key)] = $value;
        }

        return var_export($newArray, true);
    }

    public function dumpExpression($value)
    {
        if ($this->isExpression($value)) {
            return $this->compile($value);
        } else {
            return '\'' . $value . '\'';
        }
    }

    public function dumpBoolExpression($value)
    {
        if ($this->isExpression($value)) {
            return '(bool) ' . $this->compile($value);
        } else {
            if (is_string($value)) {
                if ('' === $value) {
                    return 'false';
                }

                return 'true';
            } else {
                return '(bool) \'' . $value.'\'';
            }
        }
    }

    public function isExpression($expression)
    {
        return preg_match(self::EXPRESSION_REGEX, $expression, $matches);
    }

    public function shouldSkipProperty($exclusion, $context)
    {
        if (!$context->getExclusionStrategy()) {
            return false;
        }

        $propertyMetadata = new RelationPropertyMetadata($exclusion);

        return $context->getExclusionStrategy()->shouldSkipProperty($propertyMetadata, $context);
    }

    public function excludeIfExists($exclusion)
    {
        return (null !== $exclusion and null !== $exclusion->getExcludeIf());
    }
}
