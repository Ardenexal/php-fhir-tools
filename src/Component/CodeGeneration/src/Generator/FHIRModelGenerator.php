<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\String\u;

/**
 * Generates PHP model classes from FHIR StructureDefinitions
 *
 * This class is responsible for converting FHIR StructureDefinitions into
 * PHP classes with proper type hints, validation constraints, and documentation.
 * It handles:
 *
 * - Class generation from StructureDefinitions
 * - Property creation with appropriate PHP types
 * - Constructor generation with promoted properties
 * - Inheritance relationships between FHIR types
 * - Validation constraint application
 * - Nested element processing and class creation
 * - Content reference resolution
 * - Enhanced error handling and validation
 *
 * The generator produces PSR-12 compliant code with comprehensive PHPDoc
 * annotations and Symfony validation constraints.
 *
 * @phpstan-type GenerationContext array{targetNamespace: PhpNamespace, classPrefix: string}
 * @phpstan-type ElementProperties array{
 *      path: string,
 *      id?: string,
 *      base?: array{path: string},
 *      short?: string,
 *      description?: string,
 *      comment?: string,
 *      definition?: string,
 *      min?: int,
 *      max?: string,
 *      contentReference?: string,
 *      type?: array<int,array{extension?: array<int,array{url: string, valueUrl: string}>, code: string}>,
 *      binding?: array{strength: string, valueSet: string}
 *  }
 * @phpstan-type NestedElementArray array<string, mixed>
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools\Component\CodeGeneration
 */
class FHIRModelGenerator implements GeneratorInterface
{
    /**
     * Default prefix for all generated FHIR classes
     */
    public const DEFAULT_CLASS_PREFIX = 'FHIR';

    /**
     * Check if this generator can handle the given definition
     *
     * @param array<string, mixed> $definition The FHIR definition to check
     *
     * @return bool True if this generator can handle the definition
     */
    public function canGenerate(array $definition): bool
    {
        return isset($definition['resourceType']) && $definition['resourceType'] === 'StructureDefinition';
    }

    /**
     * Get the priority of this generator (higher numbers = higher priority)
     *
     * @return int The priority value
     */
    public function getPriority(): int
    {
        return 100; // High priority for structure definitions
    }

    /**
     * Generate PHP code from FHIR definition
     *
     * @param array<string, mixed> $definition The FHIR definition to generate code from
     * @param string $version The FHIR version
     * @param BuilderContextInterface $context The builder context for managing generated types
     *
     * @return ClassType The generated PHP class
     *
     * @throws GenerationException When generation fails
     */
    public function generate(array $definition, string $version, BuilderContextInterface $context): ClassType
    {
        return $this->generateModelClass($definition, $version, $context);
    }

    /**
     * @param array<string, mixed> $structureDefinition
     * @param string $version
     * @param BuilderContextInterface $builderContext
     *
     * @return ClassType
     */
    public function generateModelClass(array $structureDefinition, string $version, BuilderContextInterface $builderContext): ClassType
    {
        $className = self::DEFAULT_CLASS_PREFIX . u($structureDefinition['name'])->pascal();
        $namespace = $builderContext->getElementNamespace($version);
        $class     = new ClassType($className, $namespace);

        if ($structureDefinition['abstract'] === true) {
            $class->setAbstract();
        }
        if (isset($structureDefinition['baseDefinition'])) {
            $parent = str_replace('http://hl7.org/fhir/StructureDefinition/', '', $structureDefinition['baseDefinition']);
            $class->setExtends($namespace->getName() . '\\' . self::DEFAULT_CLASS_PREFIX . u($parent)->pascal());
        }

        // Add appropriate FHIR attributes based on the structure definition kind
        if ($structureDefinition['kind'] === 'resource') {
            // Note: Attributes will be added by the serialization component
            $class->addComment('@fhir-resource ' . $structureDefinition['name']);
        } elseif ($structureDefinition['kind'] === 'primitive-type') {
            $class->addComment('@fhir-primitive ' . $structureDefinition['name']);
        } elseif ($structureDefinition['kind'] === 'complex-type') {
            // Check if this is a backbone element by looking at the base definition
            $isBackboneElement = isset($structureDefinition['baseDefinition'])
                                && str_contains($structureDefinition['baseDefinition'], 'BackboneElement');

            if ($isBackboneElement) {
                // Extract parent resource and element path from the structure definition name
                $elementPath    = $structureDefinition['name'];
                $parentResource = explode('.', $elementPath)[0];
                $class->addComment('@fhir-backbone-element ' . $elementPath);
            } else {
                $class->addComment('@fhir-complex-type ' . $structureDefinition['name']);
            }
        }

        if (isset($structureDefinition['publisher'])) {
            $class->addComment('@author ' . $structureDefinition['publisher']);
        }
        $class->addComment('@see ' . $structureDefinition['url']);
        if (isset($structureDefinition['snapshot']['element'][0]['definition']) === true) {
            $class->addComment('@description ' . $structureDefinition['snapshot']['element'][0]['definition']);
        }
        $class->addMethod('__construct');
        $parentParameters = [];

        if (isset($structureDefinition['snapshot']) === true) {
            $elements = $this->nestElements($structureDefinition['snapshot']['element']);

            foreach ($elements['_properties'] as $property) {
                $element = $property['_element'];
                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->createForElement($class, $property['_element'], $property['_properties'], $version, $builderContext);
            }
        }

        return $class;
    }

