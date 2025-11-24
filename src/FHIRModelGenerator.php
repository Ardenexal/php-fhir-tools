<?php

namespace Ardenexal\FHIRTools;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;

use function Symfony\Component\String\u;

/**
 * Class FHIRTools
 *
 * @phpstan-type GenerationContext array{targetNamespace: PhpNamespace, classPrefix: string}
 *
 * @package Ardenexal\FHIRTools
 */
class FHIRModelGenerator
{
    private BuilderContext $builderContext;

    /**
     * @param BuilderContext $builderContext
     */
    public function __construct(
        BuilderContext $builderContext
    ) {
        $this->builderContext = $builderContext;
    }

    /**
     * @param mixed[] $structureDefinition
     * @param string  $version
     *
     * @return ClassType
     */
    public function generateModelClass(array $structureDefinition, string $version): ClassType
    {
        $className = BuilderContext::DEFAULT_CLASS_PREFIX . u($structureDefinition['name'])->pascal();
        $namespace = $this->builderContext->getElementNamespace($version);
        $class     = new ClassType($className, $namespace);

        if ($structureDefinition['abstract'] === true) {
            $class->setAbstract();
        }
        if (isset($structureDefinition['baseDefinition'])) {
            $parent = str_replace('http://hl7.org/fhir/StructureDefinition/', '', $structureDefinition['baseDefinition']);
            $class->setExtends($namespace->getName() . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($parent)->pascal());
        }

        $class->addComment('@author ' . $structureDefinition['publisher']);
        $class->addComment('@see ' . $structureDefinition['url']);
        $class->addComment('@description ' . $structureDefinition['snapshot']['element'][0]['definition']);

        $constructor      = $class->addMethod('__construct');
        $parentParameters = [];

        foreach ($structureDefinition['snapshot']['element'] as $element) {
            $nameParts = array_slice(explode('.', $element['path']), 1);
            // First level elements are properties of the class
            if (count($nameParts) === 1) {
                if (
                    $element['path'] !== $element['base']['path']
                    && ! in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($element, $constructor, $version);
            }
        }
        if (isset($structureDefinition['baseDefinition'])) {
            $constructor->addBody('parent::__construct($' . implode(', $', $parentParameters) . ');');
        }


        return $class;
    }

    /**
     * @param array{
     *     id: string,
     *     path: string,
     *     base?: array{path: string},
     *     short: string,
     *     description: string,
     *     comment: string,
     *     min: int,
     *     max: string,
     *     type: array<int,array{
     *          extension?: array<int,array{url: string, valueUrl: string}>,
     *          code: string
     *      }>,
     *     binding?: array{strength: string, valueSet: string},
     * }                    $element
     * @param Method        $method
     * @param string        $version
     * @param EnumType|null $enum
     *
     * @return void
     */
    private function addElementAsProperty(array $element, Method $method, string $version, ?EnumType $enum = null): void
    {
        $types = array_map(function(array $type) use ($enum, $version, $element) {
            $code = $type['code'];

            $targetElementNamespace = $this->builderContext->getElementNamespace($version)->getName();
            $targetEnumNamespace    = $this->builderContext->getEnumNamespace($version)->getName();
            if ($code === 'http://hl7.org/fhirpath/System.String') {
                if (isset($element['base']['path']) && $element['base']['path'] === 'integer.value') {
                    return 'int';
                }
                $fhirTypeExtension = array_find($type['extension'] ?? [], fn ($ext) => $ext['url'] === 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fhir-type');
                if ($enum !== null && $fhirTypeExtension !== null && $fhirTypeExtension['valueUrl'] === 'code') {
                    return '\\' . $targetEnumNamespace . '\\' . $enum->getName();
                }

                return 'string';
            }
            if ($code === 'http://hl7.org/fhirpath/System.Boolean') {
                return 'bool';
            }
            if ($code === 'http://hl7.org/fhirpath/System.Integer') {
                return 'int';
            }
            if ($code === 'http://hl7.org/fhirpath/System.Decimal') {
                return 'float';
            }
            if ($code === 'http://hl7.org/fhirpath/System.DateTime') {
                return '\\' . \DateTimeInterface::class;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Date') {
                return 'string';
            }
            if ($code === 'http://hl7.org/fhirpath/System.Time') {
                return 'string';
            }


            if ($code === 'code' && isset($element['binding']['strength']) && $element['binding']['strength'] === 'required') {
                $enum = $this->builderContext->getEnum($element['binding']['valueSet']);
                if ($enum !== null) {
                    $codeType = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . $enum->getName() . 'Type';
                } else {
                    $valueSet = $element['binding']['valueSet'];
                    // TODO handle versioned value sets better
                    $enumName = u(basename($valueSet, '|4.3.0'))->pascal();
                    /** @var class-string $codeType */
                    $codeType = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . $enumName . 'Type';
                    $this->builderContext->addPendingType($valueSet, $codeType);
                }

                return $codeType;
            }

            return '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($code)->pascal();
        }, $element['type']);


        $parameterName = $this->convertToMethodName($element['path']);

        $isArray = in_array($element['max'], ['1', '0'], true) === false;

        $isNullable = $element['min'] === 0 && $isArray === false;

        if ($element['max'] !== '0') {
            $method->addComment('@var ' . ($isNullable ? '?' : '') . implode('|', $types) . ($isArray ? '[]' : '') . ' $' . $parameterName . ' ' . $element['short']);
            if ($isArray) {
                $method->addPromotedParameter($parameterName, [])
                    ->setNullable(false)
                    ->setType('array');
            } elseif ($isNullable === false) {
                $method->addPromotedParameter($parameterName)
                    ->setNullable(false)
                    ->setType(implode('|', $types));
            } else {
                $method->addPromotedParameter($parameterName, null)
                    ->setType(implode('|', $types));
            }
        }
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function convertToMethodName(string $path): string
    {
        return lcfirst(u($path)->split('.')[1]->camel()->toString());
    }

    /**
     * @param EnumType $enumType
     * @param string   $version
     *
     * @return ClassType
     */
    public function generateModelCodeType(EnumType $enumType, string $version): ClassType
    {
        $elementNamespace    = $this->builderContext->getElementNamespace($version);
        $structureDefinition = $this->builderContext->getDefinition('http://hl7.org/fhir/StructureDefinition/code');
        $className           = $enumType->getName() . 'Type';
        $class               = new ClassType($className, $elementNamespace);

        //        if (isset($structureDefinition['baseDefinition'])) {
        //            $parent = str_replace('http://hl7.org/fhir/StructureDefinition/', '', $structureDefinition['baseDefinition']);
        //            $class->setExtends($elementNamespace->getName() . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($parent)->pascal());
        //        }

        $class->addComment('@author ' . $structureDefinition['publisher']);
        $class->addComment('@see ' . $structureDefinition['url']);
        $class->addComment('@description ' . $structureDefinition['snapshot']['element'][0]['definition']);

        $constructor      = $class->addMethod('__construct');
        $parentParameters = [];

        foreach ($structureDefinition['snapshot']['element'] as $element) {
            $nameParts = array_slice(explode('.', $element['path']), 1);
            // First level elements are properties of the class
            if (count($nameParts) === 1) {
                if (
                    $element['path'] !== $element['base']['path']
                    && ! in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($element, $constructor, $version, $enumType);
            }
        }
        //        if (isset($structureDefinition['baseDefinition'])) {
        //            $constructor->addBody('parent::__construct($' . implode(', $', $parentParameters) . ');');
        //        }


        return $class;
    }
}
