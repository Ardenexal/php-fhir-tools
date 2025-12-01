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
 * @phpstan-type ElementProperties array{
 *      path: string,
 *      id?: string,
 *      base?: array{path: string},
 *      short?: string,
 *      description?: string,
 *      comment?: string,
 *      min?: int,
 *      max?: string,
 *      type?: array<int,array{extension?: array<int,array{url: string, valueUrl: string}>, code: string}>,
 *      binding?: array{strength: string, valueSet: string}
 *  }
 * @phpstan-type NestedElement array{
 *      _element: ElementProperties,
 *      _properties: array<string, NestedElement>
 *  }
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
        $elements         = $this->nestElements($structureDefinition['snapshot']['element']);

        foreach ($elements['_properties'] as $key => $property) {
            $element = $property['_element'];
            if (
                $element['path'] !== $element['base']['path']
                && !in_array($element['path'], $parentParameters, true)
                && $element['max'] !== '0'
            ) {
                $parentParameters[] = $this->convertToMethodName($element['base']['path']);
            }
            $this->createForElement($class, $property['_element'], $property['_properties'], $version);
        }
//        if (isset($structureDefinition['baseDefinition'])) {
//            $constructor->addBody('parent::__construct($' . implode(', $', $parentParameters) . ');');
//        }

        return $class;
    }

    /**
     * @param NestedElement            $classElement
     * @param array<int,NestedElement> $propertyElements
     * @param string                   $version
     *
     * @return ClassType
     */
    public function createForElement(ClassType $classType, array $classElement, array $propertyElements, string $version): ClassType
    {
        $constructor      = $classType->getMethod('__construct');
        $parentParameters = [];
        foreach ($propertyElements as $key => $propertyElement) {
            // This is a primitive type
            if (count($propertyElement['_properties']) === 0) {
                if ($propertyElement['_element']['max'] === '0') {
                    continue;
                }
                $element = $propertyElement['_element'];
                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($propertyElement['_element'], $constructor, $version);
            } else {
                $element    = $propertyElement['_element'];
                $className  = BuilderContext::DEFAULT_CLASS_PREFIX . u($element['path'])->pascal();
                $namespace  = $this->builderContext->getElementNamespace($version);
                $childClass = new ClassType($className, $namespace);
                $childConstructor = $childClass->addMethod('__construct');
                $this->builderContext->addType($element['path'], $childClass);

                if (isset($element['base']) && $element['type'][0]['code'] === 'Element') {
                    $childClass->setExtends($namespace->getName() . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . 'Element');
                }

                $childClass->addComment('@description ' . $element['definition']);
                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($element, $childConstructor, $version);

                $this->createForElement($childClass, $element, $propertyElement['_properties'], $version);
            }
        }

        if ($classType->getExtends() !== null) {
            $constructor->addBody('parent::__construct($' . implode(', $', $parentParameters) . ');');
        }

        return $classType;

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
        $types = [];
        foreach ($element['type'] as $type) {
            $code = $type['code'];

            $targetElementNamespace = $this->builderContext->getElementNamespace($version)->getName();
            $targetEnumNamespace    = $this->builderContext->getEnumNamespace($version)->getName();
            if ($code === 'http://hl7.org/fhirpath/System.String') {
                if (isset($element['base']['path']) && $element['base']['path'] === 'integer.value') {
                    $types[] = 'int';
                    continue;
                }
                $fhirTypeExtension = array_find($type['extension'] ?? [], fn($ext) => $ext['url'] === 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fhir-type');
                if ($enum !== null && $fhirTypeExtension !== null && $fhirTypeExtension['valueUrl'] === 'code') {
                    $types[] = '\\' . $targetEnumNamespace . '\\' . $enum->getName();
                    continue;
                }

                $types[] = 'string';
                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Boolean') {
                $types[] = 'bool';
                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Integer') {
                $types[] = 'int';
                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Decimal') {
                $types[] = 'float';
                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.DateTime') {
                $types[] = '\\' . \DateTimeInterface::class;
                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Date') {
                $types[] = 'string';
                continue;
            }
            if ($code === 'http://hl7.org/fhirpath/System.Time') {
                $types[] = 'string';
                continue;
            }

            if ($code === 'string') {
                $types[] = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($code)->pascal();
                $types[] = 'string';
                continue;
            }

            if ($code === 'Element') {
                $elementClass = u($element['path'])->pascal()->toString();

                $types[] = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . $elementClass;
                continue;
            }


            if ($code === 'code' && isset($element['binding']['strength']) && $element['binding']['strength'] === 'required') {
                $enum = $this->builderContext->getEnum($element['binding']['valueSet']);
                if ($enum !== null) {
                    $codeType = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . $enum->getName() . 'Type';
                } else {
                    $valueSet = $element['binding']['valueSet'];
                    // TODO handle versioned value sets better
                    $valueSetData = $this->builderContext->getDefinition(explode('|', $valueSet)[0]);
                    /** @var class-string $codeType */
                    $codeType = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($valueSetData['name'])->pascal() . 'Type';
                    $this->builderContext->addPendingType($valueSet, $codeType);
                }

                $types[] = $codeType;
                continue;
            }

            $types[] = '\\' . $targetElementNamespace . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($code)->pascal();
        }


        $parameterName = $this->convertToMethodName($element['path']);

        $isArray = in_array($element['max'], ['1', '0'], true) === false;

        $isNullable = $element['min'] === 0 && $isArray === false;
        if ($element['max'] !== '0') {
            if ($isArray) {
                $method->addPromotedParameter($parameterName, [])
                       ->setNullable(false)
                       ->setType('array')
                       ->addComment('@var  array<' . implode('|', $types) . '> $' . $parameterName . ' ' . $element['short']);
            } elseif ($isNullable === false) {
                $method->addPromotedParameter($parameterName)
                       ->setNullable(false)
                       ->setType(implode('|', $types))
                       ->addComment('@var ' . implode('|', $types) . ' $' . $parameterName . ' ' . $element['short']);
            } else {
                $method->addPromotedParameter($parameterName, null)
                       ->setType(implode('|', $types))
                       ->addComment('@var null|' . implode('|', $types) . ' $' . $parameterName . ' ' . $element['short']);
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
        return lcfirst(array_last(u($path)->split('.'))->camel()->toString());
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
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($element, $constructor, $version, $enumType);
            }
        }


        return $class;
    }

    /**
     * @param array{
     * id: string,
     * path: string,
     * base?: array{path: string},
     * short: string,
     * description: string,
     * comment: string,
     * min: int,
     * max: string,
     * type: array<int,array{
     * extension?: array<int,array{url: string, valueUrl: string}>,
     * code: string
     * }>,
     * binding?: array{strength: string, valueSet: string},
     * }             $element
     * @param string $version
     *
     * @return ClassType
     */
    private function generateElementClass(array $element, string $version): ClassType
    {
        $elementClass = $this->builderContext->getType(u($element['path'])->pascal()->toString());
        $className    = BuilderContext::DEFAULT_CLASS_PREFIX . $elementClass . 'Element';
        $namespace    = $this->builderContext->getElementNamespace($version);
        $class        = new ClassType($className, $namespace);


        if (isset($element['base'])) {
            $parent = str_replace('http://hl7.org/fhir/StructureDefinition/', '', $element['base']);
            $class->setExtends($namespace->getName() . '\\' . BuilderContext::DEFAULT_CLASS_PREFIX . u($parent)->pascal());
        }

        //        $class->addComment('@author ' . $structureDefinition['publisher']);
        //        $class->addComment('@see ' . $structureDefinition['url']);
        $class->addComment('@description ' . $element['description']);

        $constructor      = $class->addMethod('__construct');
        $parentParameters = ['id', 'extension'];


        if (isset($structureDefinition['baseDefinition'])) {
            $constructor->addBody('parent::__construct($' . implode(', $', $parentParameters) . ');');
        }


        return $class;
    }

    /**
     *
     * Nests elements by their dot-separated path into a multi-dimensional array.
     * Each part of the path becomes a key at the corresponding array depth.
     * The element details are stored in a reserved '_element' key at the deepest level.
     * Child elements are nested under '_properties'.
     *
     * @param array<ElementProperties> $elements
     *
     * @return array<string, NestedElement>
     */
    private function nestElements(array $elements): array
    {
        $nestedArray = [];
        foreach ($elements as $item) {
            $pathParts = explode('.', $item['path']);
            $current   = &$nestedArray;
            foreach ($pathParts as $part) {
                if (!isset($current['_properties'])) {
                    $current['_properties'] = [];
                }
                if (!isset($current['_properties'][$part])) {
                    $current['_properties'][$part] = [];
                }
                $current =& $current['_properties'][$part];
            }
            $current['_element'] = $item;
            if (!isset($current['_properties'])) {
                $current['_properties'] = [];
            }
        }

        return $nestedArray;
    }
}