    /**
     * @param ClassType                        $classType
     * @param array<string, mixed>             $classElement
     * @param array<string,array<string, mixed>> $propertyElements
     * @param string                           $version
     * @param BuilderContextInterface          $builderContext
     *
     * @return ClassType
     */
    public function createForElement(ClassType $classType, array $classElement, array $propertyElements, string $version, BuilderContextInterface $builderContext): ClassType
    {
        $constructor      = $classType->getMethod('__construct');
        $parentParameters = [];

        foreach ($propertyElements as $key => $propertyElement) {
            // This is a primitive type
            if (!array_key_exists('_properties', $propertyElement) || count($propertyElement['_properties']) === 0) {
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
                $this->addElementAsProperty($propertyElement['_element'], $constructor, $version, $builderContext);
            } else {
                $element    = $propertyElement['_element'];
                $className  = self::DEFAULT_CLASS_PREFIX . u($element['path'])->pascal();
                $namespace  = $builderContext->getElementNamespace($version);
                $childClass = new ClassType($className, $namespace);
                $childClass->addMethod('__construct');
                $builderContext->addType($element['path'], $childClass);

                // Determine if this is a backbone element or regular element
                $isBackboneElement = isset($element['type'][0]['code']) && $element['type'][0]['code'] === 'BackboneElement';
                $isElement         = isset($element['type'][0]['code']) && $element['type'][0]['code'] === 'Element';

                if ($isBackboneElement) {
                    // Add comment for backbone elements
                    $elementPath    = $element['path'];
                    $parentResource = explode('.', $elementPath)[0];
                    $childClass->addComment('@fhir-backbone-element ' . $elementPath);
                    $childClass->setExtends($namespace->getName() . '\\' . self::DEFAULT_CLASS_PREFIX . 'BackboneElement');
                } elseif ($isElement) {
                    // Add comment for regular complex elements
                    $childClass->addComment('@fhir-complex-type ' . $element['path']);
                    $childClass->setExtends($namespace->getName() . '\\' . self::DEFAULT_CLASS_PREFIX . 'Element');
                }

                if (isset($element['definition'])) {
                    $childClass->addComment('@description ' . $element['definition']);
                }
                if (
                    $element['path'] !== $element['base']['path']
                    && !in_array($element['path'], $parentParameters, true)
                    && $element['max'] !== '0'
                ) {
                    $parentParameters[] = $this->convertToMethodName($element['base']['path']);
                }
                $this->addElementAsProperty($element, $constructor, $version, $builderContext);

                if (isset($propertyElement['_properties'])) {
                    $this->createForElement($childClass, $element, $propertyElement['_properties'], $version, $builderContext);
                }
            }
        }

        if ($classType->getExtends() !== null) {
            $constructor->addBody('parent::__construct(' . implode(', ', $parentParameters) . ');');
        }

        return $classType;
    }

    /**
     * @param array<string, mixed> $element
     * @param Method $method
     * @param string $version
     * @param BuilderContextInterface $builderContext
     * @param EnumType|null $enum
     *
     * @return void
     */
    private function addElementAsProperty(array $element, Method $method, string $version, BuilderContextInterface $builderContext, ?EnumType $enum = null): void
    {
        $types = [];
        if (!isset($element['type']) && isset($element['contentReference'])) {
            $contentRef = preg_replace('/^.*#/', '', $element['contentReference']);
            if ($contentRef === null) {
                throw GenerationException::invalidElementPath($element['contentReference']);
            }
            $relatedClass = $builderContext->getType($contentRef);
            if ($relatedClass === null) {
                throw GenerationException::missingContentReference($element['contentReference'], $element['path']);
            }
            $types[] = '\\' . $builderContext->getElementNamespace($version)->getName() . '\\' . $relatedClass->getName();
        } elseif (isset($element['type'])) {
            foreach ($element['type'] as $type) {
                $code = $type['code'];

                $targetElementNamespace = $builderContext->getElementNamespace($version)->getName();
                $targetEnumNamespace    = $builderContext->getEnumNamespace($version)->getName();
                if ($code === 'http://hl7.org/fhirpath/System.String') {
                    if (isset($element['base']['path']) && $element['base']['path'] === 'integer.value') {
                        $types[] = 'int';
                        continue;
                    }
                    $fhirTypeExtension = array_find($type['extension'] ?? [], fn ($ext) => $ext['url'] === 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fhir-type');
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
                    $types[] = '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . u($code)->pascal();
                    $types[] = 'string';
                    continue;
                }

                if ($code === 'Element') {
                    $elementClass = u($element['path'])->pascal()->toString();
                    $types[] = '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . $elementClass;
                    continue;
                }

                if ($code === 'BackboneElement') {
                    $elementClass = u($element['path'])->pascal()->toString();
                    $types[] = '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . $elementClass;
                    continue;
                }

                if ($code === 'code' && isset($element['binding']['strength']) && $element['binding']['strength'] === 'required') {
                    $enum = $builderContext->getEnum($element['binding']['valueSet']);
                    if ($enum !== null) {
                        $codeType = '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . $enum->getName() . 'Type';
                    } else {
                        $valueSet = $element['binding']['valueSet'];
                        // TODO handle versioned value sets better
                        $valueSetData = $builderContext->getDefinition(explode('|', $valueSet)[0]);
                        if ($valueSetData !== null) {
                            $codeType = '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . u($valueSetData['name'])->pascal() . 'Type';
                            $enumType = '\\' . $targetEnumNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . u($valueSetData['name'])->pascal();
                        } else {
                            $codeType = 'string'; // Fallback
                        }
                    }

                    $types[] = $codeType;
                    continue;
                }

                $types[] = '\\' . $targetElementNamespace . '\\' . self::DEFAULT_CLASS_PREFIX . u($code)->pascal();
            }
        }

        $parameterName = $this->convertToMethodName($element['path']);

        $maxValue = $element['max'] ?? '1';
        $minValue = $element['min'] ?? 0;

        $isArray    = !in_array($maxValue, ['1', '0'], true);
        $isNullable = $minValue === 0 && $isArray === false;

        if ($maxValue !== '0') {
            $shortDescription = $element['short'] ?? '';
            if ($isArray) {
                $method->addPromotedParameter($parameterName, [])
                       ->setNullable(false)
                       ->setType('array')
                       ->addComment('@var  array<' . implode('|', $types) . '> ' . $parameterName . ' ' . $shortDescription);
            } else {
                $parameter = $method->addPromotedParameter($parameterName, null)
                                    ->setType(implode('|', $types))
                                    ->addComment('@var null|' . implode('|', $types) . ' ' . $parameterName . ' ' . $shortDescription);
                if ($isNullable === false) {
                    $parameter->addAttribute(NotBlank::class);
                }
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
        $pathParts = u($path)->split('.');
        $lastPart  = array_last($pathParts);
        if ($lastPart === null) {
            throw GenerationException::invalidElementPath($path);
        }

        return lcfirst($lastPart->camel()->toString());
    }

    /**
     * Nests elements by their dot-separated path into a multi-dimensional array.
     * Each part of the path becomes a key at the corresponding array depth.
     * The element details are stored in a reserved '_element' key at the deepest level.
     * Child elements are nested under '_properties'.
     *
     * @param array<int,array<string, mixed>> $elements
     *
     * @return array{_properties: array<string, array<string, mixed>>}
     */
    private function nestElements(array $elements): array
    {
        /** @var array{_properties: array<string, array<string, mixed>>} $nestedArray */
        $nestedArray = ['_properties' => []];
        foreach ($elements as $item) {
            $pathParts = explode('.', $item['path']);
            $current   = &$nestedArray;
            foreach ($pathParts as $part) {
                if (!array_key_exists('_properties', $current)) {
                    $current['_properties'] = [];
                }
                if (!array_key_exists($part, $current['_properties'])) {
                    $current['_properties'][$part] = [];
                }
                $current = &$current['_properties'][$part];
            }
            $current['_element'] = $item;
            if (!array_key_exists('_properties', $current)) {
                $current['_properties'] = [];
            }
        }

        return $nestedArray;
    }
}